<?php
/**
 * User: Sanchit <dev@minutephp.com>
 * Date: 10/15/2016
 * Time: 5:23 AM
 */

namespace Minute\Cart {

    use App\Model\MProduct;
    use App\Model\MProductCart;
    use Carbon\Carbon;
    use Minute\Config\Config;
    use Minute\Event\CartEvent;
    use Minute\Event\Dispatcher;
    use Minute\Event\WalletModifyEvent;
    use Minute\Event\WalletOrderEvent;
    use Minute\Event\WalletPurchaseEvent;
    use Minute\Http\HttpResponseEx;
    use Minute\Lang\Lang;
    use Minute\Log\LoggerEx;
    use Minute\Session\Session;

    class Cart {
        /**
         * @var Config
         */
        private $config;
        /**
         * @var LoggerEx
         */
        private $logger;
        /**
         * @var CartManager
         */
        private $cartManager;
        /**
         * @var Session
         */
        private $session;
        /**
         * @var Dispatcher
         */
        private $dispatcher;
        /**
         * @var HttpResponseEx
         */
        private $response;
        /**
         * @var Lang
         */
        private $lang;

        /**
         * Cart constructor.
         *
         * @param CartManager $cartManager
         * @param Config $config
         * @param Session $session
         * @param LoggerEx $logger
         * @param Dispatcher $dispatcher
         * @param HttpResponseEx $response
         * @param Lang $lang
         */
        public function __construct(CartManager $cartManager, Config $config, Session $session, LoggerEx $logger, Dispatcher $dispatcher, HttpResponseEx $response, Lang $lang) {
            $this->cartManager = $cartManager;
            $this->config      = $config;
            $this->session     = $session;
            $this->logger      = $logger;
            $this->dispatcher  = $dispatcher;
            $this->response    = $response;
            $this->lang        = $lang;
        }

        public function checkoutComplete(WalletOrderEvent $event) {
            if (($event->getItemType() === 'cart') && ($cart_id = $event->getItemId())) {
                /** @var MProductCart $cart */
                $cart    = MProductCart::find($cart_id);
                $product = MProduct::find($cart->product_id);
                $url     = $product->welcome_url ?: ($this->config->get('private/urls/welcome_url', '/purchase/complete'));

                if (!$cart->user_id) {
                    if ($user_id = $this->session->getLoggedInUserId()) {
                        $cart->user_id = $user_id;
                        $cart->save();

                        $modifyEvent = new WalletModifyEvent('cart', $cart_id, ['user_id' => $user_id]);
                        $this->dispatcher->fire(WalletModifyEvent::USER_WALLET_MODIFY, $modifyEvent);
                    } else {
                        $url = $this->response->getLoginRedirect($this->lang->getText('This step is required to complete your purchase!'), false, '/auth/purchase');
                    }
                }

                $event->setRedirect($url);
                $this->cartManager->upgrade('processing', $cart);
            }
        }

        public function cancel(WalletOrderEvent $event) {
            if (($event->getItemType() === 'cart') && ($cart_id = $event->getItemId())) {
                /** @var MProductCart $cart */
                if ($cart = MProductCart::find($cart_id)) {
                    $cart->status = 'cancel';
                    $cart->save();

                    $this->dispatcher->fire(CartEvent::USER_CART_CANCELED, new CartEvent($cart->user_id, $cart->toArray()));
                }
            }
        }

        public function purchase(WalletOrderEvent $event) {
            if (($event->getItemType() === 'cart') && ($cart_id = $event->getItemId())) {
                /** @var MProductCart $cart */
                $cart    = MProductCart::find($cart_id);
                $pending = $this->isPending($cart->status);
                $type    = $pending && !empty($cart->setup_amount) ? 'setup' : 'rebill';
                $amount  = $type === 'setup' ? $cart->setup_amount : $cart->rebill_amount;

                if ($pending || (($cart->status === 'rebill') && ($cart->next_rebill_at <= Carbon::now()))) {
                    $event = new WalletPurchaseEvent($cart->user_id, 'cart', $cart_id, $amount);
                    $this->dispatcher->fire(WalletPurchaseEvent::USER_WALLET_PURCHASE, $event);
                }
            }
        }

        public function purchaseConfirm(WalletPurchaseEvent $event) {
            if (($event->getItemType() === 'cart') && ($cart_id = $event->getItemId())) {
                /** @var MProductCart $cart */
                $cart     = MProductCart::find($cart_id);
                $pending  = $this->isPending($cart->status);
                $type     = $pending && !empty($cart->setup_amount) ? 'setup' : 'rebill';
                $expected = $type === 'setup' ? $cart->setup_amount : $cart->rebill_amount;
                $amount   = $event->getAmount();

                if ($amount !== $expected) {
                    $this->logger->critical("Expected amount: $expected, Got amount: $amount");
                }

                if (!empty($cart->rebill_amount)) {
                    $rebill_after = $type === 'setup' ? $cart->setup_time : $cart->rebill_time;
                    $rebill_str   = strtr($rebill_after, ['d' => ' day', 'w' => ' week', 'm' => ' month', 'y' => ' year']);
                    $rebill_date  = strtotime($rebill_str);

                    $cart->status         = 'rebill';
                    $cart->next_rebill_at = Carbon::createFromTimestamp($rebill_date);
                } else {
                    $cart->status = 'complete';
                }

                $cart->save();

                $this->cartManager->upgrade($type, $cart);
                $this->dispatcher->fire(CartEvent::USER_CART_PURCHASED, new CartEvent($cart->user_id, $cart->toArray()));
            }
        }

        public function refund(WalletOrderEvent $event) {
            if (($event->getItemType() === 'cart') && ($cart_id = $event->getItemId())) {
                /** @var MProductCart $cart */
                $cart    = MProductCart::find($cart_id);
                $pending = $this->isPending($cart->status);

                if (!$pending) {
                    $payment = $event->getPayment();
                    $event   = new WalletPurchaseEvent($cart->user_id, 'cart', $cart_id, 0);
                    $this->dispatcher->fire(WalletPurchaseEvent::USER_WALLET_PURCHASE_CANCEL, $event);
                }
            }
        }

        public function refundConfirm(WalletPurchaseEvent $event) {
            if (($event->getItemType() === 'cart') && ($cart_id = $event->getItemId())) {
                /** @var MProductCart $cart */
                $cart    = MProductCart::find($cart_id);
                $pending = $this->isPending($cart->status);

                if (!$pending) {
                    $type         = $event->getAmount() === $cart->setup_amount ? 'setup' : 'rebill';
                    $cart->status = 'refund';
                    $cart->save();

                    $this->cartManager->downgrade($type, $cart);
                    $this->dispatcher->fire(CartEvent::USER_CART_REFUNDED, new CartEvent($cart->user_id, $cart->toArray()));
                }
            }
        }

        private function isPending($status) {
            return preg_match('/^(pending|cancel|refund)$/', $status) && true;
        }
    }
}
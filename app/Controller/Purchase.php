<?php
/**
 * Created by: MinutePHP framework
 */
namespace App\Controller {

    use App\Model\MProductCart;
    use Carbon\Carbon;
    use Minute\Config\Config;
    use Minute\Error\PurchaseError;
    use Minute\Event\Dispatcher;
    use Minute\Event\VoucherEvent;
    use Minute\Event\WalletOrderEvent;
    use Minute\Session\Session;
    use Minute\View\Redirection;

    class Purchase {
        /**
         * @var Session
         */
        private $session;
        /**
         * @var Dispatcher
         */
        private $dispatcher;
        /**
         * @var Config
         */
        private $config;

        /**
         * Purchase constructor.
         *
         * @param Session $session
         * @param Dispatcher $dispatcher
         * @param Config $config
         */
        public function __construct(Session $session, Dispatcher $dispatcher, Config $config) {
            $this->session    = $session;
            $this->dispatcher = $dispatcher;
            $this->config     = $config;

            MProductCart::unguard();
        }

        public function index($processor, $_product) {
            /** @var MProductCart $cart */
            $user_id = $this->session->getLoggedInUserId();
            $product = $_product[0];

            if ($product->enabled === 'true') {
                if ($coupon = $_COOKIE['coupon'] ?? '') {
                    $event = new VoucherEvent($coupon, $product->product_id);
                    $this->dispatcher->fire(VoucherEvent::VOUCHER_VERIFY, $event);

                    if ($discounts = $event->getValidCoupons()) {
                        $overrides = ['setup_amount', 'setup_time', 'rebill_amount', 'rebill_time'];

                        foreach ((array) $discounts as $discount) {
                            if ($discount['product_id'] === $product->product_id) {
                                foreach ($overrides as $override) {
                                    $newValue = $discount[$override] ?? null;

                                    if (!empty($newValue) || ((string) $newValue === '0')) {
                                        if (preg_match('/(\d+)%/', $newValue, $matches)) { //can be in percentage also
                                            $product->$override = $product->$override * min(1, max(0, (1 - ((float) $matches[1] / 100))));
                                        } else {
                                            $product->$override = $newValue;
                                        }
                                    }
                                }

                                break;
                            }
                        }
                    } else {
                        $coupon = '';
                    }
                }

                $cart    = MProductCart::create(['user_id' => $user_id, 'created_at' => Carbon::now(), 'product_id' => $product->product_id, 'setup_amount' => $product->setup_amount,
                                                 'setup_time' => $product->setup_time, 'rebill_amount' => $product->rebill_amount, 'rebill_time' => $product->rebill_time,
                                                 'coupon' => $coupon]);
                $ident   = $cart->product_cart_id;
                $payment = ['setup_amount' => (float) $product->setup_amount, 'setup_time' => $product->setup_time ?? '', 'rebill_amount' => (float) $product->rebill_amount,
                            'rebill_time' => $product->rebill_time ?? '', 'description' => $product->name];

                $event = new WalletOrderEvent($user_id, $processor, 'cart', $ident, $product->name, $payment);
                $this->dispatcher->fire(WalletOrderEvent::USER_WALLET_ORDER_START, $event);

                if ($url = $event->getRedirect()) {
                    return new Redirection($url);
                }

                //it will either redirect the cart to Checkout or fire a WalletOrderEvent::USER_WALLET_PURCHASE_COMPLETE that will redirect it too product welcome_url
                //either way it should not reach here
                throw new PurchaseError("No payment processor installed for $processor");
            }

            throw new PurchaseError("Product is disabled: " . $product->name);
        }
    }
}
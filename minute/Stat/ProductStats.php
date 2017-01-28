<?php
/**
 * User: Sanchit <dev@minutephp.com>
 * Date: 10/19/2016
 * Time: 8:17 AM
 */
namespace Minute\Stat {

    use App\Model\MProductCart;
    use App\Model\MProductStat;
    use Minute\Event\UserPaymentEvent;
    use Minute\Event\WalletOrderEvent;

    class ProductStats {
        public function updateStats(WalletOrderEvent $event) {
            $name = $event->getName();

            if ($cart_id = $event->getItemId()) {
                $cart = MProductCart::find($cart_id);

                if ($product_id = $cart->product_id) {
                    /** @var MProductStat $productStat */
                    $productStat = MProductStat::firstOrCreate(['product_id' => $product_id]);

                    if ($name === WalletOrderEvent::USER_WALLET_ORDER_START) {
                        $productStat->carts = ($productStat->carts ?? 0) + 1;
                    } elseif ($name === WalletOrderEvent::USER_WALLET_FIRST_PAYMENT) {
                        $productStat->conversions = ($productStat->conversions ?? 0) + 1;
                    } elseif ($name === WalletOrderEvent::USER_WALLET_ORDER_CANCELLED) {
                        $productStat->cancels = ($productStat->cancels ?? 0) + 1;
                    } elseif ($name === WalletOrderEvent::USER_WALLET_ORDER_REFUND) {
                        $productStat->refunds = ($productStat->refunds ?? 0) + 1;
                    }

                    $productStat->save();
                }
            }
        }
    }
}
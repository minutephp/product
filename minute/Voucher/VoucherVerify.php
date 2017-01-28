<?php
/**
 * User: Sanchit <dev@minutephp.com>
 * Date: 9/10/2016
 * Time: 1:19 PM
 */
namespace Minute\Voucher {

    use App\Model\MProductCoupon;
    use Carbon\Carbon;
    use Minute\Event\VoucherEvent;

    class VoucherVerify {
        public function verifyCoupon(VoucherEvent $event) {
            /** @var MProductCoupon $builder */
            $code      = $event->getCode();
            $productId = $event->getProductId();
            $builder   = MProductCoupon::where('code', '=', $code)->where('expires_at', '>', Carbon::now());

            if ($productId > 0) {
                $builder->where('product_id', '=', $productId)->limit(1);
            }

            $coupons = $builder->get();

            /** @var MProductCoupon $coupon */
            foreach ($coupons as $coupon) {
                $event->addValidCoupon($coupon->attributesToArray());
            }
        }
    }
}
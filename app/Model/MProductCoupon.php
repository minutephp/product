<?php
/**
 * Created by: MinutePHP Framework
 */
namespace App\Model {

    use Minute\Model\ModelEx;

    class MProductCoupon extends ModelEx {
        protected $table      = 'm_product_coupons';
        protected $primaryKey = 'product_coupon_id';
    }
}
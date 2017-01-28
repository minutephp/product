<?php
/**
 * Created by: MinutePHP Framework
 */
namespace App\Model {

    use Minute\Model\ModelEx;

    class MProductCart extends ModelEx {
        protected $table      = 'm_product_carts';
        protected $primaryKey = 'product_cart_id';
    }
}
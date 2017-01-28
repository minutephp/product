<?php
/**
 * Created by: MinutePHP Framework
 */
namespace App\Model {

    use Minute\Model\ModelEx;

    class MProduct extends ModelEx {
        protected $table      = 'm_products';
        protected $primaryKey = 'product_id';
    }
}
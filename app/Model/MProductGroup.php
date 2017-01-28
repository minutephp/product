<?php
/**
 * Created by: MinutePHP Framework
 */
namespace App\Model {

    use Minute\Model\ModelEx;

    class MProductGroup extends ModelEx {
        protected $table      = 'm_product_groups';
        protected $primaryKey = 'product_level_id';
    }
}
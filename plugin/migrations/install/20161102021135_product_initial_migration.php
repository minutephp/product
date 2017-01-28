<?php
use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class ProductInitialMigration extends AbstractMigration
{
    public function change()
    {
        // Automatically created phinx migration commands for tables from database minute

        // Migration for table m_product_carts
        $table = $this->table('m_product_carts', array('id' => 'product_cart_id'));
        $table
            ->addColumn('user_id', 'integer', array('null' => true, 'limit' => 11))
            ->addColumn('created_at', 'datetime', array())
            ->addColumn('product_id', 'integer', array('limit' => 11))
            ->addColumn('setup_amount', 'float', array('null' => true))
            ->addColumn('setup_time', 'string', array('null' => true, 'limit' => 10))
            ->addColumn('rebill_amount', 'float', array('null' => true))
            ->addColumn('rebill_time', 'string', array('null' => true, 'limit' => 10))
            ->addColumn('next_rebill_at', 'datetime', array('null' => true))
            ->addColumn('coupon', 'string', array('null' => true, 'limit' => 255))
            ->addColumn('status', 'enum', array('null' => true, 'default' => 'pending', 'values' => array('pending','complete','rebill','cancel','refund')))
            ->create();


        // Migration for table m_product_coupons
        $table = $this->table('m_product_coupons', array('id' => 'product_coupon_id'));
        $table
            ->addColumn('product_id', 'integer', array('limit' => 11))
            ->addColumn('created_at', 'datetime', array())
            ->addColumn('code', 'string', array('limit' => 255))
            ->addColumn('setup_amount', 'string', array('null' => true, 'limit' => 10))
            ->addColumn('setup_time', 'string', array('null' => true, 'limit' => 10))
            ->addColumn('rebill_amount', 'string', array('null' => true, 'limit' => 10))
            ->addColumn('rebill_time', 'string', array('null' => true, 'limit' => 10))
            ->addColumn('expires_at', 'datetime', array('null' => true))
            ->addColumn('comment', 'string', array('null' => true, 'limit' => 255))
            ->addIndex(array('product_id', 'code'), array('unique' => true))
            ->create();


        // Migration for table m_product_groups
        $table = $this->table('m_product_groups', array('id' => 'product_group_id'));
        $table
            ->addColumn('product_id', 'integer', array('limit' => 11))
            ->addColumn('payment_type', 'enum', array('default' => 'rebill', 'values' => array('processing','setup','rebill')))
            ->addColumn('group_name', 'string', array('limit' => 255))
            ->addColumn('credits', 'integer', array('null' => true, 'limit' => 11))
            ->addColumn('extend_expiry_days', 'integer', array('limit' => 11))
            ->addIndex(array('product_id'), array())
            ->create();


        // Migration for table m_product_stats
        $table = $this->table('m_product_stats', array('id' => 'product_stat_id'));
        $table
            ->addColumn('product_id', 'integer', array('limit' => 11))
            ->addColumn('carts', 'integer', array('default' => '0', 'limit' => 11))
            ->addColumn('conversions', 'integer', array('default' => '0', 'limit' => 11))
            ->addColumn('cancels', 'integer', array('default' => '0', 'limit' => 11))
            ->addColumn('refunds', 'integer', array('default' => '0', 'limit' => 11))
            ->addIndex(array('product_id'), array('unique' => true))
            ->create();


        // Migration for table m_products
        $table = $this->table('m_products', array('id' => 'product_id'));
        $table
            ->addColumn('created_at', 'datetime', array())
            ->addColumn('updated_at', 'datetime', array())
            ->addColumn('name', 'string', array('limit' => 255))
            ->addColumn('description', 'string', array('null' => true, 'limit' => 255))
            ->addColumn('setup_amount', 'float', array('null' => true))
            ->addColumn('setup_time', 'string', array('null' => true, 'limit' => 10))
            ->addColumn('rebill_amount', 'float', array('null' => true))
            ->addColumn('rebill_time', 'string', array('null' => true, 'limit' => 10))
            ->addColumn('welcome_url', 'string', array('null' => true, 'limit' => 255))
            ->addColumn('enabled', 'enum', array('values' => array('true','false')))
            ->addIndex(array('name'), array('unique' => true))
            ->create();


    }
}
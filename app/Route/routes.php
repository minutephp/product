<?php

/** @var Router $router */
use Minute\Model\Permission;
use Minute\Routing\Router;

$router->get('/admin/products', null, 'admin', 'm_products[5] as products', 'm_product_groups[products.product_id][1] as levels', 'm_product_coupons[products.product_id][1] as coupons',
    'm_product_stats[products.product_id] as stats')
       ->setReadPermission('products', 'admin')->setDefault('products', '*');
$router->post('/admin/products', null, 'admin', 'm_products as products', 'm_product_groups as levels', 'm_product_coupons as coupons')
       ->setAllPermissions('products', 'admin')->setAllPermissions('levels', 'admin')->setAllPermissions('coupons', 'admin')
       ->setDeleteCascade('products', ['levels', 'coupons']);

$router->get('/admin/products/edit/{product_id}', 'Admin/Products/Edit', 'admin', 'm_products[product_id] as products', 'm_product_groups[products.product_id][2] as levels',
    'm_product_coupons[products.product_id][5] as coupons')->setReadPermission('products', 'admin')->setDefault('product_id', '0');
$router->post('/admin/products/edit/{product_id}', null, 'admin', 'm_products as products', 'm_product_groups as levels', 'm_product_coupons as coupons')
       ->setAllPermissions('products', 'admin')->setAllPermissions('levels', 'admin')->setAllPermissions('coupons', 'admin')->setDefault('product_id', '0');

$router->get('/purchase/{processor}/{product_id}', 'Purchase', false, 'm_products[product_id] as product')
       ->setReadPermission('product', Permission::EVERYONE);

$router->get('/purchase/complete', null, false);

//voucher
$router->get('/_payments/vouchers/load', 'Payment/Vouchers.php@load', false)
       ->setDefault('_noView', true);
$router->post('/_payments/vouchers/apply', 'Payment/Vouchers.php@apply', false);
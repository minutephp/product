<?php
/**
 * User: Sanchit <dev@minutephp.com>
 * Date: 7/8/2016
 * Time: 7:57 PM
 */
namespace Minute\Menu {

    use Minute\Event\ImportEvent;

    class ProductMenu {
        public function adminLinks(ImportEvent $event) {
            $links = [
                'e-commerce' => ['title' => 'E-commerce', 'icon' => 'fa-shopping-cart', 'priority' => 6],
                'products' => ['title' => 'Products', 'icon' => 'fa-shopping-cart', 'href' => '/admin/products', 'priority' => 1, 'parent' => 'e-commerce']
            ];

            $event->addContent($links);
        }
    }
}
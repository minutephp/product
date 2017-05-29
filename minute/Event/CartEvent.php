<?php
/**
 * User: Sanchit <dev@minutephp.com>
 * Date: 5/28/2017
 * Time: 7:00 AM
 */

namespace Minute\Event {

    class CartEvent extends UserEvent {
        const USER_CART_PURCHASED = 'user.cart.purchased';
        const USER_CART_CANCELED  = 'user.cart.canceled';
        const USER_CART_REFUNDED  = 'user.cart.refunded';
    }
}
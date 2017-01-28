<?php

/** @var Binding $binding */
use Minute\Cart\Cart;
use Minute\Event\AdminEvent;
use Minute\Event\Binding;
use Minute\Event\TodoEvent;
use Minute\Event\VoucherEvent;
use Minute\Event\WalletOrderEvent;
use Minute\Event\WalletPurchaseEvent;
use Minute\Menu\ProductMenu;
use Minute\Stat\ProductStats;
use Minute\Todo\ProductTodo;
use Minute\Voucher\VoucherVerify;

$binding->addMultiple([
    //product
    ['event' => AdminEvent::IMPORT_ADMIN_MENU_LINKS, 'handler' => [ProductMenu::class, 'adminLinks']],

    //voucher
    ['event' => VoucherEvent::VOUCHER_VERIFY, 'handler' => [VoucherVerify::class, 'verifyCoupon']],

    //handle pdt
    ['event' => WalletOrderEvent::USER_WALLET_ORDER_RETURN, 'handler' => [Cart::class, 'checkoutComplete']],

    //handle ipn + wallet purchase
    ['event' => WalletOrderEvent::USER_WALLET_ORDER_PROCESSED, 'handler' => [Cart::class, 'purchase']],

    //mark cart as cancelled (to notify user that their subscription is ending)
    ['event' => WalletOrderEvent::USER_WALLET_ORDER_CANCELLED, 'handler' => [Cart::class, 'cancel']],

    //handle in-wallet purchase
    ['event' => WalletPurchaseEvent::USER_WALLET_PURCHASE_PASS, 'handler' => [Cart::class, 'purchaseConfirm']],
    ['event' => WalletPurchaseEvent::USER_WALLET_PURCHASE_CANCEL_PASS, 'handler' => [Cart::class, 'refundConfirm']],

    //mark cart as cancelled and take back the upgrades
    ['event' => WalletOrderEvent::USER_WALLET_ORDER_REFUND, 'handler' => [Cart::class, 'refund']],

    //for stats
    ['event' => "user.wallet.order.*", 'handler' => [ProductStats::class, 'updateStats']],

    //tasks
    ['event' => TodoEvent::IMPORT_TODO_ADMIN, 'handler' => [ProductTodo::class, 'getTodoList']],
]);
<?php
/**
 * User: Sanchit <dev@minutephp.com>
 * Date: 10/15/2016
 * Time: 6:52 AM
 */
namespace Minute\Cart {

    use App\Model\MProductCart;
    use App\Model\MProductGroup;
    use App\Model\MUserGroup;
    use Carbon\Carbon;

    class CartManager {

        public function upgrade(string $payment_type, MProductCart $cart) {
            if ($user_id = $cart->user_id) {
                $now    = Carbon::now();
                $levels = MProductGroup::where('payment_type', '=', $payment_type)->where('product_id', '=', $cart->product_id)->get();

                foreach ($levels as $level) {
                    /** @var MUserGroup $group */
                    $group   = MUserGroup::firstOrNew(['user_id' => $user_id, 'product_cart_id' => $cart->product_cart_id, 'group_name' => $level->group_name]);
                    $expires = Carbon::parse($group->expires_at) ?: $now;
                    $expires = $expires > $now ? $expires : Carbon::now();

                    $group->created_at = $group->created_at ?: $now;
                    $group->updated_at = $now;
                    $group->expires_at = $expires->addDay($level->extend_expiry_days ?: 1);
                    $group->credits    = ($group->credits ?: 0) + $level->credits;
                    $group->comments   = 'Updated by cart manager';

                    $group->save();
                }
            }
        }

        public function downgrade(string $payment_type, MProductCart $cart) {
            if ($user_id = $cart->user_id) {
                $levels = MProductGroup::where('payment_type', '=', $payment_type)->where('product_id', '=', $cart->product_cart_id)->get();

                foreach ($levels as $level) {
                    /** @var MUserGroup $group */
                    if ($group = MUserGroup::where('user_id', '=', $user_id)->where('product_cart_id', '=', $cart->product_cart_id)->where('group_name', '=', $level->group_name)->first()) {
                        $expires           = Carbon::parse($group->expires_at) ?: Carbon::now();
                        $group->expires_at = $expires->subDay($level->extend_expiry_days ?: 999);
                        $group->credits    = max(0, ($group->credits ?: 0) - $level->credits);
                        $group->comments   = 'Order refunded';
                        $group->save();
                    }
                }
            }
        }
    }
}
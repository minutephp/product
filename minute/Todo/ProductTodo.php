<?php
/**
 * User: Sanchit <dev@minutephp.com>
 * Date: 8/9/2016
 * Time: 4:56 PM
 */
namespace Minute\Todo {

    use App\Model\MProduct;
    use App\Model\MProductCoupon;
    use Carbon\Carbon;
    use Minute\Config\Config;
    use Minute\Event\ImportEvent;

    class ProductTodo {
        /**
         * @var TodoMaker
         */
        private $todoMaker;

        /**
         * MailerTodo constructor.
         *
         * @param TodoMaker $todoMaker - This class is only called by TodoEvent (so we assume TodoMaker is be available)
         */
        public function __construct(TodoMaker $todoMaker, Config $config) {
            $this->todoMaker = $todoMaker;
        }

        public function getTodoList(ImportEvent $event) {
            $todos[] = ['name' => "Create products", 'status' => MProduct::where('enabled', '=', 'true')->count() ? 'complete' : 'incomplete', 'link' => '/admin/products'];
            $todos[] = ['name' => "Create discount coupons", 'status' => MProductCoupon::where('expires_at', '>', Carbon::now())->count() ? 'complete' : 'incomplete', 'link' => '/admin/products'];
            $todos[] = $this->todoMaker->createManualItem("check-pricing", "Check pricing", 'Check product pricing as per "/pricing" page');

            $event->addContent(['Product' => $todos]);
        }
    }
}
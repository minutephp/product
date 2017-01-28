<?php
/**
 * Created by: MinutePHP framework
 */
namespace App\Controller\Payment {

    use Minute\Config\Config;
    use Minute\Error\VoucherError;
    use Minute\Event\Dispatcher;
    use Minute\Event\VoucherEvent;
    use Minute\Http\HttpResponseEx;

    class Vouchers {
        /**
         * @var HttpResponseEx
         */
        private $response;
        /**
         * @var Config
         */
        private $config;
        /**
         * @var Dispatcher
         */
        private $dispatcher;

        /**
         * Vouchers constructor.
         *
         * @param HttpResponseEx $response
         * @param Config $config
         * @param Dispatcher $dispatcher
         */
        public function __construct(HttpResponseEx $response, Config $config, Dispatcher $dispatcher) {
            $this->response   = $response;
            $this->config     = $config;
            $this->dispatcher = $dispatcher;
        }

        public function load() {
            if ($code = @$_COOKIE['coupon']) {
                $coupons = $this->findValidCoupons($code);
            }

            echo json_encode(['vouchers' => $coupons ?? []]);
        }

        public function apply($code) {
            if (!empty($code)) {
                $coupons = $this->findValidCoupons($code);

                if (count($coupons)) {
                    $this->response->setCookie('coupon', $code, '+1 year');

                    exit('OK');
                }
            }

            throw new VoucherError("Voucher code is invalid or expired: $code");
        }

        private function findValidCoupons($code) {
            $event = new VoucherEvent($code, 0);
            $this->dispatcher->fire(VoucherEvent::VOUCHER_VERIFY, $event);

            return $event->getValidCoupons();
        }
    }
}
<?php
namespace myfanclub\modules\preferences\service;

/**
 * Controller for member administration
 *
 *
 * @package  controller
 * @author   Ultra Sites Medienagentur <info@ultra-sites.de>
 * @version  alpha
 * @access   public
 * @see      http://myfanclub.ultra-sites.de
 */
class MyfcPreferencesPaymentCollectorService
{
    public function collectDynamicPayment($paymentAmount, $paymentAge, $paymentDescription)
    {
        array_shift($paymentAmount);
        $dynamicPayment = [];

        foreach ($paymentAmount as $key => $amount) {
            if (empty($paymentDescription[$key])) {
                $dynamicPayment[] = [
                    "amount" => $amount,
                    "age" => $paymentAge[$key]
                ];
            } else {
                $dynamicPayment[] = [
                    "special" => true,
                    "amount" => $amount,
                    "description" => $paymentDescription[$key]
                ];
            }
        }

        return json_decode(json_encode([$dynamicPayment]));
    }

    public function collectSolidPayment($paymentAmount)
    {
        $solidPayment[] =
            [
                "solid" => true,
                "amount" =>  $paymentAmount[0]
            ];

        return json_decode(json_encode($solidPayment));
    }
}

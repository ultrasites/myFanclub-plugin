<?php
namespace myfanclub\helper;

/**
 * Preference Data Helper
 *
 * Helper Class for Preferences
 *
 *
 * @package  helpers
 * @author   Ultra Sites Medienagentur <info@ultra-sites.de>
 * @version  alpha
 * @access   public
 * @see      http://myfanclub.ultra-sites.de
 */
class MyfcPreferenceData
{


    /**
     * Checks if preference value is solid
     *
     * @param $preferenceValue
     * @return boolean
     * @access public
     */
    public static function myfcIsSolidPayment($preferenceValue)
    {
        if (count($preferenceValue) == 1) {
            if ($preferenceValue[0]->solid) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Calculate payment amount
     *
     * This method calculates the member amount. Dynamic or solid payment
     *
     * @param $preferenceValue
     * @param $special
     * @param $age
     * @return integer
     * @access public
     */
    public static function myfcCalculateAmount($preferenceValue, $special = null, $age = null)
    {
        $zero = 0;

        if (self::myfcIsSolidPayment($preferenceValue)) {
            return $preferenceValue[0]->amount;
        } else {
            if (!empty($special)) {
                foreach ($preferenceValue as $item) {
                    if (isset($item->description) && $item->description == $special) {
                        return $item->amount;
                    }
                }
            } elseif (!empty($age)) {
                $ageArray = [];


                foreach ($preferenceValue as $item) {
                    if (empty($item->age)) {
                        continue;
                    }
                    $ageArray[$item->age] = $item->amount;
                }

                ksort($ageArray);

                foreach ($ageArray as $key => $amount) {
                    if ($age < $key) {
                        return $amount;
                    } else {
                        continue;
                    }
                }
            } else {
                return $zero;
            }
        }
    }


    /**
     * getPaymentSpecials
     *
     * @param $preferenceValue
     * @return array|string
     * @access public
     */
    public static function myfcGetPaymentSpecials($preferenceValue)
    {
        $paymentSpecials = [];

        if (self::myfcIsSolidPayment($preferenceValue)) {
            return '';
        } else {
            $i = 0;
            foreach ($preferenceValue as $item) {
                if (isset($item->description)) {
                    $paymentSpecials[$i] = $item;
                    $i++;
                }
            }
        }

        return $paymentSpecials;
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: chw
 * Date: 2019-06-25
 * Time: 09:41
 */

namespace App\Utils;


class CurrencyUtil
{

    public const REGION = 'qc';

    /**
     * @param $currency
     * @return string
     */
    public static function injectRegion($currency): string
    {
        $currency .= ('_' . self::REGION);
        return $currency;
    }

}

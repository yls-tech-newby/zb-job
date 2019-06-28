<?php
/**
 * Created by PhpStorm.
 * User: chw
 * Date: 2019-06-24
 * Time: 14:48
 */

namespace App\Utils;


class TransferUtil
{

    /**
     * 数组绑定对象
     * @param $array
     * @param $target
     * @param bool $fromCamel
     * @return mixed
     */
    public static function arrayBindEntity($array, $target, $fromCamel = false)
    {
        if (is_array($array) && !empty($array)) {
            foreach ($array as $key => $item) {
                if ($fromCamel) {
                    $target->$key = $item;
                } else {
                    $attrName = self::camelize($key);
                    $target->$attrName = $item;
                }
            }
        }
        return $target;
    }


    /**
     * 下划线转驼峰
     * @param $word
     * @param string $separator
     * @return string
     */
    public static function camelize($word, $separator = '_'): string
    {
        $word = $separator . str_replace($separator, ' ', strtolower($word));
        return ltrim(str_replace(' ', '', ucwords($word)), $separator);
    }


    /**
     * 驼峰命名转下划线命名
     * @param $camelCaps
     * @param string $separator
     * @return string
     */
    public static function unCamelize($camelCaps, $separator = '_'): string
    {
        return strtolower(preg_replace('/([a-z])([A-Z])/', '$1' . $separator . '$2', $camelCaps));
    }


}

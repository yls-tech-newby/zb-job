<?php
/**
 * Created by PhpStorm.
 * User: chw
 * Date: 2019-06-24
 * Time: 10:41
 */

namespace App\Utils;


class RequestUtil
{

    public const ACCESS_KEY = '41674e1d-ebb1-4c2d-a27f-2f02d9f5d008';

    public const SECRET_KEY = '42274229-6a4c-40f0-ab15-d059db31e17f';


    /**
     * zb api GET请求
     * @param $pUrl
     * @return bool|mixed|string
     */
    public static function zbGet($pUrl)
    {
        $tResult = false;

        $tCh = curl_init();
        curl_setopt($tCh, CURLOPT_URL, $pUrl);
        curl_setopt($tCh, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($tCh, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($tCh, CURLOPT_TIMEOUT, 2);

        for ($i = 0; $i < 5; $i++) {
            $tResult = curl_exec($tCh);
            if ($tResult) {
                break;
            }
        }

        curl_close($tCh);
        $tResult = json_decode($tResult, true);
        return $tResult;
    }


    /**
     * zb api POST请求
     * @param $pUrl
     * @param $pData
     * @return bool|mixed|string
     */
    public static function zbPost($pUrl, $pData)
    {
        $tCh = curl_init();
        curl_setopt($tCh, CURLOPT_POST, true);
        curl_setopt($tCh, CURLOPT_POSTFIELDS, $pData);
        curl_setopt($tCh, CURLOPT_HTTPHEADER, array('Content-type: application/x-www-form-urlencoded'));
        curl_setopt($tCh, CURLOPT_URL, $pUrl);
        curl_setopt($tCh, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($tCh, CURLOPT_SSL_VERIFYPEER, false);
        $tResult = curl_exec($tCh);
        curl_close($tCh);
        $tResult = json_decode($tResult, true);
        return $tResult;
    }


    /**
     * 生成签名
     * @param array $pParams
     * @return string
     */
    public static function injectSign($pParams = []): string
    {
        ksort($pParams);
        $tPreSign = http_build_query($pParams, '', '&');
        $SecretKey = sha1(self::SECRET_KEY);
        $tSign = hash_hmac('md5', $tPreSign, $SecretKey);
        $pParams['sign'] = $tSign;
        $pParams['reqTime'] = time() * 1000;
        return http_build_query($pParams, '', '&');
    }

}

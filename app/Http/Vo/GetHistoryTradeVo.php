<?php
/**
 * Created by PhpStorm.
 * User: chw
 * Date: 2019-06-24
 * Time: 14:56
 */

namespace App\Http\Vo;


class GetHistoryTradeVo
{
    /**
     * 交易时间时间戳
     * @var
     */
    public $date;

    /**
     * 交易价格
     * @var
     */
    public $price;

    /**
     * 交易数量
     * @var
     */
    public $amount;

    /**
     * 交易id
     * @var
     */
    public $tid;

}

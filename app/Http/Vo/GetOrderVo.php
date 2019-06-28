<?php
/**
 * Created by PhpStorm.
 * User: chw
 * Date: 2019-06-24
 * Time: 15:16
 */

namespace App\Http\Vo;


class GetOrderVo
{

    /**
     * 交易币种
     * @var
     */
    public $currency;

    /**
     * 委托单号
     * @var
     */
    public $id;

    /**
     * 单价
     * @var
     */
    public $price;

    /**
     * 挂单状态
     * @var
     */
    public $status;

    /**
     * 挂单总数量
     * @var
     */
    public $totalAmount;

    /**
     * 已成交数量
     * @var
     */
    public $tradeAmount;

    /**
     * 委托时间
     * @var
     */
    public $tradeDate;

    /**
     * 已成交总金额
     * @var
     */
    public $tradeMoney;

}

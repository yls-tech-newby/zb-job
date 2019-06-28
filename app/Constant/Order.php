<?php
/**
 * Created by PhpStorm.
 * User: chw
 * Date: 2019-06-25
 * Time: 14:48
 */

namespace App\Constant;


class Order
{

    /**
     * 待成交
     */
    public const WAITING = 0;

    /**
     * 取消
     */
    public const CANCEL = 1;

    /**
     * 交易完成
     */
    public const FINISHED = 2;

    /**
     * 待成交未交易部分
     */
    public const WAITING_APART = 3;

    /**
     * 购买
     */
    public const BUY = 1;

    /**
     * 出售
     */
    public const SELL = 0;
}

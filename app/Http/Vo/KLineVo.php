<?php
/**
 * Created by PhpStorm.
 * User: chw
 * Date: 2019-06-24
 * Time: 15:02
 */

namespace App\Http\Vo;


class KLineVo
{
    /**
     * 开
     * @var
     */
    public $start;

    /**
     * 高
     * @var
     */
    public $high;

    /**
     * 低
     * @var
     */
    public $low;

    /**
     * 收
     * @var
     */
    public $end;

    /**
     * 交易量
     * @var
     */
    public $tradeAmount;
}

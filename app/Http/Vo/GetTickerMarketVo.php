<?php
/**
 * Created by PhpStorm.
 * User: chw
 * Date: 2019-06-24
 * Time: 14:45
 */

namespace App\Http\Vo;


class GetTickerMarketVo
{

    /**
     * 最高价
     * @var
     */
    public $high;

    /**
     * 最低价
     * @var
     */
    public $low;

    /**
     * 买一价
     * @var
     */
    public $buy;

    /**
     * 卖一价
     * @var
     */
    public $sell;

    /**
     * 最新成交价
     * @var
     */
    public $last;

    /**
     * 成交量（最近的24小时）
     * @var
     */
    public $vol;

}

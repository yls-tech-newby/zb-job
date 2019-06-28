<?php
/**
 * Created by PhpStorm.
 * User: chw
 * Date: 2019-06-24
 * Time: 14:59
 */

namespace App\Http\Vo;


class GetKLineVo
{
    /**
     * k线内容
     * @var KLineVo[]
     */
    public $data;

    /**
     * 买入货币
     * @var
     */
    public $moneyType;

    /**
     * 卖出货币
     * @var
     */
    public $symbol;

}

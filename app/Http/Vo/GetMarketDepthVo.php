<?php
/**
 * Created by PhpStorm.
 * User: chw
 * Date: 2019-06-24
 * Time: 14:50
 */

namespace App\Http\Vo;


class GetMarketDepthVo
{

    /**
     * 卖方深度
     * @var DepthVo[]
     */
    public $asks;

    /**
     * 买方深度
     * @var DepthVo[]
     */
    public $bids;

    /**
     * 深度生成时间戳
     * @var
     */
    public $timestamp;

}

<?php
/**
 * Created by PhpStorm.
 * User: chw
 * Date: 2019-06-24
 * Time: 15:29
 */

namespace App\Http\Vo;


class GetUserCoinInfoVo
{

    /**
     * 币种英文名
     * @var
     */
    public $enName;

    /**
     * 是否冻结
     * @var
     */
    public $freez;

    /**
     * 币种排序
     * @var
     */
    public $fundstype;

    /**
     * 小数点后精确位数
     * @var
     */
    public $unitDecimal;

    /**
     * 展示名称
     * @var
     */
    public $showName;

    /**
     * 币种中文名
     * @var
     */
    public $cnName;

    /**
     * 是否可充值
     * @var
     */
    public $isCanRecharge;

    /**
     * 币种标签
     * @var
     */
    public $unitTag;

    /**
     * 是否可提现
     * @var
     */
    public $isCanWithdraw;

    /**
     * 可用币数
     * @var
     */
    public $available;

    /**
     * 是否可理财
     * @var
     */
    public $canLoan;

    /**
     * 币种键名
     * @var
     */
    public $key;
}

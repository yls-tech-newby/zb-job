<?php
/**
 * Created by PhpStorm.
 * User: chw
 * Date: 2019-06-24
 * Time: 15:14
 */

namespace App\Http\Vo;


class CancelOrderVo
{

    /**
     * 返回代码
     * @var
     */
    public $code;

    /**
     * 提示信息
     * @var
     */
    public $message;

    /**
     * 委托挂单号
     * @var
     */
    public $id;

}

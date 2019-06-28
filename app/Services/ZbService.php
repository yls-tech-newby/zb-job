<?php
/**
 * Created by PhpStorm.
 * User: chw
 * Date: 2019-06-24
 * Time: 15:24
 */

namespace App\Services;


use App\Business\ZbBusiness;
use App\Constant\MarketDepth;
use App\Constant\Order;

class ZbService
{
    public const MAX_COIN = 10;

    private $zbBusiness;

    /**
     * ZbService constructor.
     * @param $zbBusiness
     */
    public function __construct(ZbBusiness $zbBusiness)
    {
        $this->zbBusiness = $zbBusiness;
    }


    public function test()
    {
        $handleMsg = [];

        //检查余额
        $coins = $this->zbBusiness->getUserCoinInfo();
        $qc = $coins['qc']->available;
        $availableCoins = floor($coins['eos']->available * 100) / 100;

        //检查委托
        $unfinishedOrders = $this->zbBusiness->getUnfinishedOrders('eos');
        $unfinishedOrder = $unfinishedOrders[0] ?? null;

        //分析深度
        $depth = $this->getValuableDepth('eos', 5);

        $tickerMarket = $this->zbBusiness->getTickerMarket('eos');
        $coinsTotalValue = $tickerMarket->last * $availableCoins;

        $unfinishedOrderPrice = 0;
        if ($unfinishedOrder) {
            $unfinishedOrderPrice = $unfinishedOrder->totalAmount * $unfinishedOrder->price;
        }

        $handleMsg[] = '可用QC:' . $qc;
        $handleMsg[] = '待成交金额:' . $unfinishedOrderPrice;
        $handleMsg[] = '持币价值:' . $coinsTotalValue;
        $handleMsg[] = '资产总和:' . ($qc + $unfinishedOrderPrice + $coinsTotalValue);

        if ($availableCoins) {

            $handleMsg[] = 'EOS余币:' . $availableCoins;
            $handleMsg[] = 'EOS现存委托:' . json_encode($unfinishedOrder);

            if ($unfinishedOrder) {

                $result = $this->zbBusiness->cancelOrder($unfinishedOrder->id, $unfinishedOrder->currency);
                $handleMsg[] = '取消委托:' . json_encode($result);


                if ($depth['type'] === MarketDepth::SELL) {
                    //有币有单卖
                    $result = $this->zbBusiness->commitOrder('eos', Order::SELL, $depth['price'], $availableCoins);
                    $handleMsg[] = '提交委托:' . json_encode($result);

                } elseif ((self::MAX_COIN - $availableCoins) > 0) {
                    //有币有单买
                    $result = $this->zbBusiness->commitOrder('eos', Order::BUY, $depth['price'], self::MAX_COIN - $availableCoins);
                    $handleMsg[] = '提交委托:' . json_encode($result);
                }

            } else {

                if ($depth['type'] === MarketDepth::SELL) {
                    //有币无单卖
                    $result = $this->zbBusiness->commitOrder('eos', Order::SELL, $depth['price'], $availableCoins);
                    $handleMsg[] = '提交委托:' . json_encode($result);

                } elseif ((self::MAX_COIN - $availableCoins) > 0) {
                    //有币无单买
                    $result = $this->zbBusiness->commitOrder('eos', Order::BUY, $depth['price'], self::MAX_COIN - $availableCoins);
                    $handleMsg[] = '提交委托:' . json_encode($result);
                }

            }
        } else {
            $handleMsg[] = 'EOS余币:0';

            if ($unfinishedOrder) {
                $result = $this->zbBusiness->cancelOrder($unfinishedOrder->id, $unfinishedOrder->currency);
                $handleMsg[] = '取消委托:' . json_encode($result);

                if ($depth['type'] === MarketDepth::SELL) {
                    //无币有单卖
                    //闲置
                    $handleMsg[] = '闲置轮空';

                } elseif ((self::MAX_COIN - $availableCoins) > 0) {
                    //无币有单买
                    $result = $this->zbBusiness->commitOrder('eos', Order::BUY, $depth['price'], self::MAX_COIN);
                    $handleMsg[] = '提交委托:' . json_encode($result);
                }

            } else {

                if ($depth['type'] === MarketDepth::SELL) {
                    //无币无单卖
                    //闲置
                    $handleMsg[] = '闲置轮空';
                } elseif ((self::MAX_COIN - $availableCoins) > 0) {
                    //无币无单买
                    $result = $this->zbBusiness->commitOrder('eos', Order::BUY, $depth['price'], self::MAX_COIN);
                    $handleMsg[] = '提交委托:' . json_encode($result);
                }

            }
        }

        return $handleMsg;
    }


    /**
     * 取范围内最有价值深度
     * @param $currency
     * @param int $size
     * @return array
     */
    public function getValuableDepth($currency, $size = 3): array
    {
        $depth = $this->zbBusiness->getMarketDepth($currency, $size);
        $highestAmount = 0;
        $price = 0;
        $type = null;
        foreach ($depth->asks as $ask) {
            if ($ask->amount > $highestAmount) {
                $highestAmount = $ask->amount;
                $price = $ask->price;
                $type = MarketDepth::SELL;
            }
        }
        foreach ($depth->bids as $bid) {
            if ($bid->amount > $highestAmount) {
                $highestAmount = $bid->amount;
                $price = $bid->price;
                $type = MarketDepth::BUY;
            }
        }

        return [
            'price' => $price,
            'type' => $type
        ];
    }

}

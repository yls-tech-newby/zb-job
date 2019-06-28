<?php
/**
 * Created by PhpStorm.
 * User: chw
 * Date: 2019-06-24
 * Time: 10:40
 */

namespace App\Business;


use App\Http\Vo\CancelOrderVo;
use App\Http\Vo\CommitOrderVo;
use App\Http\Vo\DepthVo;
use App\Http\Vo\GetHistoryTradeVo;
use App\Http\Vo\GetKLineVo;
use App\Http\Vo\GetMarketDepthVo;
use App\Http\Vo\GetOrderVo;
use App\Http\Vo\GetTickerMarketVo;
use App\Http\Vo\GetUserCoinInfoVo;
use App\Http\Vo\KLineVo;
use App\Utils\CurrencyUtil;
use App\Utils\RequestUtil;
use App\Utils\TransferUtil;
use Illuminate\Support\Facades\Log;

class ZbBusiness
{

    /**
     * 获取币种行情
     * @param $currency string 币种
     * @return GetTickerMarketVo
     */
    public function getTickerMarket($currency = ''): GetTickerMarketVo
    {
        $currency = CurrencyUtil::injectRegion($currency);
        $response = RequestUtil::zbGet('http://api.zb.cn/data/v1/ticker?market=' . $currency);
        return TransferUtil::arrayBindEntity($response['ticker'], new GetTickerMarketVo());
    }


    /**
     * 获取币种市场深度
     * @param string $currency
     * @param int $size
     * @return GetMarketDepthVo
     */
    public function getMarketDepth($currency = '', $size = 3): GetMarketDepthVo
    {
        $currency = CurrencyUtil::injectRegion($currency);
        $response = RequestUtil::zbGet("http://api.zb.cn/data/v1/depth?market={$currency}&size={$size}");

        $asks = [];
        foreach ($response['asks'] as $ask) {
            $asks[] = TransferUtil::arrayBindEntity([
                'price' => $ask[0],
                'amount' => $ask[1]
            ], new DepthVo());
        }
        $response['asks'] = $asks;

        $bids = [];
        foreach ($response['bids'] as $bid) {
            $bids[] = TransferUtil::arrayBindEntity([
                'price' => $bid[0],
                'amount' => $bid[1]
            ], new DepthVo());
        }
        $response['bids'] = $bids;

        return TransferUtil::arrayBindEntity($response, new GetMarketDepthVo());
    }


    /**
     * 获取币种历史成交
     * @param string $currency
     * @param int $sinceId 从指定交易id后50条数据
     * @return GetHistoryTradeVo[]
     */
    public function getHistoryTrade($currency = '', $sinceId = 0): array
    {
        $currency = CurrencyUtil::injectRegion($currency);
        $response = RequestUtil::zbGet("http://api.zb.cn/data/v1/trades?market={$currency}&since={$sinceId}");
        $results = [];
        foreach ($response as $item) {
            $results[] = TransferUtil::arrayBindEntity($item, new GetHistoryTradeVo());
        }
        return $results;
    }


    /**
     * 获取指定币种的k线
     * @param $currency
     * @param $time
     * @param $sinceTime
     * @param $size
     * @return GetKLineVo
     */
    public function getKLine($currency, $time, $sinceTime, $size): GetKLineVo
    {
        $currency = CurrencyUtil::injectRegion($currency);
        $response = RequestUtil::zbGet("http://api.zb.cn/data/v1/kline?market={$currency}&type={$time}&since={$sinceTime}&size={$size}");
        $data = [];
        foreach ($response['data'] as $item) {
            $data[] = TransferUtil::arrayBindEntity($item, new KLineVo());
        }
        $response['data'] = $data;
        return TransferUtil::arrayBindEntity($response, new GetKLineVo());
    }


    /**
     * 委托下单
     * @param $currency
     * @param $tradeType
     * @param $price
     * @param $amount
     * @return CommitOrderVo
     */
    public function commitOrder($currency, $tradeType, $price, $amount): CommitOrderVo
    {
        $currency = CurrencyUtil::injectRegion($currency);
        $url = 'https://trade.zb.cn/api/order?' . RequestUtil::injectSign([
                'method' => 'order',
                'accesskey' => RequestUtil::ACCESS_KEY,
                'price' => $price,
                'amount' => $amount,
                'tradeType' => $tradeType,
                'currency' => $currency,
                'acctType' => 0
            ]);

        $response = RequestUtil::zbGet($url);
        return TransferUtil::arrayBindEntity($response, new CommitOrderVo());
    }


    /**
     * 取消委托
     * @param $id
     * @param $currency
     * @return CancelOrderVo
     */
    public function cancelOrder($id, $currency): CancelOrderVo
    {
        if(!strpos($currency,'_')){
            $currency = CurrencyUtil::injectRegion($currency);
        }
        $url = 'https://trade.zb.cn/api/cancelOrder?' . RequestUtil::injectSign([
                'method' => 'cancelOrder',
                'accesskey' => RequestUtil::ACCESS_KEY,
                'id' => $id,
                'currency' => $currency
            ]);

        $response = RequestUtil::zbGet($url);
        return TransferUtil::arrayBindEntity($response, new CancelOrderVo());
    }


    /**
     * 获取订单信息
     * @param $id
     * @param $currency
     * @return GetOrderVo
     */
    public function getOrder($id, $currency): GetOrderVo
    {
        $currency = CurrencyUtil::injectRegion($currency);
        $url = 'https://trade.zb.cn/api/getOrder?' . RequestUtil::injectSign([
                'method' => 'getOrder',
                'accesskey' => RequestUtil::ACCESS_KEY,
                'id' => $id,
                'currency' => $currency
            ]);

        $response = RequestUtil::zbGet($url);
        return TransferUtil::arrayBindEntity($response, new GetOrderVo());
    }


    /**
     * 批量获取委托单
     * @param $currency
     * @param $page
     * @param $size
     * @return GetOrderVo[]
     */
    public function getOrders($currency, $page = 1, $size = 20): array
    {
        $currency = CurrencyUtil::injectRegion($currency);
        $url = 'https://trade.zb.cn/api/getOrdersIgnoreTradeType?' . RequestUtil::injectSign([
                'method' => 'getOrdersIgnoreTradeType',
                'accesskey' => RequestUtil::ACCESS_KEY,
                'currency' => $currency,
                'pageIndex' => $page,
                'pageSize' => $size,
            ]);

        $response = RequestUtil::zbGet($url);
        $result = [];
        if (!isset($response['code'])) {
            foreach ($response as $item) {
                $result[] = TransferUtil::arrayBindEntity($item, new GetOrderVo());
            }
        }
        return $result;
    }


    /**
     * @param $currency
     * @param int $page
     * @param int $size
     * @return GetOrderVo[]
     */
    public function getUnfinishedOrders($currency, $page = 1, $size = 20): array
    {
        $currency = CurrencyUtil::injectRegion($currency);
        $url = 'https://trade.zb.cn/api/getUnfinishedOrdersIgnoreTradeType?' . RequestUtil::injectSign([
                'method' => 'getUnfinishedOrdersIgnoreTradeType',
                'accesskey' => RequestUtil::ACCESS_KEY,
                'currency' => $currency,
                'pageIndex' => $page,
                'pageSize' => $size,
            ]);

        $response = RequestUtil::zbGet($url);
        $result = [];
        if (!isset($response['code'])) {
            foreach ($response as $item) {
                $result[] = TransferUtil::arrayBindEntity($item, new GetOrderVo());
            }
        }
        return $result;
    }


    /**
     * 获取用户信息
     * @return GetUserCoinInfoVo[]
     */
    public function getUserCoinInfo(): array
    {
        $url = 'https://trade.zb.cn/api/getAccountInfo?' . RequestUtil::injectSign([
                'method' => 'getAccountInfo',
                'accesskey' => RequestUtil::ACCESS_KEY
            ]);
        $response = RequestUtil::zbGet($url);
        $result = [];
        foreach ($response['result']['coins'] as $coin) {
            $result[$coin['key']] = TransferUtil::arrayBindEntity($coin, new GetUserCoinInfoVo(), true);
        }
        return $result;
    }

}

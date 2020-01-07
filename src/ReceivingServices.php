<?php
/**
 * Created by PhpStorm.
 * User: dingran
 * Date: 2020/1/6
 * Time: 下午9:11
 */

namespace Kuaila;


class ReceivingServices
{
    private $areasUrl = 'https://openapi.chukou1.cn/v1/pickupAreas';
    private $orderUrl = 'https://openapi.chukou1.cn/v1/pickupOrders';
    private $header = [];
    private $tokenManager = null;

    public function __construct()
    {
        $this->tokenManager = new TokenManager();

        $this->header = [
            'Authorization: Bearer ' . $this->tokenManager->getAccessToken(),
            'Content-Type:'.'application/json; charset=UTF-8'
        ];
    }

    /**
     * 获取收货区域
     *
     * @param $city
     *
     * @return array|bool|mixed|string
     */
    public function getReceivingArea($city)
    {
        $url = $this->areasUrl . '?City=' . $city;

        $result = \curl_get($url, $this->header);

        return $result;
    }

    /**
     * 创建收货订单
     *
     * @param $data
     *
     * @return array|bool|mixed|string
     */
    public function createReceivingOrder($data)
    {
        $result = \curl_post($this->orderUrl, $data, $this->header);

        return $result;
    }
}
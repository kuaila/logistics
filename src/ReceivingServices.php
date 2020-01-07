<?php
/**
 * Created by PhpStorm.
 * User: dingran
 * Date: 2020/1/6
 * Time: 下午9:11
 */

namespace Kuailian;


use Kuaila\TokenManager;

class ReceivingServices
{
    private $url = 'https://openapi.chukou1.cn/v1/pickupAreas';
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

    public function getReceivingArea($city)
    {
        $url = $this->url . '?City=' . $city;

        $result = \curl_get($url, $this->header);

         return $result;
    }
}
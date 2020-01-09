<?php
/**
 * Created by PhpStorm.
 * User: dingran
 * Date: 2020/1/9
 * Time: 下午1:25
 */

namespace Kuaila;


class OrderSend
{
    /**
     * 批量创建中国直发订单
     */
    private $orderUrl = 'https://openapi.chukou1.cn/v1/directExpressOrders/multiple';

    /**
     * 获取中国直发包裹标签
     */
    private $tagUrl = 'https://openapi.chukou1.cn/v1/directExpressOrders/label';

    /**
     * 取消直发包裹
     */
    private $cancelPackageUrl = 'https://openapi.chukou1.cn/v1/directExpressOrders';

    private $header = [];
    private $tokenManager = null;

    public function __construct()
    {
        $this->init();
    }

    public function init()
    {
        $this->tokenManager = new TokenManager();

        try {
            $this->header = [
                'Authorization: Bearer ' . $this->tokenManager->getAccessToken(),
                'Content-Type:'.'application/json; charset=UTF-8'
            ];
        } catch (\Exception $e) {
            echo $e->getMessage();exit;
        }
    }

    /**
     * 批量创建中国直发订单
     *
     * @param $data array
     *
     * @return array|bool|mixed|string
     */
    public function sendOrder($data)
    {
        $res = \curl_post($this->orderUrl, $data);

        return $res;
    }

    /**
     * 获取中国直发包裹标签
     *
     * @param $data array
     *
     * @return array|bool|mixed|string
     */
    public function getPackageTag($data)
    {
        $res = \curl_post($this->tagUrl, $data);

        return $res;
    }

    /**
     * 取消直发包裹
     * @param $packageId
     * @param $idType
     *
     * @return None
     */
    public function cancelPackage($packageId, $idType)
    {
        $url = $this->cancelPackageUrl . '/' . $packageId . '/cancel?idType=' . $idType;

        $res = \curl_get($url, $this->header);

        return $res;
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: dingran
 * Date: 2020/1/4
 * Time: 下午2:37
 */

namespace Kuaila;


class TokenManager
{
    /**
     * 应用发起授权请求url
     *
     * @var string
     */
    private $codeUrl = 'https://openapi.chukou1.cn/oauth2/authorization';

    /**
     * 获取令牌url
     *
     * @var string
     */
    private $tokenUrl = 'https://openapi.chukou1.cn/oauth2/token';

    /**
     * token信息
     *
     * @var string
     */
    private $token = [];

    /**
     * 请求参数
     *
     * client_id 应用唯一标识
     * client_secret 应用密钥
     * redirect_uri 应用入口
     *
     * @var array
     */
    private $params = [];

    /**
     * 设置请求参数
     *
     * @param $params
     */
    public function setParams($params)
    {
        $this->params = $params;
    }

    public function getParam($key)
    {
        return $this->params[$key];
    }

    public function sendRequest()
    {
        $url = $this->codeUrl . '?client_id=' . $this->getParam('client_id') . '&response_type=code&scope=OpenApi&redirect_uri=' . $this->getParam('redirect_uri');

        header('Location:' . $url);
    }

    public function generateToken($code)
    {
        $data = [
            'client_id' => $this->getParam('client_id'),
            'client_secret' => $this->getParam('client_secret'),
            'redirect_uri' => $this->getParam('redirect_uri'),
            'grant_type' => 'authorization_code',
            'code' => $code
        ];

        $url = $this->tokenUrl . '?client_id=' . $this->getParam('client_id') . '&client_secret=' . $this->getParam('client_secret')
            . '&redirect_uri=' . $this->getParam('redirect_uri') . '&grant_type=authorization_code&code=' . $code;

        $token = \curl_post($url, $data);
echo '<pre>';print_r($token);
        if (isset($token['status']) && 0 === $token['status']) {
            return $token['msg'];
        }

        return $this->save($token);
    }

    public function save($token)
    {
        return file_put_contents(dirname(__DIR__) . '/' . 'token.log', serialize($token));
    }

    public function getToken()
    {
        $this->token = unserialize(file_get_contents(dirname(__DIR__) . '/' . 'token.log'));
    }

    public function getAccessToken()
    {
        if ($this->checkToken()) {
            return $this->token['AccessToken'];
        }

        return '';
    }

    public function checkToken()
    {
        if (!empty($this->token) && $this->token['AccessTokenExpiresIn'] > time()) {
            return true;
        }

        return false;
    }

    public function refreshToken()
    {}

    public function checkRefreshToken()
    {}

}
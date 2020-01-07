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

    private $tokenlog = '';

    public function __construct()
    {
        $this->tokenlog = dirname(__DIR__) . '/' . 'token.log';
    }

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

        if (isset($token['status']) && 0 === $token['status']) {
            return $token['msg'];
        }

        return $this->save(json_decode($token, true));
    }

    public function save($token)
    {
        return file_put_contents($this->tokenlog, serialize($token));
    }

    public function getToken()
    {
        $this->token = unserialize(file_get_contents($this->tokenlog));
    }

    public function getAccessToken()
    {
        $this->getToken();

        if (!$this->checkToken()) {
            $this->refreshToken();
        }

        return $this->token['AccessToken'];
    }

    public function checkToken()
    {
        if (file_exists($this->tokenlog)) {
            if (!empty($this->token) && (filemtime($this->tokenlog) + $this->token['AccessTokenExpiresIn']) > time()) {
                return true;
            }
        }

        return false;
    }

    public function refreshToken()
    {
        if ($this->token['RefreshTokenExpiresIn'] > time()) {
            //TODO refresh
        } else {
            $this->sendRequest();
        }
    }

    public function checkRefreshToken()
    {}

}
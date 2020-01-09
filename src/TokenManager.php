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

    /**
     * 获取请求参数
     *
     * @param $key
     *
     * @return mixed
     */
    public function getParam($key)
    {
        return $this->params[$key];
    }

    /**
     * 发起授权请求
     */
    public function sendRequest()
    {
        $url = $this->codeUrl . '?client_id=' . $this->getParam('client_id') . '&response_type=code&scope=OpenApi&redirect_uri=' . $this->getParam('redirect_uri');

        header('Location:' . $url);
    }

    /**
     * 生成token
     *
     * @param $code
     *
     * @return bool|int
     */
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

        if ($this->save(json_decode($token, true))) {
            echo 'token生成成功 重新发起业务请求';
        } else {
            echo 'token生成失败';
        }
    }

    /**
     * 保存token
     *
     * @param $token
     *
     * @return bool|int
     */
    public function save($token)
    {
        return file_put_contents($this->tokenlog, serialize($token));
    }

    /**
     * 获取token
     */
    public function getToken()
    {
        $this->token = unserialize(file_get_contents($this->tokenlog));
    }

    /**
     * 获取访问令牌
     *
     * @return mixed
     */
    public function getAccessToken()
    {
        $this->getToken();

        if (!$this->checkToken()) {
            $res = $this->refreshToken();

            if ($res['status'] != 1) {
                throw new \Exception($res['msg']);
            }
        }

        return $this->token['AccessToken'];
    }

    /**
     * 检查访问令牌是否有效
     *
     * @return bool
     */
    public function checkToken()
    {
        if (file_exists($this->tokenlog)) {
            if (!empty($this->token) && (filemtime($this->tokenlog) + $this->token['AccessTokenExpiresIn']) > time()) {
                return true;
            }
        }

        return false;
    }

    /**
     * 刷新token
     */
    public function refreshToken()
    {
        if ((filemtime($this->tokenlog) + $this->token['RefreshTokenExpiresIn']) > time()) {
            $data = [
                'client_id' => $this->getParam('client_id'),
                'client_secret' => $this->getParam('client_secret'),
                'redirect_uri' => $this->getParam('redirect_uri'),
                'grant_type' => 'refresh_token',
                'refresh_token' => $this->token['RefreshToken']
            ];

            $url = $this->tokenUrl . '?client_id=' . $this->getParam('client_id') . '&client_secret=' . $this->getParam('client_secret')
                . '&redirect_uri=' . $this->getParam('redirect_uri') . '&grant_type=refresh_token&refresh_token=' . $token['RefreshToken	'];

            $result = \curl_post($url, $data);

            $this->save(json_decode($result));

            $this->token = $result;

            return ['status' => 1];
        } else {
            return ['status' => 0, 'msg' => 'token不存在, 应用重新授权'];
        }
    }
}
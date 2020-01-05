<?php
/**
 * Created by PhpStorm.
 * User: dingran
 * Date: 2020/1/4
 * Time: 下午2:37
 */

namespace Kuaila\TokenManager;


class TokenManager
{
    private $codeUrl = 'https://openapi-release.chukou1.cn/oauth2/authorization';
    private $tokenUrl = 'https://openapi-release.chukou1.cn/oauth2/token';
    private $refreshUrl = 'https://openapi-release.chukou1.cn';

    private $token = '';

    public function sendRequest()
    {}

    public function createToken()
    {}

    public function saveToken($token)
    {
        file_put_contents('', serialize($token));
    }

    public function getToken()
    {
        $this->token = unserialize(file_get_contents(''));
    }

    public function checkAccountToken()
    {}

    public function refreshToken()
    {}

    public function checkRefreshToken()
    {}
}
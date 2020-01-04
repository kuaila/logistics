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
    private $codeUrl = '';
    private $tokenUrl = '';
    private $refreshUrl = '';

    public function getCodeUrl()
    {
        $this->codeUrl = $config['codeUrl'];
    }

    public function getTokenUrl()
    {
        $this->tokenUrl = $config['tokenUrl'];
    }

    public function getRefreshUrl()
    {
         $this->refreshUrl = $config['refreshUrl'];
    }
}
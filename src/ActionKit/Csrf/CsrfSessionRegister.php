<?php
namespace ActionKit\Csrf;
use ActionKit\CsrfToken;

class CsrfSessionRegister
{
    protected $sessionKey;

    public function __construct($sessionKey)
    {
        $this->sessionKey = $sessionKey;
    }

    public function register(CsrfToken $token)
    {
        $_SESSION[$this->sessionKey] = serialize($token);
    }

    public function deregister()
    {
        unset($_SESSION[$this->sessionKey]);
    }
}





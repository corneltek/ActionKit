<?php
namespace ActionKit\Csrf;
use ActionKit\Csrf\CsrfToken;
use ActionKit\Csrf\CsrfStorage;


/**
 * CsrfSessionStorage store the csrf token into session
 */
class CsrfSessionStorage implements CsrfStorage
{
    protected $sessionKey;

    public function __construct($sessionKey)
    {
        $this->sessionKey = $sessionKey;
    }

    public function getSessionKey()
    {
        return $this->sessionKey;
    }

    public function store(CsrfToken $token, $key = null)
    {
        $_SESSION[$key ?: $this->sessionKey] = serialize($token);
    }

    public function exists($key = null)
    {
        return isset($_SESSION[$key ?: $this->sessionKey]);
    }

    /**
     * Load a CSRF token from session by specific session key
     *
     * @param string $key
     * @return CsrfToken
     */
    public function load($key = null)
    {
        if (isset($_SESSION[$key ?: $this->sessionKey])) {
            return unserialize($_SESSION[$key ?: $this->sessionKey]);
        }
    }

    public function drop($key = null)
    {
        unset($_SESSION[$key ?: $this->sessionKey]);
    }
}


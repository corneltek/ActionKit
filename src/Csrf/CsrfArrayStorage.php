<?php
namespace ActionKit\Csrf;

use ActionKit\Csrf\CsrfToken;
use ActionKit\Csrf\CsrfStorage;

/**
 * CsrfArrayStorage store the csrf token into session
 */
class CsrfArrayStorage implements CsrfStorage
{
    protected $tokenKey;

    protected $array = [];

    public function __construct($key)
    {
        $this->tokenKey = $key;
        $this->array = array();
    }

    public function getTokenKey()
    {
        return $this->key;
    }

    public function store(CsrfToken $token, $key = null)
    {
        $this->array[$key ?: $this->tokenKey] = serialize($token);
    }

    public function exists($key = null)
    {
        return isset($this->array[$key ?: $this->tokenKey]);
    }

    /**
     * Load a CSRF token from session by specific session key
     *
     * @param string $key
     * @return CsrfToken
     */
    public function load($key = null)
    {
        if (isset($this->array[$key ?: $this->tokenKey])) {
            return unserialize($this->array[$key ?: $this->tokenKey]);
        }
    }

    public function drop($key = null)
    {
        unset($this->array[$key ?: $this->tokenKey]);
    }
}

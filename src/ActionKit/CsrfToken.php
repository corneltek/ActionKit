<?php
namespace ActionKit;
use Exception;

class CsrfToken {
    
    public $sessionKey;

    /**
     * @var integer time to live of the token. using seconds.
     */
    public $ttl;

    /**
     * @var integer
     */
    public $timestamp;


    /**
     * @var string
     */
    public $salt;

    /**
     * @var string
     */
    public $sessionId;

    /**
     * @var string
     */
    public $ip;

    /**
     * @var string
     */
    public $hash;

    public function __construct($sessionKey, $ttl)
    {
        $this->ttl = $ttl;
        $this->sessionKey = $sessionKey;
    }

    public function checkExpiry($timestamp)
    {
        return ($timestamp - $this->timestamp) < $this->ttl;
    }
}

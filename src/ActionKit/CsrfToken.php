<?php
namespace ActionKit;
use Exception;

class CsrfToken {
    
    public $sessionKey;

    /**
     * @var int time to live of the token. using seconds.
     */
    public $ttl;
    public $timestamp;
    public $salt;
    public $sessionId;
    public $ip;
    public $hash;

    public function __construct($sessionKey, $ttl){
        $this->ttl = $ttl;
        $this->sessionKey = $sessionKey;
    }

    public function checkExpiry($timestamp) {
        return ($timestamp - $this->timestamp) < $this->ttl;
    }
}

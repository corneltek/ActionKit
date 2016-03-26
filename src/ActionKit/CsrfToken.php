<?php
namespace ActionKit;
use Exception;

class CsrfToken
{
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

    public function toPublicArray()
    {
        return array(
            'hash'      => $this->hash,
            'ttl'       => $this->ttl,
            'timestamp' => $this->timestamp,
            'created_at' => date('c', $this->timestamp),
            'expired_at' => date('c', $this->timestamp + $this->ttl),
        );
    }


}

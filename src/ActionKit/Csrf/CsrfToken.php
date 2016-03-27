<?php
namespace ActionKit\Csrf;
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
    public $hash;

    /**
     * @var array $extra information
     */
    public $extra = [];

    public function __construct($sessionKey, $ttl = 0, array $extra = array())
    {
        $this->ttl = $ttl;
        $this->timestamp = time(); // created_at
        $this->sessionKey = $sessionKey;
        $this->salt = $this->randomString(32);
        $this->extra = $extra;
        $this->hash = $this->generateChecksum();
    }

    public function isExpired($now)
    {
        if ($this->ttl != 0) {
            return ($now - $this->timestamp) > $this->ttl;
        }
        return false;
    }

    public function toPublicArray()
    {
        return [
            'hash'      => $this->hash,
            'ttl'       => $this->ttl,
            'timestamp' => $this->timestamp,
            'created_at' => date('c', $this->timestamp),
            'expired_at' => date('c', $this->timestamp + $this->ttl),
        ];
    }

    protected function randomString($len = 10)
    {
        $rString = '';
        $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789';
        $charsTotal  = strlen($chars);
        for ($i = 0; $i < $len; $i++) {
            $rInt = (integer) mt_rand(0, $charsTotal);
            $rString .= substr($chars, $rInt, 1);
        }
        return $rString;
    }


    /**
     * generateChecksum generates sha1 checksum and stored in base64 format string
     *
     * @return string
     */
    protected function generateChecksum()
    {
        return base64_encode(sha1(serialize($this)));
    }
}

<?php
namespace ActionKit;
use ActionKit\CsrfToken;
use Exception;

class CsrfTokenProvider
{
    protected $sessionKey;

    public function __construct($sessionKey = '_csrf_token')
    {
        $this->sessionKey = $sessionKey;
    }

    /**
     * Generate a CSRF token and save the token into the session with the
     * current session key.
     *
     * @param integer $ttl time to live
     */
    public function generateToken($sessionKey = null, $ttl = 60 * 20)
    {
        $token = new CsrfToken($sessionKey ?: $this->sessionKey, $ttl);
        $token->timestamp = time();
        $token->salt = $this->randomString(32);
        $token->sessionId = session_id();
        $token->ip = $_SERVER['REMOTE_ADDR'];
        $_SESSION[$token->sessionKey] = serialize($token);
        $token->hash = $this->encodeToken($token);
        return $token;
    }

    public function verifyToken(CsrfToken $token, $tokenHash) {
        if ($token != null) {
            if (!$token->checkExpiry($_SERVER['REQUEST_TIME'])) {
                return false;
            }
            $tokenHash = base64_decode($tokenHash);
            $generatedHash = $this->calculateHash($token);
            if ($tokenHash and $generatedHash) {
                return $tokenHash == $generatedHash;
            }
        }
        return false;
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
    

    protected function calculateHash(CsrfToken $token)
    {
        return sha1($_SESSION[$token->sessionKey]);
    }

    public function loadToken($withHash = false)
    {
        return $this->loadTokenWithSessionKey($this->sessionKey, $withHash);
    }

    /**
     * Load a CSRF token from session by specific session key
     *
     * @param string $sessionKey
     * @param boolean $withHash
     */
    public function loadTokenWithSessionKey($sessionKey, $withHash = false)
    {
        if (isset($_SESSION[$sessionKey])) {
            $token = unserialize($_SESSION[$sessionKey]);
            if ($withHash) {
                $token->hash = $this->encodeToken($token);
            }
            return $token;
        } 
        return null;
    }

    protected function encodeToken(CsrfToken $token)
    {
        $hash = $this->calculateHash($token);
        return base64_encode($hash);
    }
}

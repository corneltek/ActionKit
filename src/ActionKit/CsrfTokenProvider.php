<?php
namespace ActionKit;
use ActionKit\CsrfToken;
use Exception;

class CsrfTokenProvider
{
    protected $sessionKey;

    protected $ttl = 1800;

    public function __construct($sessionKey = '__csrf_token')
    {
        $this->sessionKey = $sessionKey;
    }

    public function setTtl($seconds)
    {
        $this->ttl = $seconds;
    }

    public function dropToken($sessionKey)
    {
        unset($_SESSION[$sessionKey]);
    }

    /**
     * Generate a CSRF token and save the token into the session with the
     * current session key.
     *
     * @param integer $ttl time to live seconds
     *
     * @return CsrfToken
     */
    public function generateToken($sessionKey = null, $ttl = null)
    {
        $ttl = $ttl ?: $this->ttl; // fallback to default ttl
        $sessionKey = $sessionKey ?: $this->sessionKey;
        $token = new CsrfToken($sessionKey, $ttl, [
            'session_id'  => session_id(),
            'remote_addr' => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0',
        ]);

        // Store the token object in session
        $_SESSION[$this->sessionKey] = serialize($token);

        // Reeturn the Csrf token
        return $token;
    }

    /**
     * Verify incoming csrf token
     */
    public function verifyToken(CsrfToken $token, $insecureTokenHash, $requestTime) {
        if ($token != null) {
            if ($token->isExpired($requestTime)) {
                return false;
            }
            $generatedHash = $token->hash;
            if ($insecureTokenHash !== null && $generatedHash !== null) {
                return $insecureTokenHash === $generatedHash;
            }
        }
        return false;
    }

    public function loadToken()
    {
        return $this->loadTokenWithSessionKey($this->sessionKey);
    }

    /**
     * Load a CSRF token from session by specific session key
     *
     * @param string $sessionKey
     * @param boolean $withHash
     *
     * @return CsrfToken
     */
    public function loadTokenWithSessionKey($sessionKey)
    {
        if (isset($_SESSION[$sessionKey])) {
            // unserialized the token from session
            return unserialize($_SESSION[$sessionKey]);
        } 
        return null;
    }

}

<?php
namespace ActionKit\Csrf;

use ActionKit\Csrf\CsrfToken;
use ActionKit\Csrf\CsrfStorage;
use Exception;

class CsrfTokenProvider
{
    protected $ttl = 0;

    protected $storage;

    public function __construct(CsrfStorage $storage)
    {
        $this->storage = $storage;
    }

    public function setTtl($seconds)
    {
        $this->ttl = $seconds;
    }

    /**
     * Generate a CSRF token and save the token into the session with the
     * current session key.
     *
     * @param integer $ttl time to live seconds
     *
     * @return CsrfToken
     */
    public function generateToken()
    {
        $token = new CsrfToken(session_id(), $this->ttl, [
            'session_id'  => session_id(),
            'remote_addr' => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0',
        ]);
        return $token;
    }

    public function loadCurrentToken($refresh = false)
    {
        if ($refresh) {
            $token = $this->generateToken();
            $this->storage->store($token);
        }
        if ($token = $this->storage->load()) {
            return $token;
        }
        $token = $this->generateToken();
        $this->storage->store($token);
        return $token;
    }

    public function getStorage()
    {
        return $this->storage;
    }

    /**
     * Verify incoming csrf token
     *
     * @param string $insecureTokenHash
     * @param integer $requestTime
     * @return boolean
     */
    public function isValidToken($insecureTokenHash, $requestTime)
    {
        // Load the token object from the storage (session storage)
        $token = $this->storage->load();
        if ($token) {
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
}

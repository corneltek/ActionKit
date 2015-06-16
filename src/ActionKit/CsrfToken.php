<?php
namespace ActionKit;
use Exception;

class CsrfToken {
    
    protected $timeout = 300;
    protected $tokenSessionId = '_csrf_token';

    public function __construct($timeout=300, $tokenSessionId = '_csrf_token'){
        $this->timeout = $timeout;
        $this->tokenSessionId = $tokenSessionId;
    }

    protected function checkTimeout($timeout=NULL) {
        if (!$timeout) {
            $timeout = $this->timeout;
        }
        return ($_SERVER['REQUEST_TIME'] - $_SESSION[$this->tokenSessionId]['time']) < $timeout;
    }

    protected function calculateHash() {
        return sha1(implode('', $_SESSION[$this->tokenSessionId]));
    }

    public function checkToken($hashCsrfToken, $timeout=NULL) {

        if (isset($_SESSION[$this->tokenSessionId])) {
            if (!$this->checkTimeout($timeout)) {
                return FALSE;
            }
            $tokenHash = base64_decode($hashCsrfToken);
            $generatedHash = $this->calculateHash();
            if ($tokenHash and $generatedHash) {
                return $tokenHash == $generatedHash;
            }
        }
        return FALSE;
    }
}

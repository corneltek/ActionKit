<?php
namespace ActionKit;
use Exception;

class CsrfTokenProvider {
    
    protected $timeout = 300;
    protected $tokenSessionId = '_csrf_token';

    public function __construct($timeout=300, $tokenSessionId = '_csrf_token'){
        $this->timeout = $timeout;
        $this->tokenSessionId = $tokenSessionId;
    }

    public function randomString($len = 10) {
        // Characters that may look like other characters in different fonts
        // have been omitted.
        $rString = '';
        $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789';
        $charsTotal  = strlen($chars);
        for ($i = 0; $i < $len; $i++) {
            $rInt = (integer) mt_rand(0, $charsTotal);
            $rString .= substr($chars, $rInt, 1);
        }
        return $rString;
    }
    
    protected function calculateHash() {
        return sha1(implode('', $_SESSION[$this->tokenSessionId]));
    }

    public function generateToken() {
        $_SESSION[$this->tokenSessionId] = array();
        $_SESSION[$this->tokenSessionId]['time'] = time();
        $_SESSION[$this->tokenSessionId]['salt'] = $this->randomString(32);
        $_SESSION[$this->tokenSessionId]['sessid'] = session_id();
        $_SESSION[$this->tokenSessionId]['ip'] = $_SERVER['REMOTE_ADDR'];
        $hash = $this->calculateHash();
        return base64_encode($hash);
    }
}

<?php
namespace ActionKit;
use ActionKit\CsrfToken;
use Exception;

class CsrfTokenProvider {

    public $tokenSessionId = '_csrf_token';
    public $timeout = 300;

    public function __construct($tokenSessionId = '_csrf_token', $timeout=300){
        $this->tokenSessionId = $tokenSessionId;
        $this->timeout = $timeout;
    }

    public function generateToken() {
        $token = new CsrfToken($this->tokenSessionId, $this->timeout);
        $token->time = time();
        $token->salt = $this->randomString(32);
        $token->sessid = session_id();
        $token->ip = $_SERVER['REMOTE_ADDR'];

        $_SESSION[$token->tokenSessionId] = serialize($token);

        $hash = $this->calculateHash($token);
        return base64_encode($hash);
    }

    public function checkToken($hashCsrfToken) {
        $token = $this->getToken();
        if ($token != null) {
            if (!$token->checkExpiry($_SERVER['REQUEST_TIME'])) {
                return false;
            }
            $tokenHash = base64_decode($hashCsrfToken);
            $generatedHash = $this->calculateHash($token);
            if ($tokenHash and $generatedHash) {
                return $tokenHash == $generatedHash;
            }
        }
        return false;
    }

    protected function randomString($len = 10) {
        $rString = '';
        $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789';
        $charsTotal  = strlen($chars);
        for ($i = 0; $i < $len; $i++) {
            $rInt = (integer) mt_rand(0, $charsTotal);
            $rString .= substr($chars, $rInt, 1);
        }
        return $rString;
    }
    
    protected function calculateHash($token) {
        return sha1($_SESSION[$token->tokenSessionId]);
    }
    
    protected function getToken() {
        if (isset($_SESSION[$this->tokenSessionId])) {
            return unserialize($_SESSION[$this->tokenSessionId]);
        } 
        return null;
    }
}

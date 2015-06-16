<?php
namespace ActionKit;
use Exception;

class CsrfToken {
    
    protected $timeout = 300;

    public function __construct($timeout=300){
        $this->timeout = $timeout;
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
        return sha1(implode('', $_SESSION['csrf']));
    }

    public function generateToken() {
        $_SESSION['csrf'] = array();
        $_SESSION['csrf']['time'] = time();
        $_SESSION['csrf']['salt'] = $this->randomString(32);
        $_SESSION['csrf']['sessid'] = session_id();
        $_SESSION['csrf']['ip'] = $_SERVER['REMOTE_ADDR'];
        $hash = $this->calculateHash();
        return base64_encode($hash);
    }

    protected function checkTimeout($timeout=NULL) {
        if (!$timeout) {
            $timeout = $this->timeout;
        }
        return ($_SERVER['REQUEST_TIME'] - $_SESSION['csrf']['time']) < $timeout;
    }

    public function checkToken($hashCsrfToken, $timeout=NULL) {
        if (isset($_SESSION['csrf'])) {
            
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

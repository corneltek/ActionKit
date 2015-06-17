<?php
namespace ActionKit;
use ActionKit\CsrfToken;
use Exception;

class CsrfTokenProvider {

    static public function generateToken($tokenSessionId = '_csrf_token', $timeout = 300) {
        $token = new CsrfToken($tokenSessionId, $timeout);
        $token->time = time();
        $token->salt = CsrfTokenProvider::randomString(32);
        $token->sessid = session_id();
        $token->ip = $_SERVER['REMOTE_ADDR'];

        $_SESSION[$token->tokenSessionId] = serialize($token);

        $hash = CsrfTokenProvider::calculateHash($token);
        return base64_encode($hash);
    }

    static public function checkToken($hashCsrfToken) {
        $token = CsrfTokenProvider::loadTokenWithSessionKey();
        if ($token != null) {
            if (!$token->checkExpiry($_SERVER['REQUEST_TIME'])) {
                return false;
            }
            $tokenHash = base64_decode($hashCsrfToken);
            $generatedHash = CsrfTokenProvider::calculateHash($token);
            if ($tokenHash and $generatedHash) {
                return $tokenHash == $generatedHash;
            }
        }
        return false;
    }

    static protected function randomString($len = 10) {
        $rString = '';
        $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789';
        $charsTotal  = strlen($chars);
        for ($i = 0; $i < $len; $i++) {
            $rInt = (integer) mt_rand(0, $charsTotal);
            $rString .= substr($chars, $rInt, 1);
        }
        return $rString;
    }
    
    static protected function calculateHash(CsrfToken $token) {
        return sha1($_SESSION[$token->tokenSessionId]);
    }

    static protected function loadTokenWithSessionKey($key = '_csrf_token') {
        if (isset($_SESSION[$key])) {
            return unserialize($_SESSION[$key]);
        } 
        return null;
    }
}

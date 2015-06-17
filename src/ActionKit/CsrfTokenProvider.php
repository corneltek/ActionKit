<?php
namespace ActionKit;
use ActionKit\CsrfToken;
use Exception;

class CsrfTokenProvider {

    static public function generateToken($sessionKey = '_csrf_token', $timeout = 300) {
        $token = new CsrfToken($sessionKey, $timeout);
        $token->time = time();
        $token->salt = self::randomString(32);
        $token->sessionId = session_id();
        $token->ip = $_SERVER['REMOTE_ADDR'];

        $_SESSION[$token->sessionKey] = serialize($token);

        $token->hash = self::encodeToken($token);
        return $token;
    }

    static public function verifyToken(CsrfToken $token, $tokenHash) {
        if ($token != null) {
            if (!$token->checkExpiry($_SERVER['REQUEST_TIME'])) {
                return false;
            }
            $tokenHash = base64_decode($tokenHash);
            $generatedHash = self::calculateHash($token);
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
        return sha1($_SESSION[$token->sessionKey]);
    }

    static public function loadTokenWithSessionKey($key = '_csrf_token', $withHash = false) {
        if (isset($_SESSION[$key])) {
            $token = unserialize($_SESSION[$key]);
            if ( $withHash) {
                $token->hash = self::encodeToken($token);
            }
            return $token;
        } 
        return null;
    }

    static protected function encodeToken(CsrfToken $token) {
        $hash = self::calculateHash($token);
        return base64_encode($hash);
    }
}

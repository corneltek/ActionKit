<?php
namespace ActionKit;
use Exception;

class CsrfToken {
    
    public $tokenSessionId;
    public $timeout;
    public $time;
    public $salt;
    public $sessid;
    public $ip;

    public function __construct($tokenSessionId, $timeout){
        $this->timeout = $timeout;
        $this->tokenSessionId = $tokenSessionId;
    }

    public function checkExpiry($time) {
        return ($time - $this->time) < $this->timeout;
    }
}

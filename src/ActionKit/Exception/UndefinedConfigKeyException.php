<?php
namespace ActionKit\Exception;
use LogicException;

class UndefinedConfigKeyException extends LogicException {

    public $key;

    public function __construct($key) {
        $this->key = $key;
        parent::__construct("Config Key '$key' is undefined.");
    }
    
}


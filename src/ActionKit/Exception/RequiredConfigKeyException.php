<?php
namespace ActionKit\Exception;
use LogicException;

class RequiredConfigKeyException extends LogicException {

    protected $key;

    protected $desc;

    /**
     *
     * @param string $key
     * @param string $desc key description
     */
    public function __construct($key, $desc = null) {
        $this->key = $key;
        $this->desc = $desc;
        parent::__construct("Config '$key' is required.");
    }
    
}


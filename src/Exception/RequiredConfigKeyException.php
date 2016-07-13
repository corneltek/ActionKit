<?php
namespace ActionKit\Exception;
use LogicException;

class RequiredConfigKeyException extends LogicException {

    protected $configKey;

    protected $configDesc;

    /**
     *
     * @param string $key
     * @param string $desc key description
     */
    public function __construct($key, $desc = null) {
        $this->configKey = $key;
        $this->configDesc = $desc;
        parent::__construct("Config '$key' is required. [$desc]");
    }

    public function getConfigKey() {
        return $this->configKey;
    }

    public function getConfigDesc() {
        return $this->configDesc;
    }
    
}


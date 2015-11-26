<?php
namespace ActionKit;
use ArrayAccess;
use IteratorAggregate;
use ArrayIterator;
use Exception;

/**
 * A non-gettext dependent class for translating messages
 */
class MessagePool implements ArrayAccess, IteratorAggregate
{
    protected $messages = [ ];

    /**
     * The constructor will detect if gettext extension is loaded.
     */
    public function __construct($locale)
    {
        $this->gettextEnabled = extension_loaded('gettext');
        $this->loadByLocale($locale);
    }

    /**
     * Load messages by locale name
     *
     * @param string $locale
     * @return boolean
     */
    public function loadByLocale($locale)
    {
        $localeFile = __DIR__ . DIRECTORY_SEPARATOR . 'Messages' . DIRECTORY_SEPARATOR . $locale . '.php';
        return $this->loadMessagesFromFile($localeFile);
    }

    public function loadMessagesFromFile($localeFile)
    {
        if (!file_exists($localeFile)) {
            throw new Exception("Can't load translation entries from '$localeFile'");
        }
        if ($messages = require $localeFile) {
            $this->messages = $messages;
            return true;
        }
        return false;
    }

    public function format($msg, array $args)
    {
        $placeholders = array_map(function($a){ 
            if (is_numeric($a)) {
                return '%' . ($a+1); // start from %1
            } else {
                return '%' . $a;
            }
        }, array_keys($args));
        return str_replace($placeholders, $args, $msg);
    }

    /**
     * translate method translate a msgId into the localized message
     */
    public function translate($msgId)
    {
        $args = func_get_args();
        array_shift($args);

        if (!isset($this->messages[$msgId])) {
            throw new Exception("Message ID '$msgId' undefined.");
        }

        $msg = $this->messages[$msgId];
        return $this->format($msg, $args);
    }

    public function loadMessages(array $messages)
    {
        $this->messages = array_merge($this->messages, $messages);
    }

    public function setMessages(array $messages)
    {
        $this->messages = $messages;
    }

    public function get($msgId)
    {
        if ($this->gettextEnabled && $msgId != _($msgId)) {
            return _($msgId);
        }
        if (isset($this->messages[$msgId]) ) {
            return $this->messages[$msgId];
        }
        // pass to gettext to translate
        // throw new Exception("MessageId $msgId is not defined.");
        return $msgId;
    }

    static public function getInstance($locale = 'en')
    {
        static $instance;
        if ($instance) {
            return $instance;
        }
        return $instance = new self($locale);
    }
    
    public function offsetSet($name,$value)
    {
        $this->messages[ $name ] = $value;
    }
    
    public function offsetExists($name)
    {
        return isset($this->messages[ $name ]);
    }
    
    public function offsetGet($name)
    {
        return $this->messages[ $name ];
    }
    
    public function offsetUnset($name)
    {
        unset($this->messages[$name]);
    }

    public function getIterator()
    {
        return new ArrayIterator($this->messages);
    }
    
}


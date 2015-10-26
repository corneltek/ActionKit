<?php
namespace ActionKit;
use Exception;

/**
 * @codeCoverageIgnore
 */
class MessagePool
{
    protected $messages = [
        'file.required'  => 'File Field %1 is required.',
        'param.required' => 'Field %1 is required.',
        'validation.error' => 'Please check your input.',
    ];

    public function __construct()
    {
        $this->gettextEnabled = extension_loaded('gettext');
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
            throw new Exception("Message ID $msgId undefined.");
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

    static public function getInstance()
    {
        static $instance;
        if ($instance) {
            return $instance;
        }
        return $instance = new self;
    }
}

/**
 * This is used for gettext message parser
 * @codeCoverageIgnore

_('file.required');
_('param.required');
_('validation.error');
_('Validation Error');
_('Field %1 is required.');
_('File Field %1 is required.');

 */



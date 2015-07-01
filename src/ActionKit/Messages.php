<?php
namespace ActionKit;
use Exception;

/**
 * @codeCoverageIgnore
 */
class Messages
{
    static $messages = array(
        'file.required'  => 'File Field %1 is required.',
        'param.required' => 'Field %1 is required.',
        'validation.error' => 'Please check your input.',
    );

    static public function set($msgId, $msgStr) {
        self::$messages[ $msgId ] = $msgStr;
    }

    static public function get($msgId) {
        if ( $msgId != _($msgId) ) {
            return _($msgId);
        }

        if ( isset(self::$messages[$msgId]) ) {
            return self::$messages[$msgId];
        }
        // pass to gettext to translate
        // throw new Exception("MessageId $msgId is not defined.");
        return $msgId;
    }
}

function ___messages() {  
    _('file.required');
    _('param.required');
    _('validation.error');
    _('Validation Error');
    _('Field %1 is required.');
    _('File Field %1 is required.');
}





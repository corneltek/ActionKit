<?php
namespace ActionKit;

use Phifty\Email;
use ActionKit\Action;

/**
 * @codeCoverageIgnore

    class ConfirmEmailAction
    {

        var $to = '';
        var $cc = '';

        var $message = 'Hello World';
        var $template = 'confirm_email_{lang}.tpl'; // i18n

        function getContent()
        {
            return ..... mail content ..... or $this->message;
        }

        function run()
        {
            $to = $this->arg( 'to' );
            $this->assign(  );
            $this->send();
        }

    }
*/
class EmailAction extends Action
{
    /* email object */
    public $email;
    public $template;

    /* subclass can define: */
    public $allowOverride = false;  /* let user can submit override fields from web form */
    public $to;
    public $subject;
    public $message;
    public $contentType = 'html';  #default content type

    public function __construct($args = null, $options = array())
    {
        $this->email = new Email;
        if ($this->template) {
            # XXX: check template file
            $this->email->template( $this->template );

        }
        return parent::__construct($args, $options);
    }

    public function getContent()
    {
        if ( $this->message )

            return $this->message;
        return null;
    }

    public function extractFieldsFromThis()
    {
        if ( $this->to )
            $this->email->to( $this->to );

        if ( $this->subject )
            $this->email->subject( $this->subject );

        if ( $content = $this->getContent() ) {
            if ( $this->contentType == 'html' )
                $this->email->html( $content );
            else
                $this->email->text( $content );
        }
    }

    /* the default run method */
    public function run()
    {

        if ($this->allowOverride) {
            $to = $this->arg('to');
            $cc = $this->arg('cc');
            $bcc = $this->arg('bcc');
            $subject = $this->arg('subject');

            /* if class vars is defined, dont override it */
            if ( $to )
                $this->email->to( $to );

            if ( $cc )
                $this->email->cc( $cc );

            if ( $bcc )
                $this->email->bcc( $bcc );

            if ( $subject )
                $this->email->subject( $subject );

            $content = $this->arg('content');
            if ( ! $content )
                $content = $this->getContent();

            if ( $this->contentType == "html" )
                $this->email->html( $content );
            else
                $this->email->text( $content );

        } else {
            $this->extractFieldsFromThis();
        }

        return $this->send();
    }

    public function send()
    {
        if ( empty($this->email->to) )
            $this->error( _('Please enter E-mail address. No receiver email address. ') );

        if ( empty($this->email->from) )
            $this->error( _('Please enter your E-mail address.') );

        if ( ! $this->email->getContent() )

            return $this->error( _('Please enter mail content.') );

        try {
            $this->email->send();
        } catch ( Exception $e ) {
            return $this->error( $e->getMessage() );
        }

        return $this->success(_('Email is sent.'));
    }

}

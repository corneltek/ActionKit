<?php
# require_once 'vendor/pear/swift_required.php';
require 'autoload.php';

//Create the Transport
#  $transport = Swift_SmtpTransport::newInstance('localhost', 25)
#    ->setUsername('')
#    ->setPassword('')
#    ;

/*
You could alternatively use a different transport such as Sendmail or Mail:

//Sendmail
$transport = Swift_SendmailTransport::newInstance('/usr/sbin/sendmail -bs');
*/

//Mail
$transport = Swift_MailTransport::newInstance();

//Create the Mailer using your created Transport
$mailer = Swift_Mailer::newInstance($transport);

//Create a message
$message = Swift_Message::newInstance('Wonderful Subject')
  ->setFrom(array('cornelius.howl@gmail.com' => 'Yo-An'))
  ->setTo(array('cornelius.howl@gmail.com'))
  ->setBody('Here is the message itself')
  ;

//Send the message
$result = $mailer->send($message);

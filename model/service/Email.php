<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use Mailgun\Mailgun;

//Load Composer's autoloader
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Local.php';

class Email {
    protected $mail;
    protected $params;

    public function __construct() {
//        var_dump(Local::mailgunkey);
//        $client = new \Http\Adapter\Guzzle6\Client();
//        'https://api.mailgun.net/v3/sandbox54f77f1c5d2c4ffc93e509a5b935ff89.mailgun.org'
//        $this->mail = Mailgun::create(Local::mailgunkey);
        $this->mail = Mailgun::create(Local::mailgunkey,'https://api.eu.mailgun.net/v3/mg.masesselin.ch');

    }

    public function prepareMessage($subject, $message) {
        $this->params = ['subject' => $subject,
            'html' => $message,];
    }

    public function addAttachment($attachmentPath, $name) {
        $this->params['attachment'] = [['filePath' => $attachmentPath,
            'filename' => $name]];
//        \Mailgun\Api\Message::prepareFile();
    }

    public function sendEmail($toName, $to, $replyToName, $replyTo) {
        # Issue the call to the client.

        # is_valid is 0 or 1
//        $isValid = $result->http_response_body->is_valid;

        $this->params['from'] = 'Masesselin <no-reply@masesselin.ch>';
        $this->params['to'] = $toName . ' <' . $to . '>';
        $this->params['h:Reply-To'] = $replyToName . ' <' . $replyTo . '>';
        $this->mail->messages()->send('mg.masesselin.ch', $this->params);
    }
}

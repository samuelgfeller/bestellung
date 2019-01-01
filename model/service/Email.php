<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require_once __DIR__ . '/../../vendor/autoload.php';

class Email
{
	protected $mail;
	
	public function __construct() {
		$this->mail = new PHPMailer(true);                // Passing `true` enables exceptions
		//Server settings
		$this->mail->SMTPDebug = 0;                                 // Enable verbose debug output
		$this->mail->isSMTP();                                      // Set mailer to use SMTP
		$this->mail->Host = 'srv125.tophost.ch';                    // Specify main and backup SMTP servers
		$this->mail->SMTPAuth = true;                               // Enable SMTP authentication
		$this->mail->Username = 'no-reply@masesselin.ch';           // SMTP username
		$this->mail->Password = 'AehhPyaHs7S4M$';                   // SMTP password
		$this->mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
		$this->mail->Port = 587;
		$this->mail->CharSet = 'UTF-8';
		$this->mail->Encoding = 'base64';
	}
	
	/**
	 * @param $subject
	 * @param $message
	 */
	public function prepare($subject, $message) {
		//Content
		$this->mail->isHTML(true);                                  // Set email format to HTML
		$this->mail->Subject = $subject;
		$this->mail->Body = $message;
		$this->mail->AltBody = strip_tags($message);
//		$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
	
	}
	
	public function send($to, $replyTo, $toName = false, $replyToName = false) {
		try {
			//Recipients
			$this->mail->setFrom('no-reply@masesselin.ch', 'Masesselin');
			$this->mail->addReplyTo($replyTo, $replyToName ?? $replyTo);
			$this->mail->addAddress($to, $toName ?? $to);
			$this->mail->send();
		} catch
		(Exception $e) {
			echo 'Message could not be sent. Mailer Error: ', $this->mail->ErrorInfo;
		}
	}
	

}
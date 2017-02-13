<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class gomail {

	function __construct() {

	}

	function send_email($mailFrom, $title, $mailTo, $subject, $body) {

		require_once APPPATH.'/third_party/phpmailer/phpmailer/class.phpmailer.php';
		require_once APPPATH.'/third_party/phpmailer/phpmailer/class.smtp.php';

		$mail = new PHPMailer();

		// $mail->SMTPDebug = 2;
		// $mail->Debugoutput = 'html';

		$mail->SMTPOptions = array(
			'ssl' => array(
				'verify_peer' => false,
				'verify_peer_name' => false,
				'allow_self_signed' => true
			)
		);

		$mail->CharSet = 'UTF-8';

		$mail->isSMTP();                                      // Set mailer to use SMTP
		$mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
		$mail->SMTPAuth = true;                               // Enable SMTP authentication
		$mail->Username = 'suporteccsa@gmail.com';                 // SMTP username
		$mail->Password = 'UBUNTO-2013#';                           // SMTP password
		$mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
		$mail->Port = 465;                                    // TCP port to connect to
		$mail->isHTML(true);  

		$mail->setFrom($mailFrom, $title);
		$mail->addAddress($mailTo, '');
		$mail->Subject = $subject;
		$mail->Body    = $body;

		return $mail->send();
	
	}
	
}
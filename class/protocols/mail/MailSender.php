<?php
require_once __DIR__ . '/../../../lib/php-mailer/PHPMailerAutoload.php';
/**
 * Sends a e-mail, currently uses PHPMailer as sender
 *
 * @author ensismoebius
 */
class MailSender {
	/**
	 * Destiny
	 *
	 * @var string
	 */
	private static $to;
	
	/**
	 * From
	 *
	 * @var string
	 */
	private static $from;
	
	/**
	 * The mail server
	 *
	 * @var string
	 */
	private static $host;
	
	/**
	 * Mail protocol
	 *
	 * @var string
	 */
	private static $protocol;
	
	/**
	 * Message to send
	 *
	 * @var string
	 */
	private static $message;
	
	/**
	 * The path of the attachment
	 *
	 * @var string
	 */
	private static $attachment;
	
	/**
	 *
	 * @param string $to        	
	 */
	public static function setTo(string $to) {
		self::$to = $to;
	}
	
	/**
	 *
	 * @param string $from        	
	 */
	public static function setFrom(string $from) {
		self::$from = $from;
	}
	
	/**
	 *
	 * @param string $host        	
	 */
	public static function setHost(string $host) {
		self::$host = $host;
	}
	
	/**
	 *
	 * @param string $protocol        	
	 */
	public static function setProtocol(string $protocol) {
		self::$protocol = $protocol;
	}
	
	/**
	 *
	 * @param string $message        	
	 */
	public static function setMessage(string $message) {
		self::$message = $message;
	}
	
	/**
	 *
	 * @param string $attachment        	
	 */
	public static function setAttachment(string $attachment) {
		self::$attachment = $attachment;
	}
	
	/**
	 * Sends the email
	 * 
	 * @return bool
	 */
	public static function sendMail(): bool {
		$mail = new PHPMailer ();
		
		// All fields must be informed
		if (empty ( trim ( self::$protocol ) ) && empty ( trim ( self::$from ) ) && empty ( trim ( self::$host ) ) && empty ( trim ( self::$message ) ) && empty ( trim ( self::$to ) )) {
			return false;
		}
		
		$mail->Mailer = self::$protocol;
		$mail->setFrom ( self::$from );
		$mail->Host = self::$host;
		$mail->Body = self::$message;
		$mail->addAddress ( self::$to );
		
		if ($mail->send ()) {
			$mail->clearAddresses ();
			$mail->clearAttachments ();
			return true;
		}
		
		$mail->clearAddresses ();
		$mail->clearAttachments ();
		return false;
	}
}
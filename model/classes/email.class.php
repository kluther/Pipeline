<?php

class Email
{
	public static function send($email) {
		$smtp = new Smtp(SMTP_SERVER, SMTP_PORT, IS_TLS);
		$smtp->auth(SMTP_USER, SMTP_PASSWORD);
		$smtp->mail_from(SMTP_FROM_EMAIL);
		$smtp->send(
			$email["to"],
			$email["subject"],
			$email["message"],
			array(
				"FROM" => 'Pipeline'
			)
		);	
	}
}
<?php

class Email
{
	public static function send($email) {
		$smtp = new Smtp(SMTP_SERVER, SMTP_PORT, IS_TLS);
		$smtp->auth(SMTP_USER, SMTP_PASSWORD);
		$smtp->mail_from(SMTP_FROM_EMAIL);
		$from = array("FROM" => PIPELINE_NAME);
		$bcc = array("BCC" => $email["bcc"]);
		$addlHeaders = array_merge($from, $bcc);
		$send = $smtp->send(
			$email["to"],
			$email["subject"],
			$email["message"],
			$addlHeaders
		);
		if(!$send !== true) {
			return ($smtp->error(true));
			} else {
			return true;
			}
		}
}
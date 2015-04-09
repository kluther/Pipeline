<?php
	define('SYSTEM_PATH', dirname(__FILE__)); # location of 'site' folder - !NO CHANGE
	set_include_path(SYSTEM_PATH); # include path - !NO CHANGE
	define('TEMPLATE_PATH', SYSTEM_PATH.'/views'); # where the views are - !NO CHANGE
	define('CLASS_PATH', SYSTEM_PATH.'/model/classes'); # where the classes are - !NO CHANGE
	define('UPLOAD_PATH', SYSTEM_PATH.'/upload'); # absolute path to where uploads are <- CHANGE THIS
	define('THUMB_PATH', UPLOAD_PATH.'/thumb'); # absolute path to where thumbnails are - !NO CHANGE
	define('PREVIEW_PATH', UPLOAD_PATH.'/preview'); # absolute path to where previews are - !NO CHANGE
	define('USER_PICTURE_PATH', UPLOAD_PATH.'/user'); # absolute path to unedited user pictures - !NO CHANGE
	define('USER_PICTURE_LARGE_PATH', USER_PICTURE_PATH.'/large'); # absolute path to large user picture thumbnail - !NO CHANGE
	define('USER_PICTURE_SMALL_PATH', USER_PICTURE_PATH.'/small'); # absolute path to small user picture thumbnail - !NO CHANGE
	
	define('BASE_URI', 'http://www.example.com'); # base URI for this Pipeline instance <- CHANGE THIS
	define('COOKIE_DOMAIN','.example.com'); # the domain where the cookie will be available <- CHANGE THIS
	define('COOKIE_PATH','/'); # the path on the server where the cookie will be available <- CHANGE THIS
	define('PIPELINE_NAME', 'Pipeline Demo'); # name of this Pipeline instance <- CHANGE THIS
	define('ENABLE_CHAT',true);     #Chat is for non-commercial uses only. Please change from 'true' to 'false' to disable.
        define('DEFAULT_THEME_ID', 1); # default theme ID <- CHANGE THIS
	define('TIME_ZONE', 'America/New_York'); # name of PHP time zone <- CHANGE THIS
	define('CONTACT_EMAIL', 'email@address.com'); # contact email address <- CHANGE THIS
	define('MAX_UPLOAD_SIZE', 750); # maximum allowed upload size in MB <- CHANGE THIS	

	define('DB_HOST', 'localhost'); # database host <- CHANGE THIS
	define('DB_USERNAME', 'root'); # database username <- CHANGE THIS
	define('DB_PASSWORD', 'qwerty'); # database password <- CHANGE THIS
	define('DB_NAME', 'pipeline'); # database name <- CHANGE THIS
	
	define('SMTP_SERVER', 'ssl://smtp.gmail.com'); # SMTP server needed for sending emails <- CHANGE THIS
	define('SMTP_PORT', '465'); # SMTP server port - !NO CHANGE
	define('SMTP_USER', 'username'); # SMTP server user <- CHANGE THIS
	define('SMTP_PASSWORD', 'qwerty'); # SMTP server user <- CHANGE THIS
	define('SMTP_FROM_EMAIL', "email@address.com"); # email address that system emails are sent from <- CHANGE THIS
	define('IS_TLS', false); # SMTP TLS <- CHANGE THIS TO true IF NOT USING GMAIL

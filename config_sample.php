<?php
	define('SYSTEM_PATH', dirname(__FILE__)); # location of 'site' folder - !NO CHANGE
	set_include_path(SYSTEM_PATH); # include path - !NO CHANGE
	define('TEMPLATE_PATH', SYSTEM_PATH.'/views'); # where the views are - !NO CHANGE
	define('CLASS_PATH', SYSTEM_PATH.'/model/classes'); # where the classes are - !NO CHANGE
	define('UPLOAD_PATH', SYSTEM_PATH.'/upload'); # absolute path to where uploads are <- CHANGE THIS
	define('THUMB_PATH', UPLOAD_PATH.'/thumb'); # absolute path to where thumbnails are <- CHANGE THIS
	define('PREVIEW_PATH', UPLOAD_PATH.'/preview'); # absolute path to where previews are <- CHANGE THIS
	
	define('BASE_URI', 'http://pipeline.cc.gatech.edu'); # base URI for this Pipeline instance <- CHANGE THIS
	define('PIPELINE_NAME', 'Pipeline Demo'); # name of this Pipeline instance <- CHANGE THIS
	
	define('DB_HOST', 'localhost'); # database host <- CHANGE THIS
	define('DB_USERNAME', 'root'); # database username <- CHANGE THIS
	define('DB_PASSWORD', 'qwerty'); # database password <- CHANGE THIS
	define('DB_NAME', 'pipeline'); # database name <- CHANGE THIS
	
	define('SMTP_SERVER', 'ssl://smtp.gmail.com'); # SMTP server needed for sending emails <- CHANGE THIS
	define('SMTP_PORT', '465'); # SMTP server port - !NO CHANGE
	define('SMTP_USER', 'username'); # SMTP server user <- CHANGE THIS
	define('SMTP_PASSWORD', 'qwerty'); # SMTP server user <- CHANGE THIS
	define('IS_TLS', false); # SMTP TLS <- CHANGE THIS TO true IF NOT USING GMAIL
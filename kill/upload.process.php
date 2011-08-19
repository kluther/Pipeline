<?php
require_once('./../../global.php');

header('Pragma: no-cache');
header('Cache-Control: private, no-cache');
header('Content-Disposition: inline; filename="files.json"');

switch ($_SERVER['REQUEST_METHOD']) {
    case 'HEAD':
    case 'GET':
		$token = Filter::alphanum($_GET['token']);
		
		// get any existing uploads
		$itemID = Filter::numeric($_GET['item_id']);
		$itemType = Filter::text($_GET['item_type']);
		$uploads = Upload::getByItemID($itemType, $itemID, false);

		$upload_handler = new UploadHandler(array(
			'token' => $token,
			'uploads' => $uploads
		));
        $upload_handler->get();
        break;
    case 'POST':
		$token = Filter::alphanum($_POST['token']);
		$upload_handler = new UploadHandler(array(
			'token' => $token
		));
        $upload_handler->post();
        break;
    case 'DELETE':
		$upload_handler = new UploadHandler();
        $upload_handler->delete();
        break;
    default:
        header('HTTP/1.0 405 Method Not Allowed');
}
	
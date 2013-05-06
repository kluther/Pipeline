<?php
/*

Copyright (c) 2009 Anant Garg (anantgarg.com | inscripts.com)

This script may be used for non-commercial purposes only. For any
commercial purposes, please contact the author at 
anant.garg@inscripts.com

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
OTHER DEALINGS IN THE SOFTWARE.

*/
/*
    Modifications made by GT team for Pipeline use
 */
require_once("global.php");
include_once TEMPLATE_PATH.'/site/helper/format.php';

//*JAG - Need to quit out if session user is blank. May also want to rename to userid
$_SESSION['username'] = Session::getUserID();

if(empty($_SESSION['username']))
{
	header('Location: '.Url::error());
	exit();
}

//*JAG - Page id is a unique id assigned to each page that loads a chatbox. Currently, only one chat box will load per page.
//        this allows us to track which records have been loaded if multiple tabs for the same chat room are open.
$pageId = Filter::text($_GET['pageId']);
$isValidPageId = Filter::isValidTimestamp($pageId);
if (empty($isValidPageId)){
    $pageId = time();
}

$slug = Filter::text($_GET['slug']);
$project = Project::getProjectFromSlug($slug);

if ($_GET['action'] == "getonlineusers") { getOnlineUsers($slug); } 
if ($_GET['action'] == "chatheartbeat") { chatHeartbeat($slug,$pageId); }
if ($_GET['action'] == "sendchat") { sendChat($pageId); } 
if ($_GET['action'] == "closechat") { closeChat(); } 
if ($_GET['action'] == "startchatsession") { startChatSession($pageId); }


if (!isset($_SESSION['chatHistory'])) {
	$_SESSION['chatHistory'] = array();	
}

if (!isset($_SESSION['openChatBoxes'])) {
	$_SESSION['openChatBoxes'] = array();	
}

function chatHeartbeat($slug,$pageId) {
                    
        //Add check for open chat boxes in order to keep track of multiple windows
        if (empty($_SESSION['openChatBoxes']["$pageId"])){
            $_SESSION['openChatBoxes']["$pageId"] = 0;
            $lastRecord = 0;
        }
        else {
            $lastRecord = $_SESSION['openChatBoxes']["$pageId"];
        }
               
        //**JAG check that SLUG is not null
        $project = Project::getProjectFromSlug($slug);
        $projectName = $project->getTitle();
        //This line is used to track whether we are switching between projects and need to close down chat rooms
        $_SESSION['lastProjectID'] = $project->getID();
        
        //signed in user
        $userId = Session::getUserID();
      
	$chatBoxes = array();
        $chats = Chat::getChats($slug, $lastRecord);
        $numRows = count($chats);
        $rowIndex = 0;
        $items = '';
	
        if (is_array($chats)) {
        foreach ($chats as $row => $chat){
                $rowIndex++;
		if (!isset($_SESSION['openChatBoxes'][$chat['recipient']]) && isset($_SESSION['chatHistory'][$chat['recipient']])) {
                        $items = $_SESSION['chatHistory'][$chat['recipient']];
		}

                //Grab username if available (should always be available)
                $chatFrom = User::load($chat['sender'])->getUsername();
                $chat['message'] = sanitize($chat['message']);
                $message = str_replace('"', '\"',formatParagraphs($chat['message'],true));
                
                //Since chatHeartbeat always returns records greater than the stored last id, the only time that
                // the returned id of a search will match the stored last id will be on the first post in an empty
                // chat room
                if ($chat['id'] !== $lastRecord) {
                $items .= <<<EOD
					   {
			"s": "0",
			"f": "{$chatFrom}",
			"m": "{$message}",
                        "r": "{$chat['id']}",
                        "t": "$projectName"
	   },
EOD;
                }
        
                if (!isset($_SESSION['chatHistory'][$chat['recipient']])) {
		$_SESSION['chatHistory'][$chat['recipient']] = ''; 
                }

                $_SESSION['chatHistory'][$chat['recipient']] .= <<<EOD
						   {
			"s": "0",
			"f": "{$chatFrom}",
			"m": "{$message}",
                        "r": "{$chat['id']}",
                        "t": "$projectName"
	   },
EOD;
		
                $_SESSION['openChatBoxes'][$chat['recipient']] = $chat['sent'];
                
                unset($_SESSION['tsChatBoxes'][$chat['recipient']]);
		
                if (($numRows == $rowIndex) && ($numRows > 0) ) {
                    $_SESSION['openChatBoxes']["$pageId"] = $chat['id'];
                }
        }
}
        //Update user record with heart beat (used to tell logged in members)
        Chat::updateUserLocation($userId, $project->getID());

	if ($items != '') {
		$items = substr($items, 0, -1);
	}
header('Content-type: application/json');
?>
{
		"items": [
			<?php echo $items;?>
        ]
}

<?php
			exit(0);
}

function chatBoxSession($chatbox) {
	
	$items = '';
	
	if (isset($_SESSION['chatHistory'][$chatbox])) {
		$items = $_SESSION['chatHistory'][$chatbox];
	}

	return $items;
}

function startChatSession($pageId) {
	        
        $items = '';
                
	if ($items != '') {
		$items = substr($items, 0, -1);
	}

header('Content-type: application/json');
?>
{
		"username": "<?php echo User::load($_SESSION['username'])->getUsername(); ?>",
		"items": [
			<?php echo $items;?>
        ]
}

<?php


	exit(0);
}

function sendChat($pageId) {
	$from = $_SESSION['username'];
	$to = Filter::text($_POST['to']);
	$message = $_POST['message'];

	$_SESSION['openChatBoxes'][$to] = date('Y-m-d H:i:s', time());
                	
        $fromUsername = User::load($from)->getUsername();
	$messagesan = sanitize($message);

        if (!isset($_SESSION['chatHistory'][$to])) {
		$_SESSION['chatHistory'][$to] = '';
	}

        $_SESSION['chatHistory'][$to] .= <<<EOD
					   {
			"s": "1",
			"f": "{$fromUsername}",
			"m": "{$messagesan}"
	   },
EOD;
                       
	unset($_SESSION['tsChatBoxes'][$to]);
        
        $chat = new Chat(array(
		'sender' => mysql_real_escape_string($from),
		'recipient' => mysql_real_escape_string($to),
		'message' => mysql_real_escape_string($messagesan),
		'sent' => '2013-05-03 12:02:48'
	));
        
        $chat->save();
        $newId = $chat->getID();
        
        if (empty($_SESSION['openChatBoxes']["$pageId"])){
            $_SESSION['openChatBoxes']["$pageId"] = $newId;
        } 
        else{
            if (($newId - 1) == $_SESSION['openChatBoxes']["$pageId"]){
                $_SESSION['openChatBoxes']["$pageId"] = $newId;
            }
        }
        
        echo formatParagraphs($messagesan,true);
        exit(0);
}

function closeChat() {

	unset($_SESSION['openChatBoxes'][$_POST['chatbox']]);
	
	echo "1";
	exit(0);
}

function getOnlineUsers($slug) {
    
    $project = Project::getProjectFromSlug($slug);    
    $allMembers = $project->getAllMembers();
    //For some reason allMembers doesn't return the project creator
    $creator = $project->getCreator();
    array_push($allMembers,$creator);
    
    $usersOnline = array();
    $userIds = '';
    
    foreach($allMembers as $member) {
        //Check last time member sent a heart beat to the chat room
        //array_push($usersOnline,$member->getID());
        //I think this is faster then putting everything in an array and using implode
        $userIds .= ($member->getID() . ",");            
    }
    
    //Need to remove extra comma introduced in foreach loop 
    $userIds = rtrim($userIds,",");
  
    $usersOnline = Chat::getOnlineUsers($project->getID(), $userIds, 15);
        
    header('Content-type: application/json');
    echo json_encode($usersOnline);
        
    exit(0);
}

function sanitize($text) {
	$text = htmlspecialchars($text, ENT_QUOTES);
	$text = str_replace("\n\r","\n",$text);
	$text = str_replace("\r\n","\n",$text);
	$text = str_replace("\n","<br>",$text);
	return $text;
}
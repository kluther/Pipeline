<?php
require_once("../../global.php");
require_once TEMPLATE_PATH.'/site/helper/format.php';

$projectId = isset($_POST['projectID']) ? Filter::numeric($_POST['projectID']) : $_POST['selProject'];
//Validate that the project id specified corresponds to an actual project.
// kick us out if slug or task invalid
$project = Project::load($projectId);
//Find referral url in case there is a problem and we have to redirect the user
$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : Url::dashboard();

if ($project == null) {
    Session::setMessage('You must select a project to upload tasks from a CSV');
    header('Location: '.$referer);
    exit();
}
else {
    //Check if project creator or admin
    if (Session::isAdmin() || ($project->isCreator(Session::getUserID()))) {

        //Want to make sure end of file is .csv and not .xcsv (for example)

        //Need to figure out how to add CSV file filtering

        //Run each line of csv through validator and return JSON string
        $targetDir = UPLOAD_PATH;
        
        // 5 minutes execution time
        @set_time_limit(5 * 60);

        // Get parameters
        $chunk = isset($_REQUEST["chunk"]) ? $_REQUEST["chunk"] : 0;
        $chunks = isset($_REQUEST["chunks"]) ? $_REQUEST["chunks"] : 0;
        $fileName = isset($_REQUEST["name"]) ? $_REQUEST["name"] : '';

        //Make sure the user uploaded a file
        if (empty($fileName)) {
            Session::setMessage('You must select a CSV file');
            header('Location: '.$referer);
            exit();
        }
        
        // Clean the fileName for security reasons
        $fileName = preg_replace('/[^\w\._]+/', '', $fileName);
        $fileType = substr($fileName,-4);

        // Make sure the fileName is unique but only if chunking is disabled
        if ($chunks < 2 && file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName)) {
                $ext = strrpos($fileName, '.');
                $fileName_a = substr($fileName, 0, $ext);
                $fileName_b = substr($fileName, $ext);

                $count = 1;
                while (file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName_a . '_' . $count . $fileName_b))
                        $count++;

                $fileName = $fileName_a . '_' . $count . $fileName_b;
        }

        // Create target dir
        if (!file_exists($targetDir))
                @mkdir($targetDir);
        
        // Look for the content type header
        if (isset($_SERVER["HTTP_CONTENT_TYPE"]))
                $contentType = $_SERVER["HTTP_CONTENT_TYPE"];

        if (isset($_SERVER["CONTENT_TYPE"]))
                $contentType = $_SERVER["CONTENT_TYPE"];

        // Handle non multipart uploads older WebKit versions didn't support multipart in HTML5
        if (strpos($contentType, "multipart") !== false) {
                if (isset($_FILES['file']['tmp_name']) && is_uploaded_file($_FILES['file']['tmp_name'])) {
                        // Open temp file
                        $out = fopen($targetDir . DIRECTORY_SEPARATOR . $fileName, $chunk == 0 ? "wb" : "ab");
                        if ($out) {
                                // Read binary input stream and append it to temp file
                                $in = fopen($_FILES['file']['tmp_name'], "rb");

                                if ($in) {
                                        while ($buff = fread($in, 4096))
                                                fwrite($out, $buff);
                                } else
                                        die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
                                fclose($in);
                                fclose($out);
                                @unlink($_FILES['file']['tmp_name']);
                        } else
                                die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
                } else
                        die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
        } else {
                // Open temp file
                $out = fopen($targetDir . DIRECTORY_SEPARATOR . $fileName, $chunk == 0 ? "wb" : "ab");
                if ($out) {
                        // Read binary input stream and append it to temp file
                        $in = fopen("php://input", "rb");

                        if ($in) {
                                while ($buff = fread($in, 4096))
                                        fwrite($out, $buff);
                        } else
                                die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');

                        fclose($in);
                        fclose($out);
                } else
                        die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
        }

//        if (empty($fileName)) {
//                //$json = array( 'error' => 'You must upload a CSV file' );
//                //exit(json_encode($json));
//                //die('{"jsonrpc" : "2.0", "result" : null, "id" : "id"}');
//        }

        //Check for CSV file
        if ($fileType !== ".csv") {
                $json = array( 'error' => 'Your uploaded file must be a CSV file' );
                exit(json_encode($json));
        }

        //CSV files should be in the format:
        //Title(Required);Instruction (Required);Number Of People Needed (Optional);Deadline (Optional);Leader (Optional)

        $row = 1;
        $handle = fopen($targetDir . DIRECTORY_SEPARATOR . $fileName,"r");

        $taskArray = array();
        //flag to track whether error is found
        $errorFound = 0;
        $errorString ="";



        if ($handle !== FALSE) {
            while (($line = fgetcsv($handle, 1000, ";")) !== FALSE) {
                $numberOfFields = count($line);

                //Validate that we have the minimum number of fields (Task Name and Task Instructions). Everything else can be defaulted

                if (($numberOfFields < 2) || empty($line[0]) || empty($line[1])){

                    if ($row == 1){
                        $errorFound = 1;
                        $errorString .= "<span class=bad>Your first line doest not contain a task name and a description</span><br/>";
                        $row++;
                        continue;
                    }
                    else {
                        $errorFound = 1;
                        $errorString .= "<span class=bad> Line ". $row . " requires a task name and description.</span><br/>";
                        $row++;
                        continue;
                    }
                }
                else {
                    //Verify that we are not reading the header
                    $isHeader = strpos($line[0],"Title(Required)");
                    if ($isHeader !== false) {
                        $row++;
                        continue;
                    }
                    //Format number of people to an integer 
                    if (!empty($line[2])) {
                        $numberOfPeople = Filter::numeric($line[2]);
                        if ($numberOfPeople == false) {
                            $numberOfPeople = 1;
                        }
                    }
                    else {
                        $numberOfPeople = 0;
                    }

                    //Format Deadline, if empty or an invalid date is given, default to a week from today
                    if(!empty($line[3])) {
                        $deadline = strtotime($line[3]);
                        if ($deadline == false){
                            $deadline = strtotime("+1 week");
                            $deadline = date("Y-m-d H:i:s", $deadline);
                        }
                        else {
                            $deadline = date("Y-m-d H:i:s", $deadline);
                        }
                    }
                    else {
                        $deadline = strtotime("+1 week");
                        $deadline = date("Y-m-d H:i:s", $deadline);
                    }

                    //Format Leader, if empty or an invalid name is given, don't enter in anyone
                    if (!empty($line[4]))
                    {
                        $leaderId = User::loadByUsername(Filter::alphanum($line[4])); //***need to change with Chloe's updated user filter***
                        if (empty($leaderId)) {
                            $leaderId = Session::getUserID();
                        }
                    }
                    else{
                        //$leaderId = NULL;
                        $leaderId = Session::getUserID();
                    }
                }

                //Create Task Record
                $title = Filter::text($line[0]);
                $description = Filter::text(iconv(mb_detect_encoding($line[1],mb_detect_order(), true), "UTF-8", $line[1]));


                $task = new Task(array(
                        'creator_id' => Session::getUserID(),		
                        'leader_id' => $leaderId,
                        'project_id' => $projectId,
                        'title' => $title,
                        'description' => $description,
                        'status' => 1,
                        'deadline' => $deadline,
                        'num_needed' => $numberOfPeople
                ));

                array_push($taskArray,$task);

                //Increment row in file
                $row++;
            }
            fclose($handle);
        }

        //Save each task to the database if no errors are found
        if ($errorFound == 1){
            $errorString = "<strong><span class='bad'>Your CSV file was not uploaded.</span></strong><br/>" . $errorString;
            $json = array("error"=>$errorString);
            exit(json_encode($json));
        }
        else {
            foreach($taskArray as $task) {
                $task->save();
            }
            //Send back success message
            Session::setMessage("File successfully uploaded.");
            //header('Location: '.Url::tasks($projectId));
            $json = array('success' => '1','url' => Url::tasks($projectId));
            exit(json_encode($json));
        }


        if (empty($json)) {
            $json = array('success' => '1');
        }

        exit(json_encode($json));
    }
    //User should not hit this statement because we should only be showing the upload form
    // if the user is a project creator or an admin.
    else {
        header('Location: '.Url::error());
        exit();
    }
}
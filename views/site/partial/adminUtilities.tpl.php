<?php
require_once TEMPLATE_PATH.'/site/helper/format.php';

$fork = $SOUP->fork();

//Grab project if this is being opened up by someone who is not an admin. This
// would occur if the person is uploading a CSV file from the task tab within 
// a project.
$loadedProject = $SOUP->get('project');
if (!empty($loadedProject)){
    $loadedProjectID = $loadedProject->getID();
    $cancelButtonText = "Close";
}
else {
    //If the button is on the Admin menu, we just want to clear the text. 
    // Otherwise if the button is on the Task tab in a project, we want to close 
    // the containing div.
    $cancelButtonText = "Clear";
}

//Grab all projects for the admin user.
if (Session::isAdmin()) {
    $projects = $SOUP->get('projects');
}

?>

<script type="text/javascript">

$(document).ready(function(){
                       
//        $("#selCSVUpload").change(function(){
//           if ($('#selCSVUpload').val() > -1) {
//               $('#previewUpload').load('<= Url::previewCSV() ?>',{csvID : $('#selCSVUpload').val()})
//           } 
//        });
        
        initializeUploader();
});

        function uploadComplete() {
                
	}

</script>

<?php
$fork->set('title', 'Import Tasks');
$fork->startBlockSet('body');
?>

<p>You are able to import tasks into Pipeline if they are in a .CSV file. Check out <a href='<?php echo Url::help()."#help-uploadCSV"; ?>'>this</a> if it's your first time.</p>
<p>This utility will add tasks to your project, and will not override the tasks that you currently have in your project.</p>

<form action="<?php echo Url::adminUtilitiesProcess(); ?>" method="post" id="frmNewItem" enctype="multipart/form-data">
    <?php
        //If we don't have a project id, then we are probably in the context of the 
        // Admin tab which can select any project. Therefore, we want to return a 
        // drop down box will all projects to select form.
        if (empty($loadedProject)) {
           echo "<div class='clear'>
                    <label for='selProject'>Step 1: Select a Project that you want to import the tasks into<span class='required'>*</span></label>
                    <div class='input'>
                            <select id='selProject' type='text' value='' name='selProject'>";
                                
                                    echo "<option value=-1></option>";
                                    foreach($projects as $project) {
                                        $text = $project->getTitle();
                                        $value = $project->getID();

                                        echo "<option value=$value>$text</option>";
                                    }

                                

                         echo "</select>
                    </div>
                </div>";
        }
        //Otherwise if we have a project id, pass the project Id through a 
        // hidden field so we can later submit the form with the project id.
        else {
            echo "<input id='selProject' value=$loadedProjectID type='hidden'></input>";
        }
    ?>
    
    <div class="clear">
            <label for="selCSV">
                
                <?php
                    //Add Steps to label only if process is multistep
                    $step = (empty($loadedProject) ? "Step 2: " : null);
                    if ($step !== null){
                        echo $step;
                    }
                ?>
               Select a .CSV with the tasks that you want to upload<span class="required">*</span></label>
            <div class="input">
                    <input type="button" id="selCSV" value="Select CSV" />
                    <div id="filelist"></div>
            </div>
   </div>

   <div class="clear">
            <div class="input">
                    <br/>
                    <input type="submit" id="btnCreateTasks" value="Upload CSV" />
                    <input type="button" id="btnCancel" value="<?php echo $cancelButtonText ?>" />
           </div>
   </div>
   <div id="errorCSV" class="clear">
        
   </div>
</form>

<?php
    $SOUP->render('site/partial/newCSVUpload', array(
    'uploadButtonID' => 'btnCreateTasks',
    'formID' => 'frmNewItem' 
    ));
?>

<?php
    $fork->endBlockSet();
    $fork->render('site/partial/panel');
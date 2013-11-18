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

<?php
$fork->set('title', 'Manage Documents');
$fork->startBlockSet('body');
?>

<!--Don't Include the List-->

<form method="<?php echo Url::adminUtilitiesProcess(); ?>" id="frmNewItem" enctype="multipart/form-data">
        <h6 class="primary"><strong>Adult IRB</strong></h6>
        <div class ="funnel">
            <div class="jQ-dropdown-nav">
                <ul>
                    <li onmouseup="slide('pa1', 'na1')">Manage Current Documents</li>
                    <li onmouseup="slide('na1', 'pa1')">Upload a New Document</li>
                </ul>
            </div>
        </div>
        
        <!--pa1-->
        <div class="jQ-dropdown" id="pa1">
            <ul class="segmented-list activity">
                <li>
                    <div class="clear">
                        <label for="a1">Rev 1</label>
                        <div class="input">
                            <input type="radio" id="a1" name="a" value="a1" />
                            <p>Select to make this the current document</p>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="clear">
                        <label for="a2">Rev 2</label>
                        <div class="input">
                            <input type="radio" id="a2" name="a" value="a2" checked />
                            <p>Select to make this the current document</p>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="clear">
                        <label for="a3">Draft 1</label>
                        <div class="input">
                            <input type="radio" id="a3" name="a" value="a3" />
                            <p>Select to make this the current document</p>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
        
        <!--na1-->
        <div class="jQ-dropdown" id="na1">
            <div class="clear">
                <label for="na1current">Make this the new current document</label>
                <div class="input">
                    <input type="checkbox" id="na1current" checked />
                </div>
            </div>
            <div class="clear">
                <label for="na1name">Document Name</label>
                <div class="input">
                    <input type="text" id="na1name" />
                </div>
            </div>
            <div class="clear">
                <label for="na1file">Upload the File</label>
                <div class="input">
                    <input type="file" id="na1file" />
                </div>
            </div>
            <div class="clear">
                <div class="input">
                    <input type="submit" id="na1submit" />
                </div>
            </div>
        </div>
        
        
        
        
        
        <hr />
        <h6 class="primary"><strong>Parent Consent</strong></h6>
        <input type="button" id="newB" value="New Parent Consent Document" />
        <ul class="segmented-list activity">
            <li>
                <div class="clear">
                    <label for="b1">Rev 1</label>
                    <div class="input">
                        <input type="radio" id="b1" name="b" value="b1" checked />
                        <p>Select to make this the current document</p>
                    </div>
                </div>
            </li>
            <li>
                <div class="clear">
                    <label for="b1">Rev 2</label>
                    <div class="input">
                        <input type="radio" id="b2" name="b" value="b2" />
                        <p>Select to make this the current document</p>
                    </div>
                </div>
            </li>
            <li>
                <div class="clear">
                    <label for="b3">Another Document</label>
                    <div class="input">
                        <input type="radio" id="b3" name="b" value="b3" />
                        <p>Select to make this the current document</p>
                    </div>
                </div>
            </li>
        </ul>
</form>
<?php
    $fork->endBlockSet();
    $fork->render('site/partial/panel');
    
    $fork->set('title', 'Add a New Document Type');
    $fork->startBlockSet('body');
?>

<input type="button" id="newType" value="Add a New Document Type" />
    
<?php
    $fork->endBlockSet();
    $fork->render('site/partial/panel');
<html>
<head>
<!--include external scripts-->
<?php require_once("./KalturaClient.php"); ?>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/swfobject/2.2/swfobject.js"></script>
</head>
<body>
<?php
//define constants
define("KALTURA_PARTNER_ID", 679712);
define("KALTURA_PARTNER_SERVICE_SECRET", "b2771cbeb6d9c21c156b4d4df445c508");
//define session variables
$partnerUserID          = 'ANONYMOUS';
//construct Kaltura objects for session initiation
$config           = new KalturaConfiguration(KALTURA_PARTNER_ID);
$client           = new KalturaClient($config);
$ks               = $client->session->start(KALTURA_PARTNER_SERVICE_SECRET, $partnerUserID, KalturaSessionType::USER);
//Prepare variables to be passed to embedded flash object.
$flashVars = array();
$flashVars["uid"]               = $partnerUserID;
$flashVars["partnerId"]         = KALTURA_PARTNER_ID;
$flashVars["ks"]                  = $ks;
$flashVars["afterAddEntry"]     = "onContributionWizardAfterAddEntry";
$flashVars["close"]       = "onContributionWizardClose";
$flashVars["showCloseButton"]   = false; 
$flashVars["Permissions"]       = 1;
?>
<div id="kcw"></div>
<script type="text/javascript">
var params = {
        allowScriptAccess: "always",
        allowNetworking: "all",
        wmode: "opaque"
};
// php to js
var flashVars = <?php echo json_encode($flashVars); ?>;
<!--embed flash object-->
swfobject.embedSWF("http://www.kaltura.com/kcw/ui_conf_id/1000741 ", "kcw", "680", "360", "9.0.0", "expressInstall.swf", flashVars, params);
</script>
<!--implement callback scripts-->
<script type="text/javascript">
function onContributionWizardAfterAddEntry(entries) {
        alert(entries.length + " media file/s was/were succsesfully uploaded");
        for(var i = 0; i < entries.length; i++) {
                alert("entries["+i+"]:EntryID = " + entries[i].entryId);
        }
}
</script>
<script type="text/javascript">
function onContributionWizardClose() {
        alert("Thank you for using Kaltura Contribution Wizard");
}
</script>
</body>
</html>
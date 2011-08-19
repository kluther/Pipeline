<!--include external scripts-->
<?php require_once(SYSTEM_PATH."/lib/kaltura/KalturaClient.php"); ?>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/swfobject/2.2/swfobject.js"></script>
<?php
// get SOUP var
$token = $SOUP->get('token');
$wrapperID = $SOUP->get('wrapperID', 'kcw-wrapper');
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
<div style="margin: 0; padding: 0; height: auto; width: auto; overflow: hidden;" id="<?= $wrapperID ?>"><div id="kcw"></div></div>
<script type="text/javascript">
var params = {
        allowScriptAccess: "always",
        allowNetworking: "all",
        wmode: "opaque"
};
// php to js
var flashVars = <?php echo json_encode($flashVars); ?>;
<!--embed flash object-->
swfobject.embedSWF("http://www.kaltura.com/kcw/ui_conf_id/1000199 ", "kcw", "680", "360", "9.0.0", "expressInstall.swf", flashVars, params);

<!--implement callback scripts-->
function onContributionWizardAfterAddEntry(entries) {
	for(var i=0; i<entries.length; i++) {
		$.post(
			'<?= Url::uploadProcess() ?>',
			{
				'kalturaID': entries[i].entryId,
				'token': '<?= $token ?>'
			}
		);
	}
}

function onContributionWizardClose() {
        $('#<?= $wrapperID ?>').dialog('close');
}

$(document).ready(function(){
	$('#<?= $wrapperID ?>').dialog({
		title: 'Upload Media',
		autoOpen: false,
		modal: true,
		height: 390,
		width: 680
	});
	$('input.upload').click(function(){
		$('#<?= $wrapperID ?>').dialog('open');
	});
});
</script>
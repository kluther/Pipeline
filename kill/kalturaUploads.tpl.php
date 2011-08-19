<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/swfobject/2.2/swfobject.js"></script>
<?php require_once(SYSTEM_PATH."/lib/kaltura/KalturaClient.php"); ?>
<?php

$uploads = $SOUP->get('uploads');
$empty = $SOUP->get('empty');


$entryIDs = array();
foreach($uploads as $upload) {
	$entryIDs[] = $upload->getKalturaID();
}
$entryIDs = implode(',',$entryIDs);



//define session variables
$partnerUserID    = 'ANONYMOUS';

//construct Kaltura objects for session initiation
$config           = new KalturaConfiguration(KALTURA_PARTNER_ID);
$client           = new KalturaClient($config);
$ks               = $client->session->start(KALTURA_PARTNER_SERVICE_SECRET, $partnerUserID, KalturaSessionType::USER);
$client->setKs($ks);  // set the session in the client

$filter = new KalturaMediaEntryFilter();
$filter->idIn = $entryIDs;
//$filter->statusEqual = KalturaEntryStatus::READY;
//$filter->mediaTypeEqual = KalturaMediaType::VIDEO;
//$filter->tagsLike = "demos";

// $pager = new KalturaFilterPager();
// $pager->pageSize = 50;
// $pager->pageIndex = 1;
$list = $client->media->listAction($filter); // list all of the media items in the partner

		$entryId = $list->objects[1]->id;
		$player_width = 500;
		$player_height = 310;
		$autoPlay = "1";
		$backgroundColor = "000000";	

foreach($list->objects as $mediaEntry) {
	echo $mediaEntry->id . '<br />';
	echo '<img src="'.$mediaEntry->thumbnailUrl.'" />';
}
?>
<script type="text/javascript">
	    if (swfobject.hasFlashPlayerVersion("9.0.0")) {
	      var fn = function() {
	        var att = { data:"http://www.kaltura.com/index.php/kwidget/wid/_0/uiconf_id/1000106", 
						width:"<?php echo($player_width); ?>", 
						height:"<?php echo($player_height); ?>",
						id:"mykdp",
						name:"mykdp" };
	        var par = { flashvars:"&entryId=<?= $entryId; ?>" +
							"&autoPlay=<?php echo($autoPlay); ?>",
						allowScriptAccess:"always",
						allowfullscreen:"true",
						bgcolor:"<?php echo($backgroundColor); ?>"
					};
	        var id = "swfplayer";
	        var myObject = swfobject.createSWF(att, par, id);
	      };
	      swfobject.addDomLoadEvent(fn);
	    }
</script>
<div id="swfplayer"></div>
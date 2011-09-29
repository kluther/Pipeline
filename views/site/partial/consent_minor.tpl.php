<?php

$fork = $SOUP->fork();
$fork->startBlockSet('body');

?>
<script type="text/javascript">

$(document).ready(function(){
	$('#txtConsentEmail').focus();
	
	$('#btnAgree').mousedown(function(){
		var email = $('#txtConsentEmail').val();
		var name = $('#txtConsentName').val();
		
		buildPost({
			'processPage':'<?= Url::consentProcess() ?>',
			'info':{
				'email':email,
				'name':name
			},
			'buttonID':'#btnAgree'
		});
	});
	
	$('#btnNoThanks').mousedown(function(){
		window.location = '<?= Url::base() ?>';
	});
	
});

</script>

<p>Because you're under age 18, you need to complete a special set of consent forms before you can use <?= PIPELINE_NAME ?>. This is required by the Georgia Tech Institutional Review Board (IRB) as part of their <a href="http://www.compliance.gatech.edu/irb-informed-consent/">informed consent protocol</a>.</p>

<p>These forms are linked below. The first form must be completed by you and the second must be completed by a parent. Please print them out, complete them, and send them to the research team. You can either scan them and email the files to <a href="mailto:<?= CONTACT_EMAIL ?>"><?= CONTACT_EMAIL ?></a> or fax them to (404) 894-3146. <strong>Please include your email address with the forms</strong> or we won't be able to approve your account.</p>

<p>Thanks so much for your patience and we apologize for the inconvenience. We'll do everything we can to process these forms quickly and get you using <?= PIPELINE_NAME ?> as soon as possible.</p>

<a title="View Minor Assent Testing on Scribd" href="http://www.scribd.com/doc/66688222/Minor-Assent-Testing?secret_password=o9l5uh0iy9zeq1docrm" style="margin: 12px auto 6px auto; font-family: Helvetica,Arial,Sans-serif; font-style: normal; font-variant: normal; font-weight: normal; font-size: 14px; line-height: normal; font-size-adjust: none; font-stretch: normal; -x-system-font: none; display: block; text-decoration: underline;">Minor Assent Testing</a> <object id="doc_86890" name="doc_86890" height="600" width="100%" type="application/x-shockwave-flash" data="http://d1.scribdassets.com/ScribdViewer.swf" style="outline:none;" >            <param name="movie" value="http://d1.scribdassets.com/ScribdViewer.swf">             <param name="wmode" value="opaque">             <param name="bgcolor" value="#ffffff">             <param name="allowFullScreen" value="true">             <param name="allowScriptAccess" value="always">             <param name="FlashVars" value="document_id=66688222&access_key=key-ic3epll6pthwwipc12q&page=1&viewMode=list">             <embed id="doc_86890" name="doc_86890" src="http://d1.scribdassets.com/ScribdViewer.swf?document_id=66688222&access_key=key-ic3epll6pthwwipc12q&page=1&viewMode=list" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" height="600" width="100%" wmode="opaque" bgcolor="#ffffff"></embed>         </object>

<a title="View Parent Consent Testing on Scribd" href="http://www.scribd.com/doc/66688229/Parent-Consent-Testing?secret_password=16ydyjh9ee2mwgq9sn7f" style="margin: 12px auto 6px auto; font-family: Helvetica,Arial,Sans-serif; font-style: normal; font-variant: normal; font-weight: normal; font-size: 14px; line-height: normal; font-size-adjust: none; font-stretch: normal; -x-system-font: none; display: block; text-decoration: underline;">Parent Consent Testing</a> <object id="doc_37812" name="doc_37812" height="600" width="100%" type="application/x-shockwave-flash" data="http://d1.scribdassets.com/ScribdViewer.swf" style="outline:none;" >            <param name="movie" value="http://d1.scribdassets.com/ScribdViewer.swf">             <param name="wmode" value="opaque">             <param name="bgcolor" value="#ffffff">             <param name="allowFullScreen" value="true">             <param name="allowScriptAccess" value="always">             <param name="FlashVars" value="document_id=66688229&access_key=key-am4od775v9pd9j0v7d7&page=1&viewMode=list">             <embed id="doc_37812" name="doc_37812" src="http://d1.scribdassets.com/ScribdViewer.swf?document_id=66688229&access_key=key-am4od775v9pd9j0v7d7&page=1&viewMode=list" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" height="600" width="100%" wmode="opaque" bgcolor="#ffffff"></embed>         </object>

<?php

$fork->endBlockSet();
$fork->render('site/partial/panel');
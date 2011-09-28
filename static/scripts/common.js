// @param options json - processPage, info, buttonID
function buildPost(options){
	if(options.buttonID != null){
		$(options.buttonID).attr('disabled', 'disabled');
		$(options.buttonID).addClass('disabledButton');
		}
	$.post(options.processPage,options.info,function(data){
		data = data.substring(data.indexOf('{"')); //Needed because rename throws an error in windows. This removes extracts json from the data string.
		data = $.parseJSON(data);
		if (data != null){
			if (data.success != null){	
				if (data.successUrl != null)
					window.location.replace(data.successUrl);
				else
					window.location.reload();
				}
			else if (data.error != null){
				displayNotification(data.error,'error');
				if(options.buttonID != null){
					$(options.buttonID).removeAttr("disabled");
					$(options.buttonID).removeClass('disabledButton');
					}
				}
		}
		else{
			displayNotification("Sorry, an error occurred. Please try again.",'error');
			if(options.buttonID != null){
				$(options.buttonID).removeAttr("disabled");
				$(options.buttonID).removeClass('disabledButton');
				}
			}
		});
	}
	
function displayNotification(message,type)
{
	if (type == "error")
		$("#feedback").feedback(message, {duration: 5000, type: "error"});
	else
		$("#feedback").feedback(message, {duration: 5000});
}

function toggleEditView(edit, view)
{
	if ($(edit).is(":hidden"))
	{
		$(view).hide();
		$(edit).fadeIn();
	}
	else if ($(view).is(":hidden"))
	{
		$(edit).hide();
		$(view).fadeIn();
	}	
}

function split( val ) {
	return val.split( /,\s*/ );
}
function extractLast( term ) {
	return split( term ).pop();
}

function rms_reloaderOn(params)
{
	if(params != undefined)
	{
		$('.reloader-wrapper span').html("<br>" + params);
	} 	
	$('#reloader').show();
}
function rms_reloaderOff(params)
{
	$('#reloader').fadeOut();
}

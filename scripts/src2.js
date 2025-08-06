$(document).ready(function()
{
	$('.btn-login').click(function()
	{
		var usr = $('#usernamengpogingronan').val();
		var pwd = $('#passwordngpogingronan').val();
		
		if(usr === '')
		{
			app_alert("Invalid Admin Name","Please enter correct Admin Name","warning","Ok Thanks","usernamengpogingronan","focus");
			return false;
		}
		if(pwd === '')
		{
			app_alert("Invalid Details","Please enter correct Admin Password","warning","Ok Thanks","passwordngpogingronan","focus");
			return false;
		}
		$('.btn-login').attr('disabled', true);
		rms_reloaderOn("Looking for your credentials...");
		setTimeout(function()
		{
			$.post("./src/admin_process.php", { username: usr, password: pwd },
			function(data) {
				$('.pogikotlgsheethahahaha').html(data);
				$('.btn-login').attr('disabled', false);
				rms_reloaderOff();
			});
		},1000);
	});
});
function openShifting()
{
	$('#loginngadminnapogi').fadeOut();
	$('#storeshift').attr('disabled', false);
	$('#btnlocked').removeClass('fa-lock');
	$('#btnlocked').addClass('fa-lock-open');
}

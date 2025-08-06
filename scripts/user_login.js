function pushLogin()
{
	var mode = 'c10cc6b684e1417e8ffa924de1e58373';
	var usr = $('#u_name').val();
	var psw = $('#p_word').val();
	var shifting = $('#storeshift').val();
	if(usr == '')
	{
		app_alert("Login Error","Invalid Username","warning","Ok","username","no");
		return false;
	}
	else if(psw == '')
	{
		app_alert("Login Error","Invalid Password","warning","Ok","password","no");
		return false;
	}
	else if(storeshift == '')
	{
		app_alert("Store Shift","Please select your store shifting","warning","Ok","","");
		return false;
	}
	$('#dloginbtn').html('Loging In <i class="fa fa-spinner fa-spin"></i>');
	$('#dloginbtn').attr('disabled', true);
 	setTimeout(function()
 	{
	 	$.post("./actions/login_process.php", { mode: mode, username: usr, password: psw, shifting: shifting },
		function(data) {
			$('.login').html(data);
			$('#dloginbtn').html('Login');
			$('#dloginbtn').attr('disabled', false);
		});
	},2000);
}
$(function()
{
	$('body').keydown(function(event) {
	    if(event.which == 113) { //F2
	       	UpdateUsers();
	        return false;
	    } 
	});
	$('#showpassword').click(function()
	{
		if ($('#showpassword').hasClass('fa-solid fa-eye-slash'))
		{
			$('#showpassword').removeClass('fa-solid fa-eye-slash');
			$('#showpassword').addClass('fa-solid fa-eye');
			$('#p_word').attr('type', 'text'); // Use .attr() instead of .get(0)
		} else {
			$('#showpassword').removeClass('fa-solid fa-eye');
			$('#showpassword').addClass('fa-solid fa-eye-slash');
			$('#p_word').attr('type', 'password');
		}
	});	
	$("#u_name").keydown(function (e) {
	  if (e.keyCode == 13) {
	    document.getElementById('p_word').focus();
	  }
	});

	$("#p_word").keydown(function (e) {
	  if (e.keyCode == 13) {
	    $('#dloginbtn').trigger('click');
	  }
	});
	
});

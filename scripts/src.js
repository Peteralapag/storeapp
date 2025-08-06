$(document).ready(function()
{   
	$('#storeshift').change(function()
	{
		$('#storeshift').attr('disabled', true);
		$('#btnlocked').addClass('fa-lock');
		$('#btnlocked').removeClass('fa-lock-open');
	});
	$('.btn-locked').click(function()
	{
		$.post("./src/admin_index.php", { },
		function(data) {
			$('#loginngadminnapogi_page').html(data);
			$('#loginngadminnapogi').show();
		});
		
	});
    $('#u_name').change(function()
    {
    	$('#p_word').val('');
    	document.getElementById('p_word').focus()
    });
});

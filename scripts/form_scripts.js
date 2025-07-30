function set_session(value,params)
{
	$.post("./actions/set_session_process.php", { params: params, value: value },
	function(data) {
		$("#"+sessionStorage.navcount).click();
	});
}
$(function()
{
	$('#kilo_used').keyup(function()
	{
		calculate();	
	});
	$('#category').change(function()
	{
		$('#itemname').val('');
	});
	$('#itemname').change(function()
	{	
		var valvas = $("#items option:selected").text();
		var mode = 'getiteminfo';
		
		var itemname = $('#itemname').val();
		if(itemname == '')
		{ 
			$('#itemid').val(''); 
			$('.btnnew,.btnupdate').prop('disabled', true); 
			return false;
		} else { 
			$('.btnnew,.btnupdate').prop('disabled', false); 
		} 
		$.post("./actions/actions.php", { mode: mode, itemname: itemname },
		function(data) {
			$('.results').html(data);
			calculate();
		});
	});
});
function GetItems()
{
	var mode = 'getitems';
	var category = $('#category').val();
	var itemname = $('#itemname').val();
	
	$.post("./actions/actions.php", { mode: mode, category: category, itemname: itemname },
	function(data) {
		rms_reloaderOff();
		$('#items').html(data);
	});
}
<div id="employees"></div>
<div id="users"></div>
<div id="privilege"></div>
<script>
$(function()
{
	$('#employees').html('Checking Employees <i class="fa fa-spinner fa-spin"></i>');
	setTimeout(function()
	{
		checkEmployees();
	},1000);
});
function checkUsersPrivilege()
{
	$('#privilege').html('Checking Users <i class="fa fa-spinner fa-spin"></i>');
	var mode = 'checkprivilege';
	setTimeout(function()
	{
		$.post("./updating/process.php", { mode: mode },
		function(data) {
			$('#privilege').html(data);			
		});
	},1000);
}
function checkEmployees()
{
	var mode = 'checkemployees';
	$('#employees').html("Updating Employees Table");
	setTimeout(function()
	{
		$.post("./updating/process.php", { mode: mode },
		function(data) {
			$('#employees').html(data);			
		});
	},1000);
}
function checkUsers()
{
	var mode = 'checkusers';
	$('#users').html('Checking Users <i class="fa fa-spinner fa-spin"></i>');
	setTimeout(function()
	{
		$.post("./updating/process.php", { mode: mode },
		function(data) {
			$('#users').html(data);			
		});
	},1000);
}
</script>

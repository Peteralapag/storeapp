<div id="detect"></div>
<script>
$(function()
{
//	execute_check();
	setInterval(function()	
	{
		if($("#updateapp").is(":visible"))
		{			
		} else {
		    execute_check();
		}
	}, 10000);	
});
function execute_check()
{
	if(sessionStorage.updatelater == null || sessionStorage.updatelater == '')
	{
		$.post("./updates/detect_update.php", { },
		function(data) {
			if(data == 1)
			{
				updateApps();
			}
		});
	}
}
</script>
<?php include 'modules/modals/modals.php'?>
<?php include 'modules/loader/loader.php'?>
<script src="scripts/default_scripts.js"></script>
<script src="scripts/user_login.js"></script>
<script src="scripts/jquery-ui.js"></script>
<script src="scripts/jquery.dataTables.min.js"></script>
<script src="scripts/dataTables.bootstrap.min.js"></script>
<body>

</body>

</html>

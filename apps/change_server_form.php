<?php
require '../class/functions.class.php';
$functions = new TheFunctions;
$server = $functions->GetOnlineServer('server_ip');
?>
<div>
	<label>Current Server <span id=""></span></label>
	<!--select id="server" class="form-control" onchange="selectServer(this.value)"-->
	<select id="server" class="form-control">
	
		<?php echo $functions->GetServer($server); ?>
	</select>
	<button type="button" class="btn btn-primary form-control-sm" style="margin-top:1rem; float:right" onclick="selectServer()"><i class="fa fa-long-arrow-right" aria-hidden="true"></i>&nbsp;Connect</button>
</div>
<div id="admindata"></div>
<script>
function selectServer()
{	
	var server = $('#server').val();
	var servername = $("#server option:selected").text();
	if(servername == 'Globe Server')
	{
		var remote_url = "storeupdate.rosebakeshops.com";
	}
	if(servername == 'Converge Server')
	{
		var remote_url = "storeupdate.rosebakeshops.com";
	}
	rms_reloaderOn('Connecting Server...');
	setTimeout(function()
	{
		$.post("../actions/change_server.php", { servername: servername, server: server, remote_url: remote_url },
		function(data) {
			$('#admindata').html(data);		
		});
	},15000);
}

</script>


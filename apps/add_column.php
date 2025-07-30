<table style="width: 500px" class="table">
	<tr>
		<th style="width:200px">TABLE NAME</th>
		<th>PARAMETERS</th>
	</tr>
	<tr>
		<td><input id="tablename" type="text" class="form-control" placeholder="Table Name" autocomplete="off"></td>
		<td><input id="command" type="text" class="form-control" placeholder="Command Paremeters" autocomplete="off"></td>
	</tr>
</table>
<p style="text-align:right">
	<button class="btn btn-danger" onclick="exeCuteCommand()">EXECUTE COMMAND</button>
</p>
<div id>
<script>
function exeCuteCommand()
{
	var mode = 'addcolumn';
	var tablename = $('#tablename').val();
	var command = $('#command').val();
	$.post("../actions/database_process.php", { mode: mode, tablename: tablename, command: command },
	function(data) {
		$('#toolsdata').html(data);
	});
}
</script>
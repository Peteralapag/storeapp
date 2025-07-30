<?php
require '../init.php';
$modyul = new SystemTools;
?>
<h5 style="text-align:center;margin-top:0;margin-bottom:20px">Recover Item ID's from Store Items</h5>
<table style="width: 100%">
	<tr>
		<td colspan="4">
			<select id="modyul" class="form-control" onchange="getModule(this.value)">
				<option value="fgts">FINISHED GOODS</option>
				<option value="rawmats">RAWMATS</option>
			</select>
		</td>
	</tr>
	<tr>
		<td colspan="4">&nbsp;</td>
	</tr>
	<tr>
		<td style="width:80px">SELECT ITEM</td>
		<td style="width:10px">&nbsp;</td>
		<td>
			<select id="moduleitems" class="form-control"></select>
		</td>
	</tr>
		<tr>
		<td colspan="4">&nbsp;</td>
	</tr>
		<tr>
		<td colspan="4" style="text-align:right">
			<!--button class="btn btn-warning pull-left" style="width:100px;" onclick="executeFixedAll()">Fix All</button-->
			<button class="btn btn-primary" onclick="executeFixed()">Fixed Now</button>
			<button class="btn btn-danger" onClick="closeDialoque('fixids');">Exit</button>
		</td>
	</tr>
</table>
<div class="notifs" style="padding:5px;"></div>
<div class="resultae"></div>
<script>
function executeFixedAll()
{
	var mode = 'fixallids';
	rms_reloaderOn("Fixing all IDs");
	setTimeout(function()
	{
		$.post("../actions/database_process.php", { mode: mode },
		function(data) {
			$('.resultae').html(data);
			rms_reloaderOff();
		});
	},2000);
}
function executeFixed()
{
	var mode = 'getmoduleitems';
	var items = $('#moduleitems').val();
	rms_reloaderOn('Fixing Missing IDs...');
	setTimeout(function()
	{
		$.post("../actions/actions.php", { mode: mode, items: items },
		function(data) {
			$('.resultae').html(data);
			rms_reloaderOff();
			swal("Success","Successfuly fixing the Item","success");
		});
	},1000);
	
}
function getModule(module)
{
	var mode = 'getmodule';
	$.post("../actions/actions.php", { mode: mode, module: module },
	function(data) {
		$('#moduleitems').html(data);
	});
}
$(function()
{
	var module = $('#modyul').val();
	 getModule(module);
});
</script>
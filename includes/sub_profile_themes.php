<?php include '../init.php'; ?>
<table style="width: 300px;">
	<tr>
		<th>
			SELECT THEME COLOR
		</th>
	</tr>
		<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>
			<select id="mythemes" class="form-control input-lg">
			<?php echo $functions->ThemeColor(THEME_COLOR); ?>
		</select>
	</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
</table>
<div style="margin-top:10px;text-align:right">
	<button class="btn btn-success" style="width:100px;" onClick="applyThemes();">Apply</button>
	<button class="btn btn-primary" style="width:100px;" onClick="closeDialoque('themes');">Close</button>
</div>
<div id="themeresults"></div>
<script>
function applyThemes()
{
	var mode = 'changethemes';
	var val = $('#mythemes').val();
	$('#themes').fadeOut();
	rms_reloaderOn('Applying...');
	setTimeout(function()
	{
		$.post("../actions/actions.php", { mode: mode, val: val },	
		function(data) {
			$('#themeresults').html(data);
			rms_reloaderOff();
		});
	},1000);

}
</script>

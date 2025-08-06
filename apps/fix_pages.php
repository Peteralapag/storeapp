<style>
.tools-wrapper {
	margin-top:20px;
}
</style>
<button class="btn btn-success" onclick="itemIds()">Fix Item IDs</button>
<button class="btn btn-info" onclick="addColumn()">Add Column</button>
<button class="btn btn-primary" onclick="fixSummary()">Fix RM Summary</button>
<hr>
<div class="tools-wrapper" id="toolsdata">Loading...</div>
<script>
$(function()
{
	itemIds();
});
function fixSummary()
{
	rms_reloaderOn('Fixing Summary Database');
	setTimeout(function()
	{
		var mode = 'fixsummary';
		$.post("../actions/database_process.php", { mode: mode },
		function(data) {
			$('#toolsdata').html(data);
			rms_reloaderOff();
		});
	},2000);
}
function addColumn()
{
	$.post("../apps/add_column.php", { },
	function(data) {
		$('#toolsdata').html(data);
	});
}
function itemIds()
{
	$.post("../apps/fix_ids.php", { },
	function(data) {
		$('#toolsdata').html(data);
	});
}
</script>
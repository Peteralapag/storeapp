<?php
include '../init.php';
require '../class/brrr.class.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$brrr = new brrr;

$branch = $brrr->GetSession('branch');
$reportdate = $brrr->GetSession('branchdate');

$overheadstatus = $brrr->overheadstatus($branch, $reportdate, $db);
$overheadcount = $brrr->overheadcount($branch, $reportdate, $db);
?>

<table style="width: 100%; border-collapse: collapse; white-space: nowrap;" cellpadding="0" cellspacing="0">
	<tr>
		<?php if ($overheadstatus != 1): ?>
			<td style="text-align: left;">
				<button id="additembtn" class="btn btn-success btn-sm" onclick="addoverhead()">
					<i class="fa-solid fa-plus"></i>&nbsp;&nbsp;ADD &nbsp;<i class="fa-solid fa-user"></i>
				</button>
			</td>
			<td style="text-align: right; width: 100%;">
				<button id="posttosummary" class="btn btn-primary btn-sm" onclick="posttosummary('<?= $overheadcount?>')">
					<i class="fa fa-clipboard" aria-hidden="true"></i>&nbsp;&nbsp;Post To Summary
				</button>
			</td>
		<?php else: ?>
			<td colspan="2" style="text-align: center; padding: 10px;">
				<span style="color: gray; font-weight: bold;">
					<i class="fa fa-check-circle"></i> Overhead data has already been posted.
				</span>
			</td>
		<?php endif; ?>
	</tr>
</table>

<div id="result"></div>

<script>


function posttosummary(overheadcount) {
    var mode = 'posttosummaryoverhead';

    $.post("./actions/brrr_actions.php", { mode: mode, overheadcount: overheadcount }, function(data) {
        $("#result").html(data);
        set_function('Overhead', 'overheadcount');
    }).fail(function(xhr, status, error) {
        app_alert("Error", "Failed to post to summary. Please try again.", "error");
        console.error("POST failed:", status, error);
    });
}


function addoverhead()
{	
	
	$('#additem_title').html("ADD OVERHEAD");
	$.post("./apps/add_overhead_form.php", {  },
	function(data) {
		$("#additem_page").html(data);
		$('#additem').fadeIn();
	});
	
}

</script>
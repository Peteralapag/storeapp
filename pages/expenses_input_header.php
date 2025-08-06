<?php
include '../init.php';
require '../class/brrr.class.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$brrr = new brrr;

$branch = $brrr->GetSession('branch');
$reportdate = $brrr->GetSession('branchdate');

$expenseinputstatus = $brrr->expenseinputstatus($branch, $reportdate, $db);
$expenseinputexisting = $brrr->expenseinputexisting($branch, $reportdate, $db);
?>

<table style="width: 100%; border-collapse: collapse; white-space: nowrap;" cellpadding="0" cellspacing="0">
	<tr>
		
		
		
		<?php
		if ($expenseinputstatus == 1) {
		    // Case 1: Already posted
		    echo '<td colspan="2" style="text-align: center; padding: 10px;">
		            <span style="color: gray; font-weight: bold;">
		                <i class="fa fa-check-circle"></i> Branch Expense data has already been posted.
		            </span>
		          </td>';
		} elseif ($expenseinputexisting == 1) {
		    // Case 2: Existing data, but not yet posted
		    echo '<td style="text-align: right; width: 100%;">
		            <button id="posttosummary" class="btn btn-primary btn-sm" onclick="posttosummary()">
		                <i class="fa fa-clipboard" aria-hidden="true"></i>&nbsp;&nbsp;Post To Summary
		            </button>
		          </td>';
		} else {
		    // Case 3: No data at all
		    echo '<td colspan="2" style="text-align: center; padding: 10px;"></td>';
		}
		?>
		


	</tr>
</table>

<div id="result"></div>

<script>
function posttosummary() {
    var mode = 'posttosummaryexpenseinput';

    $.post("./actions/brrr_actions.php", { mode: mode }, function(data) {
        $("#result").html(data);
        set_function('Expense Input', 'expenses_input');
    }).fail(function(xhr, status, error) {
        app_alert("Error", "Failed to post to summary. Please try again.", "error");
        console.error("POST failed:", status, error);
    });
}
</script>

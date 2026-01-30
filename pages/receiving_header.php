<?php
include '../init.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$functions = new TheFunctions;
$file_name = $_POST['pagename'];
$title = strtoupper($file_name);

$functions = new TheFunctions;
$branch = $functions->AppBranch();
$transdate = $functions->GetSession('branchdate');
$shift = $functions->GetSession('shift');
$table = "store_".$file_name."_data";

$summary_btn = $functions->detechPostedData($table,$branch,$transdate,$db) === 1? '': 'disabled';
$tablePosted = $functions->tableDataCheckingForPosted($table,$branch,$transdate,$shift,$db);

?>

<table style="width: 100%;border-collapse:collapse;white-space:nowrap" cellpadding="0" cellspacing="0">
    <tr>
        <td style="width:150px">
            <button id="syndata" class="btn btn-success btn-sm" onclick="syncreceiveddata()">
                <i class="fa-solid fa-plus"></i>&nbsp;&nbsp;Sync Received
            </button>
        </td>
        <td style="text-align:right">
            <button id="previewdatabtn" class="btn btn-primary btn-sm" onclick="previewData()">Preview Data</button>
        </td>
    </tr>
</table>
<div class="Results"></div>


<script>

$(document).ready(function(){
	var statBut = '<?php echo $tablePosted?>';
	if(statBut == 1){
		$("#syndata").hide();
	} 
});

function syncreceiveddata() {
    psaSpinnerOn(); // show loading spinner

    $.ajax({
        url: './actions/sync_received_local.php',
        type: 'POST',
        data: { 
            branch: '<?php echo $branch; ?>', 
            report_date: '<?php echo $transdate; ?>', 
            shift: '<?php echo $shift; ?>' 
        },
        success: function(response){
            // Show the actual server response
            if(response.trim() === "") {
                app_alert('System Message', 'No received items found or nothing to sync.', 'info');
            } else {
                // You can show the detailed insert/update logs from PHP
                app_alert('System Message', response, 'success');
            }
            psaSpinnerOff();
        },
        error: function(xhr, status, error){
            console.error("AJAX Error:", status, error, xhr.responseText);
            app_alert('System Message', 'An error occurred while syncing received data.', 'error');
            psaSpinnerOff();
        }
    });
}




</script>


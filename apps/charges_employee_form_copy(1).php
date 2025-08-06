<style>
.spanpointer{
	cursor:pointer;
}
</style>
<?php
include '../init.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$functions = new TheFunctions;
$dropdown = new DropDowns;
$branch = $functions->AppBranch();
$report_date = $functions->GetSession('branchdate');
$shift = $functions->GetSession('shift');

$itemname = $_POST['itemname'];
$itemid = $_POST['itemid']; 
@$empname = $_POST['empname'];
@$empidcode = $_POST['empidcode'];
?>


<table style="width: 100%; top:0" class="tables" cellpadding="4" cellspacing="5">
	<tr>
		<td><input id="branch" type="text" class="form-control form-input" value="<?php echo $branch; ?>" disabled></td>

		<td><input id="date" type="text" class="form-control form-input" value="<?php echo $report_date; ?>" disabled></td>

		<td><input id="shift" type="text" class="form-control form-input" value="<?php echo $shift; ?>" disabled></td>

	</tr>
	<tr>
		<td colspan="2"><input id="itemname" type="text" class="form-control form-input" value="<?php echo $itemname; ?>" disabled></td>
		<td><input id="itemid" type="text" class="form-control form-input" value="<?php echo $itemid; ?>" disabled></td>
	</tr>
	<tr>
		<td colspan="2" style="text-align:center">Employee Name</td>
		<td style="text-align:center">IDCODE</td>
	</tr>
	
	<!----AUTO---->
	
	<tr id="autoselectemployeediv">
		<td colspan="2">
			<select class="form-control" id="employeenameauto">
				<?php echo $dropdown->selectEmployeeViaBranch($branch,$db);?>
			</select>
		</td>
		<td><input id="idcodeauto" type="text" class="form-control form-input" value="<?php echo $empidcode?>" autocomplete="off" disabled></td>
	</tr>
	
	<!----MANUAL---->	
	
	<tr id="manualselectemployeediv" style="display:none">
		<td colspan="2">
			<input id="employeenamemanual" type="text" class="form-control form-input" value="<?php echo $empname?>" placeholder="Enter Employee Name" autocomplete="off">
		</td>
		<td><input id="idcodemanual" type="text" class="form-control form-input" value="<?php echo $empidcode?>" placeholder="Enter IDCODE" autocomplete="off"></td>
	</tr>
		

</table>


<div class="alert alert-warning" id="notificationsmanual" style="display:none">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    Let's ensure that the ID code and employee name are accurately entered to prevent any data discrepancies.
</div>



<div class="alert alert-success" id="notificationsauto">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    If you can't find the name of the employee, you have the option to encode it manually. Click <span style="color:red" class="spanpointer" onclick="manualencoding()">here</span> to proceed with that.
</div>


<div style="float:right">
	<button class="btn btn-success btnnew" id="btnauto" onclick="savechargesemployeeauto()"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Add Employee</button>
	<button class="btn btn-warning btnnew" id="btnmanual" style="display:none" onclick="savechargesemployeemanual()"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Add Employee Manual</button>
</div>


<div id="results"></div>
<script>


$(document).ready(function() {
	$('#employeenameauto').change(function() {
        var selectedEmployee = $(this).val();
		$('#idcodeauto').val(selectedEmployee);
    });
});


function manualencoding(){
	app_confirm('System Message','Are you certain about manually adding an employee?','warning','yesmanualinputname','','')
}
function yesmanualinputname(){
	$('#btnmanual').show();
	$('#notificationsmanual').show();
	$('#manualselectemployeediv').show();
	
	$('#btnauto').hide();
	$('#notificationsauto').hide();
	$('#autoselectemployeediv').hide();
}
function savechargesemployeeauto(){ 

	var mode = 'savechargesemployeeauto_new';
	var itemname = $('#itemname').val();
	var itemid = $('#itemid').val();
	
	var branch = $('#branch').val();
	var reportdate = $('#date').val();
	var shift = $('#shift').val();
	
	var idcode = $('#idcodeauto').val();
	
	$.post("./actions/actions.php", { mode: mode, itemname: itemname, itemid: itemid, idcode: idcode, branch: branch, reportdate: reportdate, shift: shift },
	function(data) {
		$("#results").html(data);
	});

}
function savechargesemployeemanual(){

	var mode = 'savechargesemployeemanual_new';
	var itemname = $('#itemname').val();
	var itemid = $('#itemid').val();
	
	var branch = $('#branch').val();
	var reportdate = $('#date').val();
	var shift = $('#shift').val();
		
	var employeename = $('#employeenamemanual').val();
	var idcode = $('#idcodemanual').val();
	
	if (!/^\d{8}$/.test(idcode)) {
        app_alert('System Message','IDCODE is not valid.','warning');
        return false;
    }
	
	$.post("./actions/actions.php", { mode: mode, itemname: itemname, itemid: itemid, employeename: employeename, idcode: idcode, branch: branch, reportdate: reportdate, shift: shift },
	function(data) {
		$("#results").html(data);
	});

}

</script>

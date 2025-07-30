<?php
include '../init.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$functions = new TheFunctions;
$dropdown = new DropDowns;
$branch = $functions->AppBranch();
$transmode = $_POST['transmode'];
$file_name = $_POST['file_name'];
$table = "store_".$file_name."_data";

if($transmode == 'edit')
{
	$rowid = $_POST['rowid'];
	$report_date = $functions->GetEditInfo($table,$rowid,'report_date',$db);
	$shift = $functions->GetEditInfo($table,$rowid,'shift',$db);
	$transfer_to = $functions->GetEditInfo($table,$rowid,'transfer_to',$db);
	$time_covered = $functions->GetEditInfo($table,$rowid,'time_covered',$db);
	$person = $functions->GetEditInfo($table,$rowid,'employee_name',$db);
	$category = $functions->GetEditInfo($table,$rowid,'category',$db);
	$item_id = $functions->GetEditInfo($table,$rowid,'item_id',$db);
	$item_name = $functions->GetEditInfo($table,$rowid,'item_name',$db);
	$weight  = $functions->GetEditInfo($table,$rowid,'weight',$db);
	$units  = $functions->GetEditInfo($table,$rowid,'units',$db);
}
if($transmode == 'new')
{
	$rowid = '';
	$report_date = $functions->GetSession('branchdate');
	$shift = $functions->GetSession('shift');
	if(isset($_SESSION['session_timecovered'])) { $time_covered = $_SESSION['session_timecovered']; } else { $time_covered = ''; }
	if(isset($_SESSION['session_person'])) { $person = $_SESSION['session_person']; } else { $person = ''; }
	if(isset($_SESSION['session_slipno'])) { $slipno = $_SESSION['session_slipno']; } else { $slipno = ''; }
	if(isset($_SESSION['session_time'])) { $time = $_SESSION['session_time']; } else { $time = ''; }
	if(isset($_SESSION['session_category'])) { $category = $_SESSION['session_category']; } else { $category = ''; }
	$rowid = '';
	$transfer_to = '';
	$item_id = '';
	$item_name = '';
	$weight = '0.00';
	$units = '0.00';
}
?>
<table style="width: 100%" class="tables" cellpadding="4" cellspacing="5">
	<tr>
		<th style="width:29.5%" id="branchtext_a">BRANCH</th>
		<th style="width:10px">&nbsp;</th>
		<th style="width:23.5%">DATE</th>
		<th style="width:10px">&nbsp;</th>
		<th style="width:23.5%">SHIFT</th>
		<th style="width:10px">&nbsp;</th>
		<th style="width:23.5%">TRANSFER MODE</th>
	</tr>
	<tr>
		<td id="formmode_a">&nbsp;</td>
		<td></td>
		<td>
			<input id="rowid" type="hidden" value="<?php echo $rowid; ?>">
			<input id="date" type="text" class="form-control form-input" value="<?php echo $report_date; ?>" disabled>
		</td>
		<td></td>
		<td><input id="shift" type="text" class="form-control form-input" value="<?php echo $shift; ?>" disabled></td>
		<td></td>
		<td>
			<select id="transfermode" class="form-control" onchange="transferMode(this.value)">
				<?php echo $functions->GetTransferMode(''); ?>
			</select>
		</td>
	</tr>
</table>
<table style="width: 100%" class="tables" cellpadding="4" cellspacing="5">
	<tr>
		<th style="width: 24%">TIME COVERED</th>
		<th style="width: 10px">&nbsp;</th>
		<th style="width: 24%">CASHIER / RELIEVER</th>
		<th style="width: 10px">&nbsp;</th>
		<th style="width: 24%">ENCODER</th>
		<th style="width: 10px">&nbsp;</th>
		<th style="width: 24%" id="branchtext_b">SEND TO BRANCH&nbsp;</th>
	</tr>
	<tr>
		<td style="width: 24%">
			<select id="timecovered" class="form-control" onchange="set_session(this.value,'session_timecovered')" name="D1">
				<?php echo $dropdown->GetTimeCovered($time_covered,$db); ?>
			</select></td>
		<td style="width: 10px">&nbsp;</td>
		<td style="width: 24%">
			<input list="employee" id="person" class="form-control" placeholder="Select Baker" autocomplete="no" value="<?php echo $person; ?>" onchange="set_session(this.value,'session_person')"></td>
		<td style="width: 10px">&nbsp;</td>
		<td style="width: 24%"><input id="encoder" type="text" class="form-control form-input" value="<?php echo $functions->GetSession('encoder'); ?>" disabled></td>
		<td style="width: 10px">&nbsp;</td>
		<td style="width: 24%" id="formmode_b"></td>
	</tr>
	<tr>
		<th style="width: 24%">CATEGORY&nbsp;</th>
		<th style="width: 10px">&nbsp;</th>
		<th style="width: 24%">ITEM NAME</th>
		<th style="width: 10px">&nbsp;</th>
		<th style="width: 24%">WEIGHT</th>
		<th style="width: 10px">&nbsp;</th>
		<th style="width: 24%">UNITS OF MEASURE</th>
	</tr>
	<tr>
		<td style="width: 24%">
			<!-- select id="category" class="form-control"onchange="set_session(this.value,'session_category')" name="D2">
				<?php echo $dropdown->ItemCategory($category); ?>
			</select -->
			<input id="category" type="text" class="form-control" value="SUPPLIES" disabled>
		</td>		
		<td style="width: 10px">&nbsp;</td>
		<td style="width: 24%">
			<input id="rowid" type="hidden" value="<?php echo $rowid; ?>">
			<input list="items" id="itemname" class="form-control" placeholder="Select Items" autocomplete="no" onkeyup="GetItems()" value="<?php echo $item_name; ?>">
			<datalist id="items"></datalist>
		</td>
		<td style="width: 10px">&nbsp;</td>
		<td style="width: 24%">
		<input id="weight" type="text" class="form-control form-input" value="<?php echo $weight; ?>" autocomplete="no"></td>
		<td style="width: 10px">&nbsp;</td>
		<td style="width: 24%">
			<select id="uom" class="form-control">
				<?php echo $dropdown->GetUOM($units); ?>
			</select>
		</td>
	</tr>
	<tr>
		<td style="width: 24%">&nbsp;</td>
		<td style="width: 10px">&nbsp;</td>
		<td style="width: 24%">&nbsp;</td>
		<td style="width: 10px">&nbsp;</td>
		<td colspan="3" style="width: 24%">&nbsp;</td>		
	</tr>
	<tr>
		<td style="width: 24%">ITEM ID: </td>
		<td style="width: 10px">&nbsp;</td>
		<td style="width: 24%">
		<input style="text-align:center;color:red;font-weight:bold" type="text" id="itemid" class="form-control" value="<?php echo $item_id; ?>" placeholder="Item ID" disabled>

			<?php if($transmode == 'edit') { ?>
			<!--button id="ttb" class="btn btn-success" style="width: auto" onclick="transferToBranch('<?php echo $rowid; ?>')">Send to <?php echo $transfer_to; ?></button-->
			<?php }?>		
		</td>
		<td style="width: 10px">&nbsp;</td>
		<td colspan="3" style="width: 24%;text-align:right">
			<input type="hidden" id="itemid" value="<?php echo $item_id; ?>">
			<?php if($transmode == 'new') { ?>
			<button class="btn btn-success btnnew" onclick="commitAction('new')">Save Transfer</button>
			<?php } if($transmode == 'edit') { ?>
			<button class="btn btn-info btnupdate" onclick="commitAction('edit')">Update Transfer</button>
			<?php } ?>
			<button class="btn btn-danger btnclose" onclick="closeDialoque('additem')">Close</button>
		</td>		
	</tr>
</table>
<div class="results"></div>
<script>
function transferToBranch(rowid)
{
	var rowid = $('#rowid').val();
	var branch = $('#branch').val();
	var to_branch = $('#to_branch').val();
	var date = $('#date').val();
	var shift = $('#shift').val();
	var timecovered = $('#timecovered').val();
	var person = $('#person').val();
	var encoder = $('#encoder').val();
	var category = $('#category').val();
	var item_id = $('#itemid').val();
	var itemname = $('#itemname').val();
	var weight = $('#weight').val();
	var units = $('#uom').val();
	var transfermode = $('#transfermode').val();
	
	if(branch === '') { app_alert("Branch Error","Your Branch does not exists, please set it up first.","warning","Ok","","focus"); return false; } 
	else if(to_branch === '') { app_alert("Branch Error","Please select Recipient Branch","warning","Ok","to_branch","focus"); return false; } 
	else if(date === '') { app_alert("Date Error","Please select date.","warning","Ok","","focus"); return false; } 
	else if(shift === '') { app_alert("Shift Error","Please select date.","warning","Ok","","focus"); return false; } 
	else if(person === '') { app_alert("Cashier Error","Please select Cashier.","warning","Ok","person","focus"); return false; } 
	else if(encoder === '') { app_alert("Encoder Error","Please Relogin.","warning","Ok","encoder","relogin"); return false; } 
	else if(category === '') { app_alert("Category Error","Please select Category.","warning","Ok","category","focus"); return false; }
	else if(itemname === '') { app_alert("Item Name Error","Please select Item.","warning","Ok","itemname","focus"); return false; }
	else if(weight <= parseFloat('0.00')) { app_alert("Weight Error","Please enter Weight.","warning","Ok","weight","focus"); return false; }
	else if(units == '') { app_alert("Units Error","Please select Unit Of Measure","warning","Ok","units","focus"); return false; }
	else if(timecovered === '') { app_alert("Time Covered Error","Please select Time Coverage.","warning","Ok","timecovered","focus"); return false; } 

	var mode = 'suppliestransfertobranch';
	$('.btnupdate,.btnclose,#ttb').attr('disabled', true);
	$('#ttb').html('Sending to branch... <i class="fa fa-spinner fa-spin"></i>');
	$('#notification').show();
	$('#notification').html("Do not close while saving data...");
	
	setTimeout(function()
	{
		$.post("./actions/actions.php", { 
			mode: mode,
			rowid: rowid,			
			branch: branch,
			to_branch: to_branch,
			date: date,
			shift: shift,
			timecovered: timecovered,
			person: person,
			encoder: encoder,
			category: category,
			item_id: item_id,
			itemname: itemname,
			weight: weight,
			units: units,
			transfermode: transfermode
		},
		function(data) {
			$('.results').html(data);
			$('#notification').hide();
		//	$('#' + sessionStorage.navcount).trigger('click');
		});
	},1000);
}
function transferMode(params)
{
	var $obja = '<select id="to_branch" class="form-control">';
	var $objb = '<?php echo $functions->GetBranch($transfer_to,$db); ?>';
	var $objc = '</select>';
	var $obj = $obja  + "" + $objb  + "" + $objc;
	
	var $my_obj = '<input id="branch" type="text" class="form-control form-input" value="<?php echo $branch; ?>" disabled>';
	if(params == 'TRANSFER IN')
	{
		$('#formmode_a').html($obj);
		$('#branchtext_a').html("FROM BRANCH");
		$('#formmode_b').html($my_obj);
		$('#branchtext_b').html("TO MY BRANCH");
	}
	if(params == 'TRANSFER OUT')
	{
		$('#formmode_a').html($my_obj);
		$('#branchtext_a').html("BRANCH");
		$('#formmode_b').html($obj);
		$('#branchtext_b').html("SEND TO BRANCH");
	}
}
$(function()
{
	transferMode('TRANSFER OUT');
});
function commitAction(transmode)
{
	var rowid = $('#rowid').val();
	var branch = $('#branch').val();
	var to_branch = $('#to_branch').val();
	var date = $('#date').val();
	var shift = $('#shift').val();
	var timecovered = $('#timecovered').val();
	var person = $('#person').val();
	var encoder = $('#encoder').val();
	var category = $('#category').val();
	var item_id = $('#itemid').val();
	var itemname = $('#itemname').val();
	var weight = $('#weight').val();
	var units = $('#uom').val();
	var transfermode = $('#transfermode').val();
	
	if(branch === '') { app_alert("Branch Error","Your Branch does not exists, please set it up first.","warning","Ok","","focus"); return false; } 
	else if(to_branch === '') { app_alert("Branch Error","Please select Recipient Branch","warning","Ok","to_branch","focus"); return false; } 
	else if(date === '') { app_alert("Date Error","Please select date.","warning","Ok","","focus"); return false; } 
	else if(shift === '') { app_alert("Shift Error","Please select date.","warning","Ok","","focus"); return false; } 
	else if(person === '') { app_alert("Cashier Error","Please select Cashier.","warning","Ok","person","focus"); return false; } 
	else if(encoder === '') { app_alert("Encoder Error","Please Relogin.","warning","Ok","encoder","relogin"); return false; } 
	else if(category === '') { app_alert("Category Error","Please select Category.","warning","Ok","category","focus"); return false; }
	else if(itemname === '') { app_alert("Item Name Error","Please select Item.","warning","Ok","itemname","focus"); return false; }
	else if(weight <= parseFloat('0.00')) { app_alert("Weight Error","Please enter Weight.","warning","Ok","weight","focus"); return false; }
	else if(units == '') { app_alert("Units Error","Please select Unit Of Measure","warning","Ok","units","focus"); return false; }
	else if(timecovered === '') { app_alert("Time Covered Error","Please select Time Coverage.","warning","Ok","timecovered","focus"); return false; } 
	
	if(transmode === 'new')
	{ 
		var mode = 'savesuppliestransfer'; 
		// $('.btnnew,.btnclose').attr('disabled', true);
		$('.btnnew').html('Saving <i class="fa fa-spinner fa-spin"></i>'); 
		$('#notification').show();
		$('#notification').html("Do not close while saving data...");
	}
	else if(transmode === 'edit')
	{ 
		var mode = 'updatesuppliestransfer';
		$('.btnupdate,.btnclose').attr('disabled', true);
		$('.btnupdate').html('Updating <i class="fa fa-spinner fa-spin"></i>');
		$('#notification').show();
		$('#notification').html("Do not close while updating data...");
	}
	setTimeout(function()
	{
		$.post("./actions/actions.php", { 
			mode: mode,
			rowid: rowid,			
			branch: branch,
			to_branch: to_branch,
			date: date,
			shift: shift,
			timecovered: timecovered,
			person: person,
			encoder: encoder,
			category: category,
			item_id: item_id,
			itemname: itemname,
			weight: weight,
			units: units,
			transfermode: transfermode
		},
		function(data) {
			$('.results').html(data);
			$('#notification').hide();
	//		$('#' + sessionStorage.navcount).trigger('click');
		});
	},1000);
}
function calculate()
{

}
</script>
<script src="scripts/form_scripts.js"></script>

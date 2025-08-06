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
	$time_covered = $functions->GetEditInfo($table,$rowid,'time_covered',$db);
	$person = $functions->GetEditInfo($table,$rowid,'employee_name',$db);
	$slipno = $functions->GetEditInfo($table,$rowid,'slip_no',$db);
	$category = $functions->GetEditInfo($table,$rowid,'category',$db);
	$item_name = $functions->GetEditInfo($table,$rowid,'item_name',$db);
	$item_id = $functions->GetEditInfo($table,$rowid,'item_id',$db);
	$quantity  = $functions->GetEditInfo($table,$rowid,'quantity',$db);
	$units  = $functions->GetEditInfo($table,$rowid,'units',$db);
	$prefix = $functions->GetEditInfo($table,$rowid,'supp_prefix',$db);
	$supplier = $functions->GetEditInfo($table,$rowid,'supplier',$db);
	$invdr = $functions->GetEditInfo($table,$rowid,'invdr_no',$db);
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

	$item_name = '';
	$unit_price = '0.00';
	$item_id = '';
	$quantity = '1';
	$units = '';
	$prefix = '';
	$invdr = '';
	$supplier = '';
}
?>
<style>
.notification {
	display: none;
	padding:5px;
	border: 1px solid orange;
	text-align:center;
	border-radius:5px;
}
</style>
<table style="width: 100%; top:0" class="tables" cellpadding="4" cellspacing="5">
	<tr>
		<th style="width:32%">BRANCH</th>
		<th style="width:10px">&nbsp;</th>
		<th style="width:22%">DATE</th>
		<th style="width:10px">&nbsp;</th>
		<th style="width:22%">SHIFT</th>
		<th style="width:10px">&nbsp;</th>
		<th style="width:22%">FORM No.</th>
	</tr>
	<tr>
		<td><input id="branch" type="text" class="form-control form-input" value="<?php echo $branch; ?>" disabled></td>
		<td></td>
		<td><input id="date" type="text" class="form-control form-input" value="<?php echo $report_date; ?>" disabled></td>
		<td></td>
		<td><input id="shift" type="text" class="form-control form-input" value="<?php echo $shift; ?>" disabled></td>
		<td></td>
		<td>
			<input id="slipno" type="text" class="form-control form-input" placeholder="Form/Slip No." value="<?php echo $slipno; ?>" autocomplete="no"onchange="set_session(this.value,'session_slipno')">			
		</td>
	</tr>
</table>
<table style="width: 100%" class="tables" cellpadding="4" cellspacing="15">
	<tr>
		<th style="width:200px">TIME COVERED</th>
		<th style="width:10px;"></th>
		<th>CASHIER / RELIEVER</th>
		<th style="width:10px;"></th>
		<tH>ENCODER</th>
		<th style="width:10px;"></th>
		<th style="width:150px">CATEGORY</th>
	</tr>
	<tr>
		<td>
			<input id="rowid" type="hidden" value="<?php echo $rowid; ?>">
			<select id="timecovered" class="form-control" onchange="set_session(this.value,'session_timecovered')">
				<?php echo $dropdown->GetTimeCovered($time_covered,$db); ?>
			</select>
		</td>
		<td style="width:10px;"></td>
		<td>
			<input list="employee" id="person" class="form-control" placeholder="Select Baker" autocomplete="no" value="<?php echo $person; ?>" onchange="set_session(this.value,'session_person')">
			<datalist id="employee">
				<?php echo $dropdown->GetPerson($branch,'baker',$person,$db); ?>
			</datalist>			
		</td>
		<td style="width:10px;"></td>
		<td><input id="encoder" type="text" class="form-control form-input" value="<?php echo $functions->GetSession('encoder'); ?>" disabled></td>
		<td style="width:10px;"></td>
		<td>
			<!-- select id="category" class="form-control"onchange="set_session(this.value,'session_category')">
				<?php echo $dropdown->ItemCategory($category); ?>
			</select -->
			<input id="category" type="text" class="form-control" value="SUPPLIES" disabled>
		</td>			
	</tr>	
	<tr>
		<th>ITEM NAME</th>
		<th style="width:10px;"></th>
		<th>WEIGHT&nbsp;</th>
		<th style="width:10px;"></th>
		<tH>UNIT OF MEASURE</th>
		<th style="width:10px;"></th>
		<th>SUPP. PREFIX</th>
	</tr>
	<tr>
		<td>
			<input list="items" id="itemname" class="form-control" placeholder="Select Items" autocomplete="off" onkeyup="GetItems()" value="<?php echo $item_name; ?>">
			<datalist id="items"></datalist>
		</td>
		<td style="width:10px;"></td>
		<td>
			<input id="quantity" type="text" class="form-control form-input" value="<?php echo $quantity; ?>" autocomplete="no">
		</td>
		<td style="width:10px;"></td>
		<td>
			<select id="uom" class="form-control">
				<?php echo $dropdown->GetUOM($units); ?>
			</select>
		<td style="width:10px;"></td>
		<td>
			<select id="suppprefix" class="form-control">
				<?php echo $dropdown->GetPrefix($prefix); ?>
			</select></td>
	</tr>
	<tr>
		<th>SUPPLIER&nbsp;</th>
		<th style="width:10px;"></th>
		<th>INV / DR No.</th>
		<th style="width:10px;"></th>
		<th colspan="3" style="text-align:center"></th>
	</tr>	
	<tr>
		<td>
			<input id="supplier" type="text" class="form-control form-input" placeholder="Supplier" value="<?php echo $supplier; ?>">
		</td>
		<td style="width:10px;"></td>
		<td>
			<input id="invdr" type="text" class="form-control form-input" placeholder="Invoice or DR No." value="<?php echo $invdr; ?>" autocomplete="no"></td>
		<td style="width:10px;"></td>
		<td colspan="3" style="text-align:right">
			<input type="hidden" id="itemid" value="<?php echo $item_id; ?>">
			<?php if($transmode == 'new') { ?>
			<button class="btn btn-success btnnew" onclick="commitAction('new')" disabled>Save Receiving</button>
			<?php } if($transmode == 'edit') { ?>
			<button class="btn btn-info btnupdate" onclick="commitAction('edit')" style="width: auto">Update Receiving</button>
			<?php } ?>
			<button class="btn btn-danger btnclose" onclick="closeDialoque('additem')">Close</button>
		</td>
	</tr>		
</table>
<script>
function commitAction(transmode)
{
	if($('#itemid').val() === '')
	{
		swal("Error","The Item Name you are trying to encode does not exists in the database. Please try again selecting what is in the dropdown list","warning");
		$('.btnnew').prop('disabled', true);
		return false;
	}
	var rowid = $('#rowid').val();
	var branch = $('#branch').val();
	var date = $('#date').val();
	var shift = $('#shift').val();
	var timecovered = $('#timecovered').val();
	var person = $('#person').val();
	var encoder = $('#encoder').val();
	var slipno = $('#slipno').val();
	var time = $('#time').val();
	var category = $('#category').val();
	var item_id = $('#itemid').val();
	var itemname = $('#itemname').val();
	
	var quantity = $('#quantity').val();
	var uom = $('#uom').val();
	var suppprefix = $('#suppprefix').val();
	var supplier = $('#supplier').val();
	var invdr = $('#invdr').val();
	
	if(branch === '') { app_alert("Branch Error","Your Branch does not exists, please set it up first.","warning","Ok","","focus"); return false; } 
	else if(date === '') { app_alert("Date Error","Please select date.","warning","Ok","","focus"); return false; } 
	else if(shift === '') { app_alert("Shift Error","Please select date.","warning","Ok","","focus"); return false; } 
	else if(timecovered === '') { app_alert("Time Covered Error","Please select Time Coverage.","warning","Ok","timecovered","focus"); return false; } 
	else if(person === '') { app_alert("Baker Error","Please select Cashier.","warning","Ok","person","focus"); return false; } 
	else if(encoder === '') { app_alert("Encoder Error","Please Relogin.","warning","Ok","encoder","relogin"); return false; } 
	else if(slipno === '') { app_alert("Slip No. Error","Please enter Slip Number or Form Number.","warning","Ok","slipno","focus"); return false; } 
	else if(time === '') { app_alert("Time Error","Please select Time.","warning","Ok","time","focus"); return false; }
	else if(category === '') { app_alert("Category Error","Please select Category.","warning","Ok","category","focus"); return false; }
	else if(itemname === '') { app_alert("Item Name Error","Please select Item.","warning","Ok","itemname","focus"); return false; }
	else if(quantity <= '0' || quantity == '0.00') { app_alert("Quantity Error","Quantity cannot be empty or equal to zero.","warning","Ok","quantity","focus"); return false; }
	else if(uom == '') { app_alert("Unit of Measure Error","Please select Unit Of Measure.","warning","Ok","uom","focus"); return false; }
	else if(suppprefix == '') { app_alert("Supplier Prefix Error","Please Select Supplier Prefix","warning","Ok","suppprefix","focus"); return false; }
	else if(supplier == '') { app_alert("Supplier Error","Please Enter Supplier","warning","Ok","unit_price","focus"); return false; }
	// else { app_alert("Unknown Error","There's and unrecognizable error Please contact system Administrator","Ok","unit_price","focus"); return false; } 
	
	if(transmode === 'new')
	{ 
		var mode = 'savesuppreceiving'; 
		$('.btnnew,.btnclose').attr('disabled', true);
		$('.btnnew').html('Saving <i class="fa fa-spinner fa-spin"></i>'); 
		$('#notification').show();
		$('#notification').html('Do not close while saving data... <i class="fa fa-spinner fa-spin">');
	}
	else if(transmode === 'edit')
	{ 
		var mode = 'updatesuppreceiving';
		$('.btnupdate,.btnclose').attr('disabled', true);
		$('.btnupdate').html('Updating <i class="fa fa-spinner fa-spin"></i>');
		$('#notification').show();
		$('#notification').html('Do not close while updating data... <i class="fa fa-spinner fa-spin">');
	}
	setTimeout(function()
	{
		$.post("./actions/actions.php", { 
			mode: mode,
			rowid: rowid,			
			branch: branch,
			date: date,
			shift: shift,
			timecovered: timecovered,
			person: person,
			encoder: encoder,
			slipno: slipno,
			time: time,
			category: category,
			item_id: item_id,
			itemname : itemname,
			quantity: quantity,
			uom: uom,
			suppprefix: suppprefix,
			supplier: supplier,
			invdr: invdr
		},
		function(data) {
			$('.results').html(data);
			$('#notification').hide();
			$('#' + sessionStorage.navcount).trigger('click');
		});
	},1000);
}
function calculate()
{
	var kilo_used = $('#kilo_used').val();
	var yield = $('#standard_yield').val();
	$('#actual_yield').val(kilo_used * yield);
}
</script>
<script src="scripts/form_scripts.js"></script>

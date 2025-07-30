<?php
include '../init.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$functions = new TheFunctions;
$dropdown = new DropDowns;
$branch = utf8_encode($functions->AppBranch());
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
	$quantity  = $functions->GetEditInfo($table,$rowid,'quantity',$db);
	$amount  = $functions->GetEditInfo($table,$rowid,'amount',$db);
	$unit_price  = $functions->GetEditInfo($table,$rowid,'unit_price',$db);
	echo '<script>document.getElementById("category").disabled = true; document.getElementById("itemname").disabled = true;</script>';
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

	$transfer_to = '';
	$item_name = '';
	$unit_price = '0.00';
	$quantity = '0.00';
	$amount = '0.00';
	$item_id = '';
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
		<td><input id="date" type="text" class="form-control form-input" value="<?php echo $report_date; ?>" disabled></td>
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
<table style="width: 100%" class="tables" cellpadding="4" cellspacing="15">
	<tr>
		<th style="width:200px">CASHIER / RELIEVER</th>
		<th style="width:10px;"></th>
		<th>ENCODER</th>
		<th style="width:10px;"></th>
		<th id="branchtext_b">SEND TO BRANCH&nbsp;&nbsp;</th>
		<th style="width:10px;"></th>
		<th style="width:">CATEGORY</th>
	</tr>
	<tr>
		<td>
			<input list="employee" id="person" class="form-control" placeholder="Select Baker" autocomplete="no" value="<?php echo $person; ?>" onchange="set_session(this.value,'session_person')">
			<datalist id="employee">
				<?php echo $dropdown->GetPerson($branch,'baker',$person,$db); ?>
			</datalist>			</td>
		<td style="width:10px;"></td>
		<td><input id="encoder" type="text" class="form-control form-input" value="<?php echo $functions->GetSession('encoder'); ?>" disabled></td>
		<td style="width:10px;"></td>
		<td id="formmode_b">
			
		</td>
		<td style="width:10px;"></td>
		<td>
			<select id="category" class="form-control"onchange="set_session(this.value,'session_category')">
				<?php echo $dropdown->ItemCategory($category); ?>
			</select>
		</td>
	</tr>	
	<tr>
		<th>ITEM NAME</th>
		<th style="width:10px;"></th>
		<th>QUANTITY</th>
		<th style="width:10px;"></th>
		<tH>UNIT PRICE&nbsp;&nbsp;</th>
		<th style="width:10px;"></th>
		<th>AMOUNT</th>
	</tr>
	<tr>
		<td>
			<input id="rowid" type="hidden" value="<?php echo $rowid; ?>">
			<input list="items" id="itemname" class="form-control" placeholder="Select Items" autocomplete="no" onkeyup="GetItems()" value="<?php echo $item_name; ?>">
			<datalist id="items"></datalist>
		</td>
		<td style="width:10px;"></td>
		<td>
			<input id="quantity" type="text" class="form-control form-input" value="<?php echo $quantity; ?>" autocomplete="no">
		</td>
		<td style="width:10px;"></td>
		<td>
			<input id="unit_price" type="text" class="form-control form-input" placeholder="Actual Yield" value="<?php echo $unit_price; ?>" autocomplete="no">
		</td>
		<td style="width:10px;"></td>
		<td>
			<input id="amount" type="text" class="form-control form-input" placeholder="0.00" value="<?php echo $amount; ?>" autocomplete="no" readonly>
		</td>
	</tr>
	<tr>
		<th>TIME COVERED</th>
		<td style="width:10px;">&nbsp;</td>
		<th style="text-align:center"><i class="fa-solid fa-circle-info" style="color:blue;font-size:16px"></i> ITEM ID</th>
		<td style="width:10px;">&nbsp;</td>
		<td colspan="3" style="text-align:right"></td>
	</tr>		
	<tr>
		<td>
			<select id="timecovered" class="form-control" onchange="set_session(this.value,'session_timecovered')">
				<?php echo $dropdown->GetTimeCovered($time_covered,$db); ?>
			</select>
		</td>
		<td style="width:10px;"></td>
		<td>
			<input style="text-align:center;color:red;font-weight:bold" type="text" id="itemid" class="form-control" value="<?php echo $item_id; ?>" placeholder="Item ID" disabled>
			<?php if($transmode == 'edit') { ?>
			<!-- button id="ttb" class="btn btn-success" style="width: auto" onclick="transferToBranch('<?php echo $rowid; ?>')">Send to <?php echo $transfer_to; ?></button -->
			<?php }?>
		</td>
		<td style="width:10px;"></td>
		<td colspan="3" style="text-align:right">
			<?php if($transmode == 'new') { ?>
			<button class="btn btn-success btnnew" onclick="commitAction('new')" disabled>Save Transfer</button>
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
	var person = $('#person').val();
	var encoder = $('#encoder').val();
	var category = $('#category').val();
	var item_id = $('#itemid').val();
	var itemname = $('#itemname').val();
	var quantity = $('#quantity').val();
	var unit_price = $('#unit_price').val();
	var amount = $('#amount').val();
	var timecovered = $('#timecovered').val();
	var transfermode = $('#transfermode').val();

	if(item_id === '') { app_alert("ITEM ID Error","We cannot add or update if ITEM ID is missing.","warning","Ok","","focus"); return false; } 	
	else if(branch === '') { app_alert("Branch Error","Your Branch does not exists, please set it up first.","warning","Ok","","focus"); return false; } 
	else if(to_branch === '') { app_alert("Branch Error","Please select Recipient Branch","warning","Ok","to_branch","focus"); return false; } 
	else if(date === '') { app_alert("Date Error","Please select date.","warning","Ok","","focus"); return false; } 
	else if(shift === '') { app_alert("Shift Error","Please select date.","warning","Ok","","focus"); return false; } 
	else if(person === '') { app_alert("Cashier Error","Please select Cashier.","warning","Ok","person","focus"); return false; } 
	else if(encoder === '') { app_alert("Encoder Error","Please Relogin.","warning","Ok","encoder","relogin"); return false; } 
	else if(category === '') { app_alert("Category Error","Please select Category.","warning","Ok","category","focus"); return false; }
	else if(itemname === '') { app_alert("Item Name Error","Please select Item.","warning","Ok","itemname","focus"); return false; }
	else if(quantity <= parseFloat('0.00')) { app_alert("Quantity Error","Quantity cannot be empty or equal to zero.","warning","Ok","quantity","focus"); return false; }
	else if(unit_price < parseFloat('0.00')) { app_alert("Unit Price Error","Unit Price cannot be empty. Put 0 if there is no price available","warning","Ok","unit_price","focus"); return false; }
	else if(amount <= parseFloat('0.00')) { app_alert("Amount Error","Amount cannot be empty or equal to zero.","warning","Ok","amount","focus"); return false; }
	else if(timecovered === '') { app_alert("Time Covered Error","Please select Time Coverage.","warning","Ok","timecovered","focus"); return false; } 

	var mode = 'transfertobranch';
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
			person: person,
			encoder: encoder,
			category: category,
			item_id: item_id,
			itemname: itemname,
			quantity: quantity,
			unit_price: unit_price,
			amount: amount,
			timecovered: timecovered,
			transfermode: transfermode
		},
		function(data) {
			$('.results').html(data);
			$('#notification').hide();
			$('#' + sessionStorage.navcount).trigger('click');
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
	$('#quantity,#unit_price').keyup(function(){
		calculate();
	});
	transferMode('TRANSFER OUT');
});
function commitAction(transmode)
{
	var rowid = $('#rowid').val();
	var branch = $('#branch').val();
	var to_branch = $('#to_branch').val();
	var date = $('#date').val();
	var shift = $('#shift').val();
	var person = $('#person').val();
	var encoder = $('#encoder').val();
	var category = $('#category').val();
	var item_id = $('#itemid').val();
	var itemname = $('#itemname').val();
	var quantity = $('#quantity').val();
	var unit_price = $('#unit_price').val();
	var amount = $('#amount').val();
	var timecovered = $('#timecovered').val();
	var transfermode = $('#transfermode').val();

	if(item_id === '') { app_alert("ITEM ID Error","We cannot add or update if ITEM ID is missing.","warning","Ok","","focus"); return false; } 	
	else if(branch === '') { app_alert("Branch Error","Your Branch does not exists, please set it up first.","warning","Ok","","focus"); return false; } 
	else if(to_branch === '') { app_alert("Branch Error","Please select Recipient Branch","warning","Ok","to_branch","focus"); return false; } 
	else if(date === '') { app_alert("Date Error","Please select date.","warning","Ok","","focus"); return false; } 
	else if(shift === '') { app_alert("Shift Error","Please select date.","warning","Ok","","focus"); return false; } 
	else if(person === '') { app_alert("Cashier Error","Please select Cashier.","warning","Ok","person","focus"); return false; } 
	else if(encoder === '') { app_alert("Encoder Error","Please Relogin.","warning","Ok","encoder","relogin"); return false; } 
	else if(category === '') { app_alert("Category Error","Please select Category.","warning","Ok","category","focus"); return false; }
	else if(itemname === '') { app_alert("Item Name Error","Please select Item.","warning","Ok","itemname","focus"); return false; }
	else if(quantity <= parseFloat('0.00')) { app_alert("Quantity Error","Quantity cannot be empty or equal to zero.","warning","Ok","quantity","focus"); return false; }
	else if(unit_price < parseFloat('0.00')) { app_alert("Unit Price Error","Unit Price cannot be empty. Put 0 if there is no price available","warning","Ok","unit_price","focus"); return false; }
	else if(amount <= parseFloat('0.00')) { app_alert("Amount Error","Amount cannot be empty or equal to zero.","warning","Ok","amount","focus"); return false; }
	else if(timecovered === '') { app_alert("Time Covered Error","Please select Time Coverage.","warning","Ok","timecovered","focus"); return false; } 

	if(transmode === 'new')
	{ 
		var mode = 'savetransfer'; 
		$('.btnnew,.btnclose').attr('disabled', true);
		$('.btnnew').html('Saving <i class="fa fa-spinner fa-spin"></i>'); 
		$('#notification').show();
		$('#notification').html("Do not close while saving data...");
	}
	else if(transmode === 'edit')
	{ 
		var mode = 'updatetransfer';
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
			person: person,
			encoder: encoder,
			category: category,
			item_id: item_id,
			itemname: itemname,
			quantity: quantity,
			unit_price: unit_price,
			amount: amount,
			timecovered: timecovered,
			transfermode: transfermode
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
	var quantity = $('#quantity').val();
	var unit_price = $('#unit_price').val();
	$('#amount').val(unit_price * quantity);
}
</script>
<script src="scripts/form_scripts.js"></script>

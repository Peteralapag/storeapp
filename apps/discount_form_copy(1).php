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
	$discount = $functions->GetEditInfo($table,$rowid,'discount',$db);
	$item_name = $functions->GetEditInfo($table,$rowid,'item_name',$db);
	
	
}
if($transmode == 'new')
{
	$rowid = '';
	$report_date = $functions->GetSession('branchdate');
	$shift = $functions->GetSession('shift');
	$discount = '0.00';
	$item_name = '';
	if(isset($_SESSION['session_discount'])) { $category = $_SESSION['session_discount']; } else { $category = ''; }

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
			<input id="slipnos" type="text" class="form-control form-input" placeholder="Not Applicable" disabled>			
		</td>
	</tr>
</table>
<table style="width: 100%" class="tables" cellpadding="4" cellspacing="15">
	<tr>
		<th style="width:200px">ENCODER&nbsp;</th>
		<th style="width:10px;"></th>
		<th style="width:200px">DISCOUNT TYPE</th>
		<th style="width:10px;"></th>
		<th style="width:200px">CATEGORY</th>
		<th style="width:10px;"></th>
		<th style="width:200px">ITEM NAME</th>
		<th style="width:10px;"></th>
		<th style="width:200px">DISCOUNT AMOUNT</th>
		<th style="width:10px;"></th>
	</tr>
	<tr>
		<td>
			<input id="encoder" type="text" class="form-control form-input" value="<?php echo $functions->GetSession('encoder'); ?>" disabled>
		</td>
		<td style="width:10px;"></td>
		<td>
			<select id="discountType" class="form-control"onchange="set_session(this.value,'session_discount')">
				<?php echo $dropdown->DiscountType($category); ?>
			</select>
		</td>
		<td style="width:10px;"></td>
		<td>
			<select id="category" class="form-control"onchange="set_session(this.value,'session_discount_category')">
				<?php echo $dropdown->DiscountItemCategory($category); ?>
			</select>
		</td>

		<td style="width:10px;"></td>
		<td>
			<input list="items" id="itemname" class="form-control" placeholder="Select Items" autocomplete="no" onkeyup="GetItems()" value="<?php echo $item_name; ?>">
			<datalist id="items"></datalist>
		</td>
		<td style="width:10px;"></td>
		<td>
			<input id="discount" type="text" class="form-control form-input" value="<?php echo $discount; ?>">
		</td>
		<td style="width:10px;">
			<input id="rowid" type="hidden" value="<?php echo $rowid; ?>">
		</td>

	</tr>	
	<tr>
		<td>&nbsp;</td>
		<td style="width:10px;"></td>
		<td>&nbsp;</td>
		<td style="width:10px;"></td>
		<td>&nbsp;</td>
		<td style="width:10px;"></td>
		<td>&nbsp;</td>
		<td style="width:10px;"></td>
		<td>&nbsp;</td>
		<td style="width:10px;"></td>

	</tr>
	<tr>
		<td>&nbsp;</td>
		<td style="width:10px;"></td>
		<td>&nbsp;</td>
		<td style="width:10px;"></td>
		<td>&nbsp;</td>
		<td style="width:10px;"></td>

		<td colspan="3" style="text-align:right">
			<?php if($transmode == 'new') { ?>
			<button class="btn btn-success btnnew" style="width:auto" onclick="commitAction('new')">Save Discount</button>
			<?php } if($transmode == 'edit') { ?>
			<button class="btn btn-info btnupdate" onclick="commitAction('edit')" style="width: auto">Update Discount</button>
			<?php } ?>
			<button class="btn btn-danger btnclose" onclick="closeDialoque('additem')">Close</button>
		</td>
	</tr>		
</table>
<script>
function discountCategory(){
	set_session(this.value,'session_discount')
}
$(function()
{
	$('#quantity,#dinomination').keyup(function()
	{
		calculate();
	});
	
});
function commitAction(transmode)
{
	var rowid = $('#rowid').val();
	var branch = $('#branch').val();
	var date = $('#date').val();
	var shift = $('#shift').val();
	var encoder = $('#encoder').val();
	var category = $('#category').val();
	var item_name = $('#itemname').val();
	var discount = $('#discount').val();
		
	if(branch === '') { app_alert("Branch Error","Your Branch does not exists, please set it up first.","warning","Ok","","focus"); return false; } 
	else if(date === '') { app_alert("Date Error","Please select date.","warning","Ok","","focus"); return false; } 
	else if(shift === '') { app_alert("Shift Error","Please select date.","warning","Ok","","focus"); return false; } 
	else if(encoder === '') { app_alert("Encoder Error","Please Relogin.","warning","Ok","encoder","relogin"); return false; } 
	else if(discount === '') { app_alert("Discount. Error","Please enter Discount Amount.","warning","Ok","discount","focus"); return false; } 

	if(transmode === 'new')
	{ 
		var mode = 'savediscount'; 
		$('.btnnew,.btnclose').attr('disabled', true);
		$('.btnnew').html('Saving <i class="fa fa-spinner fa-spin"></i>'); 
		$('#notification').show();
		$('#notification').html('Do not close while saving data... <i class="fa fa-spinner fa-spin">');
	}
	else if(transmode === 'edit')
	{ 
		var mode = 'updatediscount';
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
			encoder: encoder,
			category: category,
			item_name: item_name,
			discount: discount
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
	var dinom = $('#dinomination').val();
	$('#total_amount').val(dinom * quantity);
}
</script>
<script src="scripts/form_scripts.js"></script>

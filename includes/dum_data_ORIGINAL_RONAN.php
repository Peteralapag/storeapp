<?php
include '../init.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);	
$items = new TheFunctions;
$store_branch = $_SESSION['appstore_branch'];
if(!isset($_SESSION['session_date'])) { $trans_date = $date->get_date(); } else { $trans_date = $_SESSION['session_date']; }
if(!isset($_SESSION['session_shift'])) { $store_shift = ''; } else { $store_shift = $_SESSION['session_shift']; }
// $summary = $items->CheckSummary('store_rm_summary_data',$trans_date,$store_branch,$db);
?>
<head>
<meta content="en-us" http-equiv="Content-Language">
<style>
.grid-wrapper { width:100%; }
.table-grid { border-collapse:collapse; cell-spacing:0; cell-padding:0 } 
.table-grid td { border:1px solid #aeaeae; }
.no-border td { border:0; }
.no-border-right td { border-right:0; }
.textbox { border:0;text-align:center;padding:4px;}
.table-footer tr {font-weight:bold;}
.bottom-border {border-bottom:3px solid #aeaeae;}
.fetch-btn-wrapper {padding:5px;text-align:right;}
.footer-form {display:none;}
.new-form {display:none;}
.new-form input {background:#fae1ed;}
.new-form button:hover {background:green;color:white;}
.fagreen { color:green;font-size:18px;}
.fared {color:gray;cursor:pointer;}
.fared:hover {color:red;}
</style>
</head>
<!-- div class="fetch-btn-wrapper">
	<button id="gendum" class="btn btn-primary" onclick="generateDUM()">Generate DUM</button>
	<button class="btn btn-warning" onclick="calculateDUM()">Calculate DUM</button>
	<button class="btn btn-danger" onclick="reGenerateDUM()">Regeneate DUM</button>
</div -->
<script>
function DeleteDumIdiv(rowid)
{
	app_confirm("Delete DUM Item","Are you sure to delete this Item?","warning","deleteDumItemIndividual",rowid,"true");
	return false;
}
function deleteDumItemIndividual(rowid)
{
	var mode = 'deleteDumitemIndiv';
	rms_reloaderOn('Process data...');
	$.post("../actions/actions.php", { mode: mode, rowid: rowid },
	function(data) {
		$('#sumdata').html(data);
		rms_reloaderOff();
		set_function('Daily Usage Report','dum');
	});
}

</script>
<div class="grid-wrapper">	
	<table style="width: 100%" class="table-grid">
		<tr class="bottom-border">
			<td style="text-align:center;width:40px;"><strong>#</strong></td>
			<td style="text-align:center"><strong>DEL</strong></td>
			<td style="text-align:center"><strong>MATERIALS</strong></td>
			<td style="text-align:center"><strong>BEG</strong></td>
			<td style="text-align:center"><strong>DELIVERY</strong></td>
			<td>
				<!-- ################ TRANSFER ###################-->
				<table style="width: 100%" class="table-grid no-border">
					<tr>
						<td style="text-align:center"><strong>TRANSFER</strong></td>
					</tr>
				</table>
				<table style="width: 100%" class="table-grid no-border">
					<tr style="border-top:1px solid #aeaeae">
						<td style="width: 33%;border-right:1px solid #aeaeae;text-align:center"><strong>IN</strong></td>
						<td style="width: 33%;text-align:center;border-right:1px solid #aeaeae"><strong>OUT</strong></td>
						<td style="width: 33%;text-align:center"><strong>C-OUT</strong></td>
					</tr>
				</table>
				<!-- ################ TRANSFER  ###################-->
			</td>
			<td style="text-align:center"><strong>TOTAL</strong></td>
			<td>
				<!-- ################ ACTUAL USAGE ###################-->
				<table style="width: 100%" class="table-grid no-border">
					<tr>
						<td style="text-align:center;white-space:nowrap"><strong>ACTUAL USAGE</strong></td>
					</tr>
				</table>
				<table style="width: 100%" class="table-grid no-border">
					<tr style="border-top:1px solid #aeaeae">
						<td style="width: 100%;border-right:1px solid #aeaeae;text-align:center">
						<strong><?php echo $store_shift; ?></strong></td>
					</tr>
				</table>
				<!-- ################ ACTUAL USAGE ###################-->				
			</td>
			<td style="text-align:center"><strong>EXP.TOTAL</strong></td>
			<td style="text-align:center;white-space:nowrap"><strong>PHYSICAL COUNT</strong></td>
			<td style="text-align:center"><strong>VARIANCE</strong></td>
			<td style="text-align:center"><strong>PRICE/KG</strong></td>
			<td style="text-align:center"><strong>VAR.AMOUNT</strong></td>
		</tr>
		<!-- ############################# DATA ################################# -->
<?php
	$v_amount=0;$v_short=0;$v_over=0;
	$query ="SELECT * FROM store_dum_data WHERE branch='$store_branch' AND shift='$store_shift' AND report_date='$trans_date'";  
	$result = mysqli_query($db, $query);  
	if($result->num_rows > 0)
	{
		$n=0;
		while($DUMROW = mysqli_fetch_array($result))  
		{
			$n++;
			$rowid = $DUMROW['id'];
			$sid = $DUMROW['sid'];
			$branch = $DUMROW['branch'];
			$report_date = $DUMROW['report_date'];
			$shift = $DUMROW['shift'];
			$item_name = $DUMROW['item_name'];
			$beginning = $DUMROW['beginning'];
			$delivery = $DUMROW['delivery'];
			$transfer_in = $DUMROW['transfer_in'];
			$transfer_out = $DUMROW['transfer_out'];
			$counter_out = $DUMROW['counter_out'];
			$sub_total = $DUMROW['sub_total'];
			$actual_usage = $DUMROW['actual_usage'];
			$net_total = $DUMROW['net_total'];
			$physical_count = $DUMROW['physical_count'];
			$variance = $DUMROW['variance'];
			$price_kg = $DUMROW['price_kg'];
			$variance_amount = $DUMROW['variance_amount'];
			$posted = $DUMROW['posted'];
			$status = $DUMROW['status'];
			
			$v_amount += $variance_amount;  ;
			
			if($variance_amount < 0) { $v_short += $variance_amount; }
			if($variance_amount > 0) { $v_over += $variance_amount; }
			
			if($variance < 0) { $bgcolor = '#d8f6d9'; }			
			else if($variance > 0) { $bgcolor = '#f6efd8'; } 
			else { $bgcolor = ''; }

			if($sid == 'null' || $sid == '')			
			{
				$deletebtn = '<i class="fa fa-trash fared" onclick="DeleteItem('.$rowid.')"></i>';
			} else {
				$deletebtn = '<i class="fa-solid fa-lock fagreen"></i>';
			}
?>
		<tr>
			<td style="text-align:center"><?php echo $n; ?></td>
			<td style="text-align:center">
				<button class="btn btn-danger btn-sm" onclick="DeleteDumIdiv('<?php echo $rowid; ?>')"><i class="fa-solid fa-trash"></i></button>
			</td>
			<td style="width:300px">				
				<table style="width: 100%;border-collapse:collapse;border:0" cellpadding="0" cellspacing="0">
					<tr>
						<td style="width:30px;border:0;text-align:center">
							<?php echo $deletebtn; ?>
						</td>
						<td style="border:0">
							<input type="text" class="textbox" style="width:100%;text-align:left" value="<?php echo $item_name; ?>" readonly>
						</td>
					</tr>
				</table>								
			</td>
			<td><input type="text" class="textbox" style="width:100%;text-align:center" value="<?php echo $beginning; ?>" readonly></td>
			<td><input type="text" class="textbox" style="width:100%;text-align:center" value="<?php echo $delivery; ?>" readonly></td>
			<td>				
				<table style="width: 100%" class="table-grid no-border" cellpadding="0" cellspacing="0">
					<tr style="border-top:1px solid #aeaeae">
						<td style="width: 33%;border-right:1px solid #aeaeae;text-align:center">
							<input type="text" class="textbox" style="width:100%;text-align:center" value="<?php echo $transfer_in; ?>" readonly>
						</td>
						<td style="width: 33%;text-align:center;border-right:1px solid #aeaeae">
							<input type="text" class="textbox" style="width:100%;text-align:center" value="<?php echo $transfer_out; ?>" readonly>
						</td>
						<td style="width: 33%;text-align:center">
							<input id="cout<?php echo $n; ?>" type="text" class="textbox" style="width:100%;text-align:center;background:#e1f6f7" value="<?php echo $counter_out; ?>">
						</td>
					</tr>
				</table>
			</td>
			<td><input type="text" class="textbox" style="width:100%;text-align:center" value="<?php echo number_format($sub_total,2); ?>" readonly></td>
			<td>				
				<table style="width: 100%" class="table-grid no-border">
					<tr style="border-top:1px solid #aeaeae">
						<td style="width: 100%;border-right:1px solid #aeaeae;text-align:center">
							<input id="actual_usage<?php echo $n; ?>" type="text" class="textbox" style="width:100%;text-align:center;background:#e1f6f7" value="<?php echo $actual_usage; ?>">
						</td>
					</tr>
				</table>
			</td>
			<td><input type="text" class="textbox" style="width:100%;text-align:center" value="<?php echo $net_total; ?>" readonly></td>
			<td><input type="text" class="textbox" style="width:100%;text-align:center" value="<?php echo $physical_count; ?>" readonly></td>
			<td><input type="text" class="textbox" style="width:100%;text-align:center;background:<?php echo $bgcolor; ?>" value="<?php echo $variance; ?>" readonly></td>
			<td><input type="text" class="textbox" style="width:100%;text-align:center" value="<?php echo $price_kg; ?>" disabled></td>
			<td><input type="text" class="textbox" style="width:100%;text-align:right;" value="<?php echo $variance_amount; ?>" disabled></td>
		</tr>
<script>
$(function()
{
	$('#cout' + '<?php echo $n; ?>').keydown(function (e)
	{
	    if (e.keyCode == 13)
	    {
	    	var mode = 'calculatedum';
	    	var rowid = '<?php echo $rowid; ?>';
	    	var column = 'counter_out';
        	var cout = parseFloat($('#cout' + <?php echo $n; ?>).val());
			var values = cout/1000;
			rms_reloaderOn('Calculating');
			$.post("./actions/actions.php", { mode: mode, column: column, rowid: rowid, values: values },
			function(data) {
				$('.dumresults').html(data);
				$('#' + sessionStorage.navcount).trigger('click');
				rms_reloaderOff();
			});
	    }
	});
	$('#actual_usage' + '<?php echo $n; ?>').keydown(function (e)
	{
	    if (e.keyCode == 13)
	    {
	    	var mode = 'calculatedum';
	    	var rowid = '<?php echo $rowid; ?>';
	    	var column = 'actual_usage';
        	var values = parseFloat($('#actual_usage' + <?php echo $n; ?>).val());
        	var values = values/1000;
			rms_reloaderOn('Calculating');
			$.post("./actions/actions.php", { mode: mode, column: column, rowid: rowid, values: values },
			function(data) {
				$('.dumresults').html(data);
				$('#' + sessionStorage.navcount).trigger('click');
				rms_reloaderOff();
			});
	    }
	});
});
</script>		
<?php } ?>
<?php } else { echo ""; } ?>	
		<tr class="table-footer" style="border-top:5px solid #aeaeae">
			<td style="text-align:center">
				<button id="formbtn" class="btn-block" style="height:27px;border:0"><i class="fa-solid fa-caret-down"></i></button>
			</td>
			<td></td>
			<td style="width:300px;padding:5px;text-align:center">TOTAL</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td class="bg-danger" style="text-align:right">SHORT&nbsp;&nbsp;</td>
			<td class="bg-danger" style="text-align:center"><?php echo $v_short; ?></td>
			<td class="bg-primary" style="text-align:right">OVER&nbsp;&nbsp;</td>
			<td class="bg-primary" style="text-align:center"><?php echo $v_over; ?></td>
			<td style="text-align:right;font-weight:bold;padding-right:5px;"><?php echo number_format($v_amount,2); ?></td>
		</tr>
		<!-- ############################# DATA TOTAL ############################# -->
		<tr class="new-form" id="newform">
			<td style="text-align:center">
				<button id="formbtn" class="btn-block" style="height:26px;border:0" onclick="addNewDUM()"><i class="fa-duotone fa-check" style="font-weight:bold"></i></button>
			</td>
			<td style="width:300px">
				<input id="rawmats" class="textbox" list="itembrowser" autocomplete="off" placeholder="Material/Product description" style="width:100%;text-align:left">
				<datalist id="itembrowser">
					<?php echo $items->GetRawmats('RAWMATS',$db); ?>
				</datalist>
			</td>
			<td><input id="beginning" type="number" class="textbox" style="width:100%;text-align:center" value="0.00"></td>
			<td><input id="delivery" type="number" class="textbox" style="width:100%;text-align:center" value="0.00"></td>
			<td>				
				<table style="width: 100%" class="table-grid no-border" cellpadding="0" cellspacing="0">
					<tr style="border-top:1px solid #aeaeae">
						<td style="width: 33%;border-right:1px solid #aeaeae;text-align:center">
							<input id="tin" type="number" class="textbox" style="width:100%;text-align:center" value="0.00">
						</td>
						<td style="width: 33%;text-align:center;border-right:1px solid #aeaeae">
							<input id="tout" type="number" class="textbox" style="width:100%;text-align:center" value="0.00">
						</td>
						<td style="width: 33%;text-align:center">
							<input id="cout" type="number" class="textbox" style="width:100%;text-align:center" value="0.00">
						</td>
					</tr>
				</table>
			</td>
			<td><input type="number" class="textbox" style="width:100%;text-align:center;background:#fff" value="0.00" disabled></td>
			<td>				
				<table style="width: 100%" class="table-grid no-border">
					<tr style="border-top:1px solid #aeaeae">
						<td style="width: 100%;border-right:1px solid #aeaeae;text-align:center">
							<input id="ausage" type="number" class="textbox" style="width:100%;text-align:center" value="0.00">
						</td>
					</tr>
				</table>
			</td>
			<td><input type="number" class="textbox" style="width:100%;text-align:center; background:#fff" placeholder="0.00" disabled></td>
			<td style="white-space:nowrap"><input id="pcount" type="number" class="textbox" style="width:100%;text-align:center" value="0.00"></td>
			<td><input type="number" class="textbox" style="width:100%;text-align:center;background:#fff" value="0.00"></td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
	</table>
</div>
<div class="dumresults"></div>
<script>

function reGenerateDUM()
{
	app_confirm("Regenerate DUM Report","Are you sure to do this? All data will be deleted and regenerate from default value. If you are not sure please select No","warning","deletedumreport",'<?php echo $trans_date; ?>');
	return false;
}
function reGenerateDUMYes(reportdate)
{
	rms_reloader('Calculating');
	setTimeout(function()
	{
		$.post("./actions/delete_dum_report.php", { reportdate: reportdate },
		function(data) {
			$('.dumresults').html(data);
			rms_reloaderOff();
			$('#gendum').trigger('click');
		});
	},1000);
}
function DeleteItem(rowid)
{
	app_confirm("Remove Item","Are you sure to remove this item?","warning","DeleteItemYes",rowid);
}
function DeleteItemYes(rowid)
{
	rms_reloader('Removing Item...');
	setTimeout(function()
	{
		var mode = 'removingdumitem';
		$.post("./actions/process.php", { mode: mode, rowid: rowid },
		function(data) {
			$('.dumresults').html(data);
			rms_reloaderOff();
		});
	},700);

}
function addNewDUM()
{
	var mode = 'addnewdum';
	var rawmats = $('#rawmats').val();
	var beginning = $('#beginning').val();
	var delivery = $('#delivery').val();
	var tin = $('#tin').val();
	var tout = $('#tout').val();
	var cout = parseFloat($('#cout').val());
	var ausage = parseFloat($('#ausage').val());
	var pcount = $('#pcount').val();

	var cout = cout/1000;
	var ausage = ausage/1000;	
	if(rawmats === '' || rawmats === 'undefined')		
	{
		app_alert("Incomplete Item","Please select atleast one Item","warning","Ok","rawmats","focus");
		return false;
	}
	if($('#beginning').val() == '')
	{
		app_alert("Invalid Number","Enter 0 if there is no value to input","warning","Ok","beginning","focus");
		return false;
	}
	else if($('#delivery').val() == '')
	{
		app_alert("Invalid Number","Enter 0 if there is no value to input","warning","Ok","delivery","focus");
		return false;
	}
	else if($('#tin').val() == '')
	{
		app_alert("Invalid Number","Enter 0 if there is no value to input","warning","Ok","tin","focus");
		return false;
	}
	else if($('#tout').val() == '')
	{
		app_alert("Invalid Number","Enter 0 if there is no value to input","warning","Ok","tout","focus");
		return false;
	}
	else if($('#cout').val() == '')
	{
		app_alert("Invalid Number","Enter 0 if there is no value to input","warning","Ok","cout","focus");
		return false;
	}
	else if($('#ausage').val() == '')
	{
		app_alert("Invalid Number","Enter 0 if there is no value to input","warning","Ok","ausage","focus");
		return false;
	}
	else if($('#pcount').val() == '')
	{
		app_alert("Invalid Number","Enter 0 if there is no value to input","warning","Ok","pcount","focus");
		return false;
	}	
	rms_reloader('Saving...');
	$('#newform').fadeOut();
	setTimeout(function()
	{
		$.post("./actions/process.php", { mode: mode, rawmats: rawmats,beginning: beginning,delivery: delivery,tin: tin,tout: tout,cout: cout,ausage: ausage,pcount: pcount    },
		function(data) {
			$('.dumresults').html(data);
			rms_reloaderOff();
		});
	},1200);
}
function generateDUMssssss()
{
	rms_reloader('Fetching Inventory...');
	setTimeout(function()
	{
		$.post("./actions/fetch_dum.php", {  },
		function(data) {
			$('.dumresults').html(data);
			rms_reloaderOff();
		//	$('#' + sessionStorage.btncount).trigger('click');
		});
	},700);
}
$(function()
{
	var sumcheck = '<?php echo $summary; ?>';
	if(sumcheck == 1)
	{
	//	console.log("Yes");
	}
	$('#formbtn').click(function()
	{
		if($("#newform").is(":visible"))
		{
			$('#newform').hide();
			$('#formbtn').html('<i class="fa-solid fa-caret-down"></i>');
			$('#newform').find('input:text').val(''); 
			$('#beginning,#delivery,#tin,#tout,#cout,#ausage,#pcount').val('0.00');			 			 
		} else {
			$('#newform').show();
			$('#formbtn').html('<i class="fa-solid fa-caret-up"></i>');
			$('#rmspagesdata').scrollTop($('#rmspagesdata').height());
		}
	});
	
	$('#batchesshell').dblclick(function()
	{
		$('#textbox').attr('readonly', false);
		document.getElementById('textbox').style.background = '#fbf6e6';
	});
	$('#textbox').blur(function()
	{
		$('#textbox').attr('readonly', true);
		document.getElementById('textbox').style.background = '';
	});

});

</script>
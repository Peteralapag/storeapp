<?PHP // require_once '../init.php';?>
<!DOCTYPE html>
<html>
<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<meta name="viewport" content="width=device-width, initial-scale=3">
<title>STORE APPS INSTALLER</title>
<link rel="stylesheet" href="../styles/fa/css/all.css">
<link rel="stylesheet" href="../styles/bootstrap.min.css">
<link rel="stylesheet" href="../styles/dataTables.bootstrap.min.css">
<link rel="stylesheet" href="../styles/styles.css">
<script src="../scripts/jquery.min.js"></script>
<script src="../scripts/bootstrap.min.js"></script>
<link rel="stylesheet" href="../styles/jquery-ui.css">
<style>
.navs td li {
	margin-bottom:5px;	
}
</style>
</head>
<body>
<div style="padding:20px">
	<h4 style="text-align:center;border-bottom:5px solid #aeaeae;padding-bottom:10px;">DATA TOOLS PANEL</h4>
	<table style="width: 100%; height: calc(100vh - 100px)">
		<tr class="navs">
			<td style="width:250px;vertical-align:top">
				<ul>
					<li style="text-align:center;border-bottom:5px solid orange;">FINISH GOODS</li>
					<li><button class="btn btn-primary btn-block action" onclick="fixTables('fgts')">FIX FGTS</button></li>
					<li><button class="btn btn-primary btn-block action" onclick="fixTables('transfer')">FIX TRANSFER</button></li>
					<li><button class="btn btn-primary btn-block action" onclick="fixTables('charges')">FIX CHARGES</button></li>
					<li><button class="btn btn-primary btn-block action" onclick="fixTables('snacks')">FIX SNACKS</button></li>
					<li><button class="btn btn-primary btn-block action" onclick="fixTables('badorder')">FIX BAD ORDER</button></li>
					<li><button class="btn btn-primary btn-block action" onclick="fixTables('damage')">FIX DAMAGED</button></li>
					<li><button class="btn btn-primary btn-block action" onclick="fixTables('complimentary')">FIX COMPLIMENTARY</button></li>
					<li><button class="btn btn-primary btn-block action" onclick="fixTables('receiving')">FIX RECEIVING</button></li>
					<li><button class="btn btn-primary btn-block action" onclick="fixTables('cashcount')">FIX CASH COUNT</button></li>
					<li><button class="btn btn-primary btn-block action" onclick="fixTables('frozendough')">FIX FROZEN DOUGH</button></li>
					<li><button class="btn btn-primary btn-block action" onclick="fixTables('pcount')">FIX PHYSICAL COUNT</button></li>
					<li><button class="btn btn-primary btn-block action" onclick="fixTables('discount')">FIX DISCOUNT</button></li>
					<li><button class="btn btn-primary btn-block action" onclick="fixTables('summary')">FIX SUMMARY</button></li>
					<li style="margin-top:10px; padding-top:20px;text-align:center;border-bottom:5px solid orange;">RAWMATS</li>
					<li><button class="btn btn-primary btn-block action" onclick="fixTables('rm_receiving')">FIX RECEIVING</button></li>
					<li><button class="btn btn-primary btn-block action" onclick="fixTables('rm_transfer')">FIX TRANSFER</button></li>
					<li><button class="btn btn-primary btn-block action" onclick="fixTables('rm_badorder')">FIX BAD ORDER</button></li>
					<li><button class="btn btn-primary btn-block action" onclick="fixTables('rm_pcount')">FIX PHYSICAL COUNT</button></li>
					<li><button class="btn btn-primary btn-block action" onclick="fixTables('rm_summary')">FIX SUMMARY</button></li>
					<li style="margin-top:10px; padding-top:20px;text-align:center;border-bottom:5px solid orange;">OTHER TOOLS----</li>
					<li><button class="btn btn-primary btn-block action" onclick="branchtools('othertools')">FIX BRANCH</button></li>

				</ul>
			</td>
			<td style="padding-left:20px;vertical-align:top; overflow:auto" id="datas">
				
			</td>
		</tr>
	</table>
</div>
<script>
function fixTables(tables)
{
	$('.action').prop('disabled', true);
	$('#datas').html("Executing " + tables + ' <i class="fa fa-spinner fa-spin"></i>')
	setTimeout(function()
	{
		$.post("./action_process.php", { tables: tables },
		function(data) {			
			$("#datas").html(data);
			$('.action').prop('disabled', false);
		});
	},1000);
}
function branchtools(tables){
	
	$.post("./action_process.php", { tables: tables },
	function(data) {			
		$("#datas").html(data);
	});
			
}
</script>
<script src="../scripts/jquery-ui.js"></script>
<script src="../scripts/jquery.dataTables.min.js"></script>
<script src="../scripts/dataTables.bootstrap.min.js"></script>
<body>
</body>

</html>

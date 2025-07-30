<?php
include '../init.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$functions = new TheFunctions;
$trans_date = $functions->GetSession('branchdate');
$store_branch = $functions->AppBranch();
$file_name = isset($_POST['pagename']) ? $_POST['pagename'] : '';
$title = strtoupper($file_name);
$shift = $_SESSION['session_shift'];

$cashCountDataCheckingForExist = $functions->cashCountDataCheckingForExist($store_branch,$trans_date,$shift,$db);

?>
<style>
.faded-row {
    opacity: 0.6;
}
</style>
<div class="tableFixHead">
<?php if($cashCountDataCheckingForExist == '1'){?>
<table style="width:50%" class="table table-hover table-striped table-bordered">
	<thead>
		<tr>
			<th style="text-align:center;width:50px">#</th>
			<th>DINOMINATION</th>
			<th>QUANTITY</th>
			<th>TOTAL AMOUNT</th>
			<th>STATUS</th>
		</tr>
	</thead>
	<tbody>
	<?php
		$query ="SELECT * FROM store_cashcount_data WHERE branch='$store_branch' AND report_date='$trans_date' AND shift='$shift' ORDER BY denomination DESC";  
		$result = mysqli_query($db, $query);
		if($result->num_rows > 0)
		{
			$x=0;
			while($ROWS = mysqli_fetch_array($result))  
			{ 
				$x++;
				$total_amount = $ROWS['quantity'] * $ROWS['denomination'];
			?>
				<tr data-id="<?php echo $ROWS['id']; ?>">
				    <td style="text-align:center"><?php echo $x; ?></td>
				    <td style="text-align:right; padding-right:30px !important; width:150px"><?php echo @$ROWS['denomination']; ?></td>
				    <?php if($ROWS['status']=='Closed') {?>
				    <td style="text-align:right; padding-right:30px !important; width:150px;"><?php echo @$ROWS['quantity']; ?></td>
				    <?php } else {?>
				    <td style="text-align:right; padding-right:30px !important; width:150px; background-color:#f7e9d5" contenteditable="true" data-denomination="<?php echo $ROWS['denomination']; ?>"><?php echo @$ROWS['quantity']; ?></td>
					<?php }?>
				    <td style="text-align:right; padding-right:30px !important; width:150px" class="total-amount"><?php echo number_format($total_amount,2); ?></td>
				    <td style="text-align:center; !important; width:150px"><?php echo @$ROWS['status']; ?></td>
				</tr>				
			<?php 
			}
		}

	?>
		<tr>
			<td colspan="3" style="font-weight:bold; text-align:center">TOTAL</td>
			<td id="overalltotalamount" style="text-align:right; padding-right:30px !important; width:150px"><?php echo number_format($functions->cashCountDataCheckingOverAllAmount($store_branch,$shift,$trans_date,$db),2)?></td>
		</tr>
	</tbody>
</table>
<div>
	<span>Total amount in words:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
	<span><?php echo ucwords($functions->numbertowords($functions->cashCountDataCheckingOverAllAmount($store_branch,$shift,$trans_date,$db))) ?> PESOS</span>
</div>
<?php 
} else {?>
	<button id="startCashCount" class="btn btn-success btn-sm"><i class="fa fa-play" aria-hidden="true"></i>&nbsp;Start Cash Count</button>&nbsp;
<?php
}
?>
</div>

<script>
$(document).ready(function(){
    
    function calculateGrandTotal() {
        var grandTotal = 0;
        $(".total-amount").each(function() {
            grandTotal += parseFloat($(this).text().replace(/,/g, ''));
        });
        $("#grand-total").text(grandTotal.toFixed(2));
        
        calculateOverallTotal(); // Call the function to update overall total
    }

    function calculateOverallTotal() {
        var overallTotal = 0;
        $(".total-amount").each(function() {
            overallTotal += parseFloat($(this).text().replace(/,/g, ''));
        });
        $("#overalltotalamount").text(overallTotal.toFixed(2));
    }

    $("td[contenteditable='true']").on("input", function() {
        var quantity = $(this).text();
        var denomination = $(this).data("denomination");
        var totalAmount = quantity * denomination;
        $(this).closest("tr").find(".total-amount").text(totalAmount.toFixed(2));
        
        var rowId = $(this).closest("tr").data("id");
        updateDatabase(rowId, quantity, totalAmount);

        calculateGrandTotal();
    })
    .on("click", function() {
        // Highlight the content of the td when clicked
        var range = document.createRange();
        range.selectNodeContents(this);
        
        var sel = window.getSelection();
        sel.removeAllRanges();
        sel.addRange(range);
    });

    function updateDatabase(rowId, quantity, totalAmount) {
        var mode = "updatecashcount_new";
        $.post("./actions/actions.php", {
            mode: mode,
            rowId: rowId,
            quantity: quantity,
            totalAmount: totalAmount
        }, function(data) {
            console.log(data);
        }).done(function() {
            rms_reloaderOff();
        }).fail(function(error) {
            console.log(error);
        });
    }

    $("#startCashCount").click(function(){
        
        var mode = "savecashcount_new";
        
        var denoval = ['1000.00','500.00','200.00','100.00','50.00','20.00','10.00','5.00','1.00','0.05','0.025','0.01','0.005'];

        var dataToSend = [];

        $.each(denoval, function(index, denomination) {
            dataToSend.push({
                'denomination': denomination,
                'quantity': 0, 
                'totalAmount': 0
            });
        });
        
        $.post("./actions/actions.php", {
            mode: mode,
            data: dataToSend
        }, function(data) {
            console.log(data);
        }).done(function() {
            $('#' + sessionStorage.navcount).trigger('click');
            rms_reloaderOff();
        }).fail(function(error) {
            console.log(error);
        });
    });    
});
</script>

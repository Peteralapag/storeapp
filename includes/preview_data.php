<?php
require '../init.php';
require '../class/functions.class.preview.php';
require '../class/functions_forms.class.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

$myShifting = $_SESSION['appstore_shifting'];

$functions = new TheFunctions;
$preview = new preview;
$FunctionForms= new FunctionForms;

$branch = $functions->AppBranch();
$transdate = $functions->GetSession('branchdate');
$shift = $functions->GetSession('shift');
$q = "WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift'";
?>
<style>
#note-container {
    display: none;
    position: absolute;
    background-color: white;
    border: 1px solid black;
    padding: 10px;
}
</style>
<table id="upper" style="width: 100%" class="table table-hover table-striped table-bordered">
    <tr>
        <th style="width:50px;text-align:center">#</th>
        <th>ITEMS</th>
        <th>BEG</th>
        <th>STK. IN</th>
        <th>F.DOUGH</th>
        <th>TX-IN</th>
        <th>TOTAL</th>
        <th>TX-OUT</th>
        <th>CHARGES</th>
        <th>SNACKS</th>
        <th>B.O.</th>
        <th>DAMAGED</th>
        <th>COMPLI.</th>
        <th>T.G.A.S.</th>
        <th>ACTL. COUNT</th>
        <th>SOLD</th>
    </tr>
    <?php

    $tfgts = "SELECT item_name FROM store_fgts_data $q UNION ALL";
    $ttransfer = "SELECT item_name FROM store_transfer_data $q UNION ALL";
    $tcharges = "SELECT item_name FROM store_charges_data $q UNION ALL";
    $tsnacks = "SELECT item_name FROM store_snacks_data $q UNION ALL";
    $tbo = "SELECT item_name FROM store_badorder_data $q UNION ALL";
    $tdamage = "SELECT item_name FROM store_damage_data $q UNION ALL";
    $tcomplimentary = "SELECT item_name FROM store_complimentary_data $q UNION ALL";
    $treceiving = "SELECT item_name FROM store_receiving_data $q UNION ALL";
    $tfrozendough = "SELECT item_name FROM store_frozendough_data $q UNION ALL";
    $tpcount = "SELECT item_name FROM store_pcount_data $q UNION ALL";
    $tsummary = "SELECT item_name FROM store_summary_data $q";

    $query = "(SELECT DISTINCT item_name FROM ($tfgts $ttransfer $tcharges $tsnacks $tbo $tdamage $tcomplimentary $treceiving $tfrozendough $tpcount $tsummary) AS combined_tables)";

    $result = mysqli_query($db, $query);

    if (!$result) {
        die("Query failed: " . mysqli_error($db));
    }

    //    echo "Debug: SQL Query: $query<br>";
    if($result->num_rows > 0)
    {
        $i = 0;
        while($ROW = mysqli_fetch_array($result))
        {
            $i++;
            $itemname = $ROW['item_name'];
            $item_id = $ROW['item_id'];

            $transferoutval = $preview->getTableValue('transfer','quantity',$itemname,$branch,$transdate,$shift,$db);
            $chargesval = $preview->getTableValue('charges','quantity',$itemname,$branch,$transdate,$shift,$db);
            $snacksval = $preview->getTableValue('snacks','quantity',$itemname,$branch,$transdate,$shift,$db);
            $boval = $preview->getTableValue('badorder','quantity',$itemname,$branch,$transdate,$shift,$db);
            $damageval = $preview->getTableValue('damage','quantity',$itemname,$branch,$transdate,$shift,$db);
            $complimentaryval = $preview->getTableValue('complimentary','quantity',$itemname,$branch,$transdate,$shift,$db);

            $frozendoughval = $preview->getTableValue('frozendough','actual_yield',$itemname,$branch,$transdate,$shift,$db);
            $fgtsval = $preview->getTableValue('fgts','actual_yield',$itemname,$branch,$transdate,$shift,$db);
            $receivingval = $preview->getTableValue('receiving','quantity',$itemname,$branch,$transdate,$shift,$db);
            $stockinvalue = $fgtsval + $receivingval;
            $beginningval = $preview->getTableValue('summary','beginning',$itemname,$branch,$transdate,$shift,$db);
            $transferinval = $preview->getTableValuetransferIn('transfer','quantity',$itemname,$branch,$transdate,$shift,$db);

            $actualCount = $preview->getTableValue('pcount','actual_count',$itemname,$branch,$transdate,$shift,$db);


            $totalval = $beginningval+ $stockinvalue + $transferinval + $frozendoughval;

            $shouldbeval = $totalval - $transferoutval - $chargesval - $snacksval - $boval - $damageval - $complimentaryval;

            $sold = $shouldbeval == 0 ? 0: $shouldbeval - $actualCount;

            $beginningInitial = $myShifting == 2? $FunctionForms->twoShiftingBegGet($item_id, $shift, $transdate, $branch, $db): $FunctionForms->threeShiftingBegGet($item_id, $shift, $transdate, $branch, $db);
			
			
			$unit_price = $preview->getItemPricePreviePage($itemname,$db);
			
			$amount = $sold * $unit_price;

            $colorData = '';
            $note = '';
            if($amount < 0){
                $colorData = '#d67673';
                $note = 'Higher actual count than T.G.A.S => the result of amount is negative';
            }
            if($sold > 0 && $amount == 0){
                $colorData = '#FFA500';
                $note = 'The inventory is depleted => the result of amount is zero';
            }
            if($actualCount > $totalval){
                $colorData = '#FFA500';
                $note = 'The actual count exceeds the total amount';
            }
/*            if($beginningval != $beginningInitial){
                $colorData = '#FFA500';
                $note = "The previous period's ending inventory doesn't match the current period's beginning inventory";        
            }
*/

            ?>
            <tr style="color:<?php echo $colorData?>" class="element" data-note="<?php echo $note?>">
                <td style="width:50px;text-align:center"><?php echo $i?></td>
                <td><?php echo $itemname?></td>
                <td><?php echo number_format($beginningval,2)?></td>
                <td><?php echo number_format($stockinvalue,2)?></td>
                <td><?php echo number_format($frozendoughval,2)?></td>
                <td><?php echo number_format($transferinval,2)?></td>
                <td><?php echo number_format($totalval,2)?></td>
                <td><?php echo number_format($transferoutval,2)?></td>
                <td><?php echo number_format($chargesval,2)?></td>
                <td><?php echo number_format($snacksval,2)?></td>
                <td><?php echo number_format($boval,2)?></td>
                <td><?php echo number_format($damageval,2)?></td>
                <td><?php echo number_format($complimentaryval,2)?></td>
                <td><?php echo number_format($shouldbeval,2)?></td>
                <td><?php echo number_format($actualCount,2)?></td>
                <td><?php echo number_format($sold,2)?></td>
            </tr>
            <?php
        }
    }
    else
    {
        // Handle case when no rows are returned
        echo "<tr><td colspan='15'>No data found.</td></tr>";
    }   
    ?>
</table>

<script>
$(document).ready(function() {
    function showNote() {
        var note = $(this).data('note');
        if (note != '') {
            $('#note-container').text(note).show();
        }
    }
    
    function hideNote() {
        $('#note-container').hide();
    }
    
    $('.element').hover(showNote, hideNote);
    
    // Adjust the position of the note-container based on mouse movement
    $(document).on('mousemove', function(event) {
        $('#note-container').css({
            top: event.pageY + 10,
            left: event.pageX + 10
        });
    });
});
</script>

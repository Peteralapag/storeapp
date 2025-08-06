<?php
include '../init.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
include '../db_config_main.php';
$mainconn = new mysqli(CON_HOST, CON_USER, CON_PASSWORD, CON_NAME);

$storebranch = $functions->AppBranch();
$transdate = $functions->GetSession('branchdate');
$shift = $functions->GetSession('shift');
$time_stamp = date("Y-m-d H:i:s");

if (!isset($_POST['modulename'])) {
    echo '<script>app_alert("Warning"," The Mode you are trying to pass does not exist","warning","Ok","","no");</script>';
    exit();
}

$mode = $_POST['modulename'];
$table = "store_{$mode}_data";
$module_name = strtoupper($mode);


switch ($mode) {
    case 'production':
        $months = $_SESSION['appstore_month'] ?? date("F");
        $monthnum = $functions->GetMonthNumber($months);
        $q = "branch='$storebranch' AND month='$monthnum'";
        break;
    case 'transfer':
    case 'rm_transfer':
    case 'supplies_transfer':
    case 'scrapinventory_transfer':
    case 'boinventory_transfer':
        $q = "report_date='$transdate' AND shift='$shift' AND posted='Posted'";
        break;
    case 'dum':
        $q = "branch='$storebranch' AND report_date='$transdate' AND shift='$shift'";
        break;
    default:
        $q = "branch='$storebranch' AND report_date='$transdate' AND shift='$shift' AND posted='Posted'";
        break;
}


$query = "SELECT * FROM $table WHERE $q";
$result = $db->query($query);

if ($result && $result->num_rows > 0) {
    while ($ROW = $result->fetch_assoc()) {
        $rowid = $ROW['id'];


        if (in_array($mode, ['transfer', 'rm_transfer', 'supplies_transfer', 'scrapinventory_transfer', 'boinventory_transfer'])) {
            if ($ROW['branch'] === $storebranch) {
                $recipient = "branch='$storebranch'";
            } else if ($ROW['transfer_to'] === $storebranch) {
                $recipient = "transfer_to='$storebranch'";
            }
        }


        $functionMap = [
            'fgts' => 'SendFGTSToServer',
            'transfer' => 'SendTransferToServer',
            'charges' => 'SendChargesToServer',
            'snacks' => 'SendSnacksToServer',
            'badorder' => 'SendBOToServer',
            'damage' => 'SendDamageToServer',
            'complimentary' => 'SendComplimentaryToServer',
            'receiving' => 'SendReceivingToServer',
            'cashcount' => 'SendCashCountToServer',
            'frozendough' => 'SendFrozenDoughToServer',
            'pcount' => 'SendPCountToServer',
            'discount' => 'SendDiscountToServer',
            'gcash' => 'SendGcashToServer',
            'grab' => 'SendGrabToServer',
            'foodpanda' => 'SendFoodPandaToServer',
            'pakati' => 'SendPakatiToServer',
            'production' => 'SendProductionToServer',
            'summary' => 'SendSummaryToServer',
            'rm_receiving' => 'SendRmReceivingToServer',
            'rm_transfer' => 'SendRmTransferToServer',
            'rm_badorder' => 'SendRmBOToServer',
            'rm_pcount' => 'SendRmPCountToServer',
            'dum' => 'SendDumToServer',
            'rm_summary' => 'SendRmSummaryToServer',
            'supplies_receiving' => 'SendSuppliesReceivingToServer',
            'supplies_transfer' => 'SendSuppliesTransferToServer',
            'supplies_badorder' => 'SendRmBOToServer',
            'supplies_pcount' => 'SendSuppliesPCountToServer',
            'supplies_summary' => 'SendSuppliesSummaryToServer',
            'scrapinventory_receiving' => 'SendScrapInventoryReceivingToServer',
            'scrapinventory_transfer' => 'SendScrapInventoryTransferToServer',
            'scrapinventory_badorder' => 'SendScrapInventoryBOToServer',
            'scrapinventory_pcount' => 'SendScrapInventoryPCountToServer',
            'scrapinventory_summary' => 'SendScrapInventorySummaryToServer',
        ];


        if (isset($functionMap[$mode])) {
            $method = $functionMap[$mode];
            if (in_array($mode, ['production'])) {
                echo $summary->$method($storebranch, $monthnum, $table, $rowid, $db, $mainconn);
            } else if (in_array($mode, ['transfer', 'rm_transfer', 'supplies_transfer', 'scrapinventory_transfer', 'boinventory_transfer'])) {
                echo $summary->$method($recipient, $transdate, $shift, $time_stamp, $table, $rowid, $db, $mainconn);
            } else {
                echo $summary->$method($storebranch, $transdate, $shift, $time_stamp, $table, $rowid, $db, $mainconn);
            }
        }
    }
} else {
    echo "No records found";
}
?>

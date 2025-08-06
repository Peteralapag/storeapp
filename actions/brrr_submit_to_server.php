<?php



include '../init.php';

include '../class/brrr.class.php';

$module = $_POST['modulename'] ?? '';

$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$brrr = new brrr;

$branch = $functions->GetSession('branch');
$transdate = $functions->GetSession('branchdate');


include '../db_config_main.php';
$conn = new mysqli(CON_HOST, CON_USER, CON_PASSWORD, CON_NAME);

if ($conn->connect_error) {
    die("Connection to server failed: " . $conn->connect_error);
}


$inserted = 0;

switch ($module) {
    case 'overhead':
        $inserted = $brrr->SubmitOverheadToServer($branch, $transdate, $db, $conn);
        break;

    case 'expense':
        $inserted = $brrr->SubmitExpenseToServer($branch, $transdate, $db, $conn);
        break;

	case 'summary':
        $inserted = $brrr->SubmitSummaryToServer($branch, $transdate, $db, $conn);
        break;



    default:
        echo "<div class='alert alert-danger'>Unknown module: $module</div>";
        exit;
}

echo "<div class='alert alert-success'>$inserted items submitted successfully to server for module <strong>$module</strong>.</div>";

<?php
ob_start();
header('Content-Type: application/json');

ini_set('display_errors', 1);
error_reporting(E_ALL);



if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require '../init.php';

$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
if ($db->connect_error) {
    echo json_encode(['status'=>'error','msg'=>$db->connect_error]);
    exit;
}

$branch = $_POST['branch'] ?? '';
$item_id = (int)($_POST['item_id'] ?? 0);
$forecast_date = $_POST['forecast_date'] ?? '';
$forecast_percent = (float)($_POST['forecast_percent'] ?? 0);
$user = $_SESSION['appstore_appnameuser'] ?? 'unknown';

if(!$branch || !$item_id || !$forecast_date){
    echo json_encode(['status'=>'error','msg'=>'Missing required fields']);
    exit;
}

// CHECK
$check = $db->prepare("
    SELECT id FROM store_forecasting
    WHERE branch=? AND item_id=? AND forecast_date=?
");

if(!$check){
    echo json_encode(['status'=>'error','msg'=>$db->error]);
    exit;
}

$check->bind_param("sis",$branch,$item_id,$forecast_date);
$check->execute();
$check->store_result();

if($check->num_rows > 0){

    $update = $db->prepare("
        UPDATE store_forecasting
        SET forecast_percent=?, created_by=?, updated_at=NOW()
        WHERE branch=? AND item_id=? AND forecast_date=?
    ");

    if(!$update){
        echo json_encode(['status'=>'error','msg'=>$db->error]);
        exit;
    }

    $update->bind_param(
        "dssis",
        $forecast_percent,
        $user,
        $branch,
        $item_id,
        $forecast_date
    );
    $update->execute();
    $update->close();

    echo json_encode(['status'=>'success','mode'=>'update']);

}else{

    $insert = $db->prepare("
        INSERT INTO store_forecasting
        (branch,item_id,forecast_date,forecast_percent,created_by,created_at)
        VALUES (?,?,?,?,?,NOW())
    ");

    if(!$insert){
        echo json_encode(['status'=>'error','msg'=>$db->error]);
        exit;
    }

    $insert->bind_param(
        "sisds",
        $branch,
        $item_id,
        $forecast_date,
        $forecast_percent,
        $user
    );
    $insert->execute();
    $insert->close();

    echo json_encode(['status'=>'success','mode'=>'insert']);
}

$check->close();
$db->close();

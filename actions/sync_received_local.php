<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../init.php';
include '../db_config_main_ver2.php'; // CON_HOST, CON_USER, CON_PASSWORD, CON_NAME

$branch = $_POST['branch'] ?? '';
$shift = $_POST['shift'] ?? '';
$report_date = $_POST['report_date'] ?? '';
$output = '';

// ---------------- LOCAL DB ----------------
$local_db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
if($local_db->connect_error){
    die("Local DB connection failed: ".$local_db->connect_error);
}

// ---------------- WMS ONLINE DB ----------------
$wms_db = new mysqli(CON_HOST, CON_USER, CON_PASSWORD, 'application_data');
if($wms_db->connect_error){
    die("WMS DB connection failed: ".$wms_db->connect_error);
}

// STEP 1: Fetch received items from WMS for this branch and date
$sql = "
    SELECT 
        pri.id AS receipt_item_id,
        pri.received_qty,
        pri.unit_price,
        pri.created_at AS received_at,
        pri.receipt_id,
        poi.id AS po_item_id,
        poi.item_code,
        poi.description,
        poi.uom,
        po.id AS po_id,
        po.source,
        po.supplier_id,
        s.name AS supplier_name,
        pr.received_by AS employee_name
    FROM purchase_receipt_items pri
    INNER JOIN purchase_receipts pr
            ON pri.receipt_id = pr.id
    INNER JOIN purchase_order_items poi 
            ON pri.po_item_id = poi.id
    INNER JOIN purchase_orders po 
            ON poi.po_id = po.id
    LEFT JOIN suppliers s 
            ON po.supplier_id = s.id
    WHERE DATE(pr.received_date) = ?
      AND po.source = ?
    ORDER BY pri.id ASC
";

$stmt = $wms_db->prepare($sql);
$stmt->bind_param("ss", $report_date, $branch);
$stmt->execute();
$wms_items = $stmt->get_result();

if($wms_items->num_rows == 0){
    echo "No received items found for $branch on $report_date";
    exit;
}

// STEP 2: Loop through WMS items
while($row = $wms_items->fetch_assoc()){

    $receipt_item_id = $row['receipt_item_id'];
    $item_code = $row['item_code'];
    $received_qty = (float)$row['received_qty'];
    $item_name = $row['description'];
    $units = $row['uom'];
    $supplier_id = $row['supplier_id'] ?? 0;
    $supplier_name = $row['supplier_name'] ?? 'Unknown';
    $employee_name = $row['employee_name'] ?? 'Unknown';

    // STEP 2a: Lookup mapping
    $map_stmt = $wms_db->prepare("SELECT store_item_id FROM store_item_mapping WHERE wms_item_code=? AND status=1");
    $map_stmt->bind_param("s", $item_code);
    $map_stmt->execute();
    $map_res = $map_stmt->get_result();

    if($map_res->num_rows == 0){
        $output .= "<span style='color:red'>Mapping missing for item_code: $item_code</span><br>";
        continue;
    }

    $store_item_id = $map_res->fetch_assoc()['store_item_id'];

    // STEP 2b: Check if record exists in store_receiving_data for this exact receipt_item_id
    $check_stmt = $local_db->prepare("SELECT id FROM store_receiving_data WHERE wms_receipt_item_id=?");
    $check_stmt->bind_param("i", $receipt_item_id);
    $check_stmt->execute();
    $check_res = $check_stmt->get_result();

    if($check_res->num_rows == 0){
        // Insert new record for this receipt item
        $insert = $local_db->prepare("
            INSERT INTO store_receiving_data
            (branch, report_date, shift, item_name, item_id, quantity, units, supplier_id, supplier, employee_name, wms_receipt_item_id, date_created)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
        ");
        $insert->bind_param(
            "ssssidsissi",
            $branch,
            $report_date,
            $shift,
            $item_name,
            $store_item_id,
            $received_qty,
            $units,
            $supplier_id,
            $supplier_name,
            $employee_name,
            $receipt_item_id
        );
        $insert->execute();
        $output .= "Inserted: $item_code ($item_name) qty: $received_qty supplier: $supplier_name employee: $employee_name<br>";
    } else {
        // Already exists, update quantity, supplier, employee info
        $existing = $check_res->fetch_assoc();
        $update = $local_db->prepare("
            UPDATE store_receiving_data
            SET quantity=?, supplier_id=?, supplier=?, employee_name=?
            WHERE id=?
        ");
        $update->bind_param(
            "disii",
            $received_qty,
            $supplier_id,
            $supplier_name,
            $employee_name,
            $existing['id']
        );
        $update->execute();
        $output .= "Updated: $item_code ($item_name) qty: $received_qty supplier: $supplier_name employee: $employee_name<br>";
    }
}

echo $output;
?>

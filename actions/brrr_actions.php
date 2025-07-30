<?PHP



include '../init.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);


if(isset($_POST['mode']))
{
	$mode = $_POST['mode'];
} else {
	print_r('
		<script>
			app_alert("Warning"," The Mode you are trying to pass does not exist","warning","Ok","","no");
		</script>
	');
	exit();
}
$branch = $functions->GetSession('branch');
$transdate = $functions->GetSession('branchdate');
$shift = $functions->GetSession('shift');
$time_stamp = date("Y-m-d H:i:s");






if ($_POST['mode'] === 'dataupdateweeksemimonthexpense') {


    include '../db_config_main.php';  // Head Office config
/*
    $logfile = 'debug_expense_download.txt';
    file_put_contents($logfile, "--- START EXPENSE SYNC ---\n", FILE_APPEND);
*/
    $local = $db; // Local DB
    $main = @new mysqli(CON_HOST, CON_USER, CON_PASSWORD, CON_NAME); // HO DB

    $datefrom = $datefrom ?? null;
    $dateto = $dateto ?? null;


//    file_put_contents($logfile, "Mode: dataupdatedailyexpense\nBranch: $branch\nDate: $reportdate\n", FILE_APPEND);

    if (!$datefrom || !$branch) {
//        file_put_contents($logfile, "Missing branch or report date\n", FILE_APPEND);
        echo json_encode(['status' => 'error', 'message' => 'Missing branch or report date']);
        exit;
    }

    $datefrom = date("Y-m-01", strtotime($datefrom));
    $dateto = date("Y-m-t", strtotime($dateto));

    if ($main->connect_error || $local->connect_error) {
//        file_put_contents($logfile, "DB connection failed\n", FILE_APPEND);
        echo json_encode(['status' => 'error', 'message' => 'DB connection failed']);
        exit;
    }


    $sql = "SELECT id, branch, report_date, category, actual_amount, remarks, created_by, created_date
            FROM store_brrr_expense_ho_data
            WHERE branch = ? AND report_date BETWEEN ? AND ?";

    $stmt_main = $main->prepare($sql);
    if (!$stmt_main) {
//        file_put_contents($logfile, "Prepare failed: " . $main->error . "\n", FILE_APPEND);
        echo json_encode(['status' => 'error', 'message' => 'Prepare failed: ' . $main->error]);
        exit;
    }

    $stmt_main->bind_param("sss", $branch, $datefrom, $dateto);
    $stmt_main->execute();
    $result = $stmt_main->get_result();

    if (!$result || $result->num_rows === 0) {
//        file_put_contents($logfile, "No data fetched from HO for $branch | $datefrom to $dateto\n", FILE_APPEND);
        echo json_encode(['status' => 'error', 'message' => 'No expense data found from Head Office']);
        exit;
    }

    $deleteSQL = "DELETE FROM store_brrr_expense_ho_data WHERE branch = ? AND report_date BETWEEN ? AND ?";
    $stmt_delete = $local->prepare($deleteSQL);
    if ($stmt_delete) {
        $stmt_delete->bind_param("sss", $branch, $datefrom, $dateto);
        $stmt_delete->execute();
        $stmt_delete->close();
//        file_put_contents($logfile, "Old local data deleted\n", FILE_APPEND);
    } else {
        file_put_contents($logfile, "Failed to delete local data: " . $local->error . "\n", FILE_APPEND);
    }


    $insertSQL = "INSERT INTO store_brrr_expense_ho_data 
        (hid, report_date, branch, category, actual_amount, remarks, created_by, created_date) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt_local = $local->prepare($insertSQL);
    if (!$stmt_local) {
//        file_put_contents($logfile, "Prepare insert failed: " . $local->error . "\n", FILE_APPEND);
        echo json_encode(['status' => 'error', 'message' => 'Prepare failed on local insert: ' . $local->error]);
        exit;
    }

    $inserted = 0;
    while ($row = $result->fetch_assoc()) {

        $hid = $row['id'];
        $report_date = $row['report_date'];
        $branch = $row['branch'];
        $category = $row['category'] ?? '';
        $actual_amount = is_numeric($row['actual_amount']) ? $row['actual_amount'] : 0;
        $remarks = $row['remarks'] ?? '';
        $created_by = $row['created_by'] ?? '';
        $created_date = $row['created_date'] ?? '';

        $stmt_local->bind_param(
            "isssdsss",
            $hid,
            $report_date,
            $branch,
            $category,
            $actual_amount,
            $remarks,
            $created_by,
            $created_date
        );

        if ($stmt_local->execute()) {
            $inserted++;
//            file_put_contents($logfile, "Inserted: $hid | $category | ₱$actual_amount\n", FILE_APPEND);
        } else {
//            file_put_contents($logfile, "Insert failed for $hid: " . $stmt_local->error . "\n", FILE_APPEND);
        }
    }

//    file_put_contents($logfile, "--- END --- Inserted: $inserted rows\n", FILE_APPEND);

    echo json_encode([
        'status' => 'success',
        'message' => "Downloaded and inserted $inserted expense records from Head Office."
    ]);
    exit;
}









if ($_POST['mode'] === 'dataupdateweeksemimonthcogs') {

    include '../db_config_main.php';
    $local = $db;
    $main = @new mysqli(CON_HOST, CON_USER, CON_PASSWORD, CON_NAME);

    $reportdate = $_POST['datefrom'];
    $datefrom = date("Y-m-01", strtotime($reportdate));
    $dateto = date("Y-m-t", strtotime($reportdate));

    if ($main->connect_error || $local->connect_error) {
        echo json_encode(['status' => 'error', 'message' => 'DB connection failed']);
        exit;
    }


    $deleteSQL = "DELETE FROM store_brrr_cogs_data WHERE date_from = ? AND date_to = ?";
    $stmt_delete = $local->prepare($deleteSQL);
    if ($stmt_delete) {
        $stmt_delete->bind_param("ss", $datefrom, $dateto);
        $stmt_delete->execute();
        $stmt_delete->close();
    }

    $sql = "SELECT id, date_from, date_to, item_name, item_id, category, cost_pc, created_by, updated_by, date_created, date_updated 
            FROM store_cogs_setting_data 
            WHERE date_from = ? AND date_to = ?";
    $stmt_main = $main->prepare($sql);
    if (!$stmt_main) {
        echo json_encode(['status' => 'error', 'message' => 'Server error: Prepare failed on HO DB: ' . $main->error]);
        exit;
    }
    $stmt_main->bind_param("ss", $datefrom, $dateto);
    $stmt_main->execute();
    $result = $stmt_main->get_result();

    if (!$result || $result->num_rows === 0) {
        echo json_encode(['status' => 'error', 'message' => 'No COGS data found from HO']);
        exit;
    }


    $stmt_local = $local->prepare("INSERT INTO store_brrr_cogs_data 
        (hid, date_from, date_to, item_name, item_id, category, cost_pc, created_by, updated_by, date_created, date_updated) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    if (!$stmt_local) {
        echo json_encode(['status' => 'error', 'message' => 'Prepare failed on local insert: ' . $local->error]);
        exit;
    }

    $inserted = 0;
    while ($row = $result->fetch_assoc()) {
        $stmt_local->bind_param(
            "isssissssss",
            $row['id'],
            $row['date_from'],
            $row['date_to'],
            $row['item_name'],
            $row['item_id'],
            $row['category'],
            $row['cost_pc'],
            $row['created_by'],
            $row['updated_by'],
            $row['date_created'],
            $row['date_updated']
        );
        $stmt_local->execute();
        $inserted++;
    }

    echo json_encode([
        'status' => 'success',
        'message' => "Downloaded and inserted $inserted COGS records from Head Office."
    ]);
    exit;
}













if ($_POST['mode'] === 'dataupdatedailysummarydataho') {

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    include '../db_config_main.php';

    $local = $db;
    $main = @new mysqli(CON_HOST, CON_USER, CON_PASSWORD, CON_NAME);

    if ($main->connect_error) {
        file_put_contents('error_log.txt', "HO DB connection failed: " . $main->connect_error . "\n", FILE_APPEND);
        die(json_encode(['status' => 'error', 'message' => 'Failed to connect to HO DB']));
    }

    $branch = $functions->GetSession('branch');
    $reportdate = $functions->GetSession('branchdate');

    // Fetch data from HO
    $stmt = $main->prepare("SELECT * FROM store_summary_data WHERE branch = ? AND report_date = ?");
    $stmt->bind_param("ss", $branch, $reportdate);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        die(json_encode(['status' => 'empty', 'message' => 'No HO summary data found.']));
    }

    // Delete existing local data
    $delete = $local->prepare("DELETE FROM store_brrr_summary_ho_data WHERE branch = ? AND report_date = ?");
    $delete->bind_param("ss", $branch, $reportdate);
    $delete->execute();
    $delete->close();

    // INSERT (hid is from HO's id)
    $insert = $local->prepare("
        INSERT INTO store_brrr_summary_ho_data (
            hid, pid, bid, branch, report_date, shift, time_covered, slip_number,
            supervisor, inputtime, category, item_id, item_name, kilo_used,
            standard_yield, actual_yield, beginning, stock_in, t_in, frozendough, total,
            t_out, charges, snacks, bo, damaged, complimentary, should_be,
            actual_count, sold, unit_price, amount, date_created, date_updated,
            updated_by, posted, status, form_no, item_added_by
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    if (!$insert) {
        die(json_encode(['status' => 'error', 'message' => 'Prepare failed: ' . $local->error]));
    }

    $inserted = 0;

    while ($row = $result->fetch_assoc()) {

        // Assign values to variables before binding
        $hid             = $row['id'];
        $pid             = $row['pid'];
        $bid             = $row['bid'];
        $branch          = $row['branch'];
        $report_date     = $row['report_date'];
        $shift           = $row['shift'];
        $time_covered    = $row['time_covered'];
        $slip_number     = $row['slip_number'];
        $supervisor      = $row['supervisor'];
        $inputtime       = $row['inputtime'];
        $category        = $row['category'];
        $item_id         = $row['item_id'];
        $item_name       = $row['item_name'];
        $kilo_used       = $row['kilo_used'];
        $standard_yield  = $row['standard_yield'];
        $actual_yield    = $row['actual_yield'];
        $beginning       = $row['beginning'];
        $stock_in        = $row['stock_in'];
        $t_in            = $row['t_in'];
        $frozendough     = $row['frozendough'];
        $total           = $row['total'];
        $t_out           = $row['t_out'];
        $charges         = $row['charges'];
        $snacks          = $row['snacks'];
        $bo              = $row['bo'];
        $damaged         = $row['damaged'];
        $complimentary   = $row['complimentary'];
        $should_be       = $row['should_be'];
        $actual_count    = $row['actual_count'];
        $sold            = $row['sold'];
        $unit_price      = $row['unit_price'];
        $amount          = $row['amount'];
        $date_created    = $row['date_created'];
        $date_updated    = $row['date_updated'];
        $updated_by      = $row['updated_by'];
        $posted          = $row['posted'];
        $status          = $row['status'];
        $form_no         = $row['form_no'];
        $item_added_by   = $row['item_added_by'] ?? 'HO_SYNC';

        $insert->bind_param(
            "iiisssssssssiissddddddddddddddddsssssss",
            $hid, $pid, $bid, $branch, $report_date, $shift, $time_covered, $slip_number,
            $supervisor, $inputtime, $category, $item_id, $item_name, $kilo_used,
            $standard_yield, $actual_yield, $beginning, $stock_in, $t_in, $frozendough,
            $total, $t_out, $charges, $snacks, $bo, $damaged, $complimentary, $should_be,
            $actual_count, $sold, $unit_price, $amount, $date_created, $date_updated,
            $updated_by, $posted, $status, $form_no, $item_added_by
        );

        if ($insert->execute()) {
            $inserted++;
        } else {
            file_put_contents('error_log.txt', "Insert failed: " . $insert->error . "\n", FILE_APPEND);
        }
    }

    $stmt->close();
    $insert->close();
    $main->close();
    $local->close();

    echo json_encode(['status' => 'success', 'message' => "Successfully inserted $inserted rows."]);
}




















if ($_POST['mode'] === 'dataupdatedailyexpense') {


    include '../db_config_main.php';  // Head Office config
/*
    $logfile = 'debug_expense_download.txt';
    file_put_contents($logfile, "--- START EXPENSE SYNC ---\n", FILE_APPEND);
*/
    $local = $db; // Local DB
    $main = @new mysqli(CON_HOST, CON_USER, CON_PASSWORD, CON_NAME); // HO DB

    $reportdate = $transdate ?? null;


//    file_put_contents($logfile, "Mode: dataupdatedailyexpense\nBranch: $branch\nDate: $reportdate\n", FILE_APPEND);

    if (!$reportdate || !$branch) {
//        file_put_contents($logfile, "Missing branch or report date\n", FILE_APPEND);
        echo json_encode(['status' => 'error', 'message' => 'Missing branch or report date']);
        exit;
    }

    $datefrom = date("Y-m-01", strtotime($reportdate));
    $dateto = date("Y-m-t", strtotime($reportdate));

    if ($main->connect_error || $local->connect_error) {
//        file_put_contents($logfile, "DB connection failed\n", FILE_APPEND);
        echo json_encode(['status' => 'error', 'message' => 'DB connection failed']);
        exit;
    }


    $sql = "SELECT id, branch, report_date, category, actual_amount, remarks, created_by, created_date
            FROM store_brrr_expense_ho_data
            WHERE branch = ? AND report_date BETWEEN ? AND ?";

    $stmt_main = $main->prepare($sql);
    if (!$stmt_main) {
//        file_put_contents($logfile, "Prepare failed: " . $main->error . "\n", FILE_APPEND);
        echo json_encode(['status' => 'error', 'message' => 'Prepare failed: ' . $main->error]);
        exit;
    }

    $stmt_main->bind_param("sss", $branch, $datefrom, $dateto);
    $stmt_main->execute();
    $result = $stmt_main->get_result();

    if (!$result || $result->num_rows === 0) {
//        file_put_contents($logfile, "No data fetched from HO for $branch | $datefrom to $dateto\n", FILE_APPEND);
        echo json_encode(['status' => 'error', 'message' => 'No expense data found from Head Office']);
        exit;
    }

    $deleteSQL = "DELETE FROM store_brrr_expense_ho_data WHERE branch = ? AND report_date BETWEEN ? AND ?";
    $stmt_delete = $local->prepare($deleteSQL);
    if ($stmt_delete) {
        $stmt_delete->bind_param("sss", $branch, $datefrom, $dateto);
        $stmt_delete->execute();
        $stmt_delete->close();
//        file_put_contents($logfile, "Old local data deleted\n", FILE_APPEND);
    } else {
        file_put_contents($logfile, "Failed to delete local data: " . $local->error . "\n", FILE_APPEND);
    }


    $insertSQL = "INSERT INTO store_brrr_expense_ho_data 
        (hid, report_date, branch, category, actual_amount, remarks, created_by, created_date) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt_local = $local->prepare($insertSQL);
    if (!$stmt_local) {
//        file_put_contents($logfile, "Prepare insert failed: " . $local->error . "\n", FILE_APPEND);
        echo json_encode(['status' => 'error', 'message' => 'Prepare failed on local insert: ' . $local->error]);
        exit;
    }

    $inserted = 0;
    while ($row = $result->fetch_assoc()) {

        $hid = $row['id'];
        $report_date = $row['report_date'];
        $branch = $row['branch'];
        $category = $row['category'] ?? '';
        $actual_amount = is_numeric($row['actual_amount']) ? $row['actual_amount'] : 0;
        $remarks = $row['remarks'] ?? '';
        $created_by = $row['created_by'] ?? '';
        $created_date = $row['created_date'] ?? '';

        $stmt_local->bind_param(
            "isssdsss",
            $hid,
            $report_date,
            $branch,
            $category,
            $actual_amount,
            $remarks,
            $created_by,
            $created_date
        );

        if ($stmt_local->execute()) {
            $inserted++;
//            file_put_contents($logfile, "Inserted: $hid | $category | ₱$actual_amount\n", FILE_APPEND);
        } else {
//            file_put_contents($logfile, "Insert failed for $hid: " . $stmt_local->error . "\n", FILE_APPEND);
        }
    }

//    file_put_contents($logfile, "--- END --- Inserted: $inserted rows\n", FILE_APPEND);

    echo json_encode([
        'status' => 'success',
        'message' => "Downloaded and inserted $inserted expense records from Head Office."
    ]);
    exit;
}








if ($_POST['mode'] === 'dataupdatedailycogs') {

    include '../db_config_main.php';
    $local = $db;
    $main = @new mysqli(CON_HOST, CON_USER, CON_PASSWORD, CON_NAME);

    $reportdate = $_POST['reportdate'];
    $datefrom = date("Y-m-01", strtotime($reportdate));
    $dateto = date("Y-m-t", strtotime($reportdate));

    if ($main->connect_error || $local->connect_error) {
        echo json_encode(['status' => 'error', 'message' => 'DB connection failed']);
        exit;
    }


    $deleteSQL = "DELETE FROM store_brrr_cogs_data WHERE date_from = ? AND date_to = ?";
    $stmt_delete = $local->prepare($deleteSQL);
    if ($stmt_delete) {
        $stmt_delete->bind_param("ss", $datefrom, $dateto);
        $stmt_delete->execute();
        $stmt_delete->close();
    }

    $sql = "SELECT id, date_from, date_to, item_name, item_id, category, cost_pc, created_by, updated_by, date_created, date_updated 
            FROM store_cogs_setting_data 
            WHERE date_from = ? AND date_to = ?";
    $stmt_main = $main->prepare($sql);
    if (!$stmt_main) {
        echo json_encode(['status' => 'error', 'message' => 'Server error: Prepare failed on HO DB: ' . $main->error]);
        exit;
    }
    $stmt_main->bind_param("ss", $datefrom, $dateto);
    $stmt_main->execute();
    $result = $stmt_main->get_result();

    if (!$result || $result->num_rows === 0) {
        echo json_encode(['status' => 'error', 'message' => 'No COGS data found from HO']);
        exit;
    }


    $stmt_local = $local->prepare("INSERT INTO store_brrr_cogs_data 
        (hid, date_from, date_to, item_name, item_id, category, cost_pc, created_by, updated_by, date_created, date_updated) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    if (!$stmt_local) {
        echo json_encode(['status' => 'error', 'message' => 'Prepare failed on local insert: ' . $local->error]);
        exit;
    }

    $inserted = 0;
    while ($row = $result->fetch_assoc()) {
        $stmt_local->bind_param(
            "isssissssss",
            $row['id'],
            $row['date_from'],
            $row['date_to'],
            $row['item_name'],
            $row['item_id'],
            $row['category'],
            $row['cost_pc'],
            $row['created_by'],
            $row['updated_by'],
            $row['date_created'],
            $row['date_updated']
        );
        $stmt_local->execute();
        $inserted++;
    }

    echo json_encode([
        'status' => 'success',
        'message' => "Downloaded and inserted $inserted COGS records from Head Office."
    ]);
    exit;
}




if ($_POST['mode'] === 'posttosummaryexpenseinput') {

    $reportdate = $functions->GetSession('branchdate');
    $branch = $functions->GetSession('branch');

    if (empty($reportdate)) {
        echo '
            <script>
                app_alert("System Message", "No report date found. Please select a date before saving.", "warning");
            </script>
        ';
        exit;
    }

    $query = "SELECT category, actual_amount FROM store_brrr_expense_data WHERE branch = ? AND report_date = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("ss", $branch, $reportdate);
    $stmt->execute();
    $result = $stmt->get_result();

    $categories = [];
    $amounts = [];
    $total = 0;

    while ($row = $result->fetch_assoc()) {
        $categories[] = $row['category'];
        $amounts[] = floatval($row['actual_amount']);
        $total += floatval($row['actual_amount']);
    }

    $category_json = json_encode($categories);
    $amount_json = json_encode($amounts);


    $check = $db->prepare("SELECT id FROM store_brrr_summary_data WHERE branch = ? AND report_date = ?");
    $check->bind_param("ss", $branch, $reportdate);
    $check->execute();
    $checkResult = $check->get_result();

    if ($checkResult->num_rows > 0) {

        $updateSummary = $db->prepare("UPDATE store_brrr_summary_data 
            SET category_json = ?, amount_json = ?, actual_amount_total = ? 
            WHERE branch = ? AND report_date = ?");
        $updateSummary->bind_param("ssdss", $category_json, $amount_json, $total, $branch, $reportdate);

        if ($updateSummary->execute()) {

            $updateStatus = $db->prepare("UPDATE store_brrr_expense_data 
                SET status = 1 
                WHERE branch = ? AND report_date = ?");
            $updateStatus->bind_param("ss", $branch, $reportdate);
            $updateStatus->execute();

            echo '
                <script>
                    app_alert("System Message", "Summary (Expense Input) updated successfully.", "success");
                </script>
            ';
        } else {
            echo '
                <script>
                    app_alert("System Message", "Failed to update summary. Please try again.", "error");
                </script>
            ';
        }

    } else {
        echo '
            <script>
                app_alert("System Message", "No existing summary found to update. Please post Headcount first.", "info");
            </script>
        ';
    }
}






if ($_POST['mode'] === 'expenseinputsave') {
    
    if (empty($transdate)) {
        echo '
            <script>
                app_alert("System Message", "No report date found. Please select a date before saving.", "warning");
            </script>
        ';
        exit;
    }

    
    
    try {
    
        $user = $_SESSION['appstore_appnameuser']; // <== ADD THIS LINE

        if (empty($_POST['entries'])) {
            throw new Exception('No expense entries found');
        }

        $entries = json_decode($_POST['entries'], true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Invalid entries format');
        }		
		
        $db->begin_transaction();

        $stmt_del = $db->prepare("DELETE FROM store_brrr_expense_data
                               WHERE branch = ? AND report_date = ?");
        $stmt_del->bind_param("ss", $branch, $transdate);
        $stmt_del->execute();

        $stmt = $db->prepare("INSERT INTO store_brrr_expense_data
                            (report_date, branch, category, actual_amount, remarks, created_by, created_date)
                            VALUES (?, ?, ?, ?, ?, ?, ?)");

        foreach ($entries as $entry) {
            $category = $entry['category'];
            $actual_amount = isset($entry['actual_amount']) ? (float)$entry['actual_amount'] : 0;
            $remarks = isset($entry['remarks']) ? $entry['remarks'] : '';

            $stmt->bind_param("sssdsss", 
                $transdate, 
                $branch, 
                $category,
                $actual_amount,
                $remarks,
                $user,
                $time_stamp
            );
            $stmt->execute();
        }

        $db->commit();

        echo json_encode([
            'success' => true,
            'message' => 'Expenses saved successfully'
        ]);
        
    } catch (Exception $e) {
        $db->rollback();
        echo json_encode([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
    exit();
}







if ($_POST['mode'] === 'posttosummaryoverhead') {

    $reportdate = $functions->GetSession('branchdate');
    $branch = $functions->GetSession('branch');

    if (empty($reportdate)) {
        echo '
            <script>
                app_alert("System Message", "No report date found. Please select a date before saving.", "warning");
            </script>
        ';
        exit;
    }


    $query = "SELECT 
                SUM(CASE WHEN LOWER(position) LIKE '%baker%' THEN 1 ELSE 0 END) AS baker_headcount,
                SUM(CASE WHEN LOWER(position) NOT LIKE '%baker%' THEN 1 ELSE 0 END) AS selling_headcount,
                COUNT(*) AS total_headcount
              FROM store_brrr_overhead_data
              WHERE branch = ? AND report_date = ?";

    $stmt = $db->prepare($query);
    $stmt->bind_param("ss", $branch, $reportdate);
    $stmt->execute();
    $stmt->bind_result($baker_headcount, $selling_headcount, $total_headcount);
    $stmt->fetch();
    $stmt->close();


    $insert = $db->prepare("INSERT INTO store_brrr_summary_data 
        (branch, report_date, baker_headcount, selling_headcount, total_headcount) 
        VALUES (?, ?, ?, ?, ?)");

    $insert->bind_param("ssiii", $branch, $reportdate, $baker_headcount, $selling_headcount, $total_headcount);

    if ($insert->execute()) {
        $update = $db->prepare("UPDATE store_brrr_overhead_data SET status = 1 WHERE branch = ? AND report_date = ?");
        $update->bind_param("ss", $branch, $reportdate);
        $update->execute();

        echo '
            <script>
                app_alert("Success", "Overhead count posted and summary saved.", "success");
            </script>
        ';
    } else {
        echo '
            <script>
                app_alert("Error", "Failed to insert into summary table.", "error");
            </script>
        ';
    }

    $insert->close();
}







if ($_POST['mode'] === 'overheadcountsave') {
    $time_stamp = date("Y-m-d H:i:s");
    $branch = $_POST['branch'];
    $report_date = $_POST['report_date'];
    $user = $_SESSION['appstore_appnameuser'];
    
    $entries = json_decode($_POST['entries'], true);
    
	
	if (empty($transdate)) {
        echo '
            <script>
                app_alert("System Message", "No report date found. Please select a date before saving.", "warning");
            </script>
        ';
        exit;
    }

	
	
    $stmt_del = $db->prepare("DELETE FROM store_brrr_overhead_data WHERE branch = ? AND report_date = ?");
    $stmt_del->bind_param("ss", $branch, $report_date);
    $stmt_del->execute();


    $stmt = $db->prepare("INSERT INTO store_brrr_overhead_data 
        (report_date, branch, idcode, acctname, position, baker, selling, created_date, created_by) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");


    if (!$stmt) {
        echo "Prepare failed: " . $db->error;
        exit;
    }

    foreach ($entries as $emp) {
        $acctname = $emp['acctname'];
        $position = $emp['position'];
        $idcode = $emp['idcode'];
        
        if (stripos($position, 'baker') !== false) {

		    $baker = 1;
		    $selling = 0;
		} else {
		    $baker = 0;
		    $selling = 1;
		}

        
        $stmt->bind_param("sssssiiss", $report_date, $branch, $idcode, $acctname, $position, $baker, $selling, $time_stamp, $user);
        $stmt->execute();
    }

    echo "success";
    exit;
}



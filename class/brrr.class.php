<?php

class brrr
{	
	
	
	public function notsubmiteddetection($branch, $datefrom, $dateto, $db)
	{
	    // Prepare overhead query
	    $sql1 = "SELECT id FROM store_brrr_overhead_data 
	             WHERE branch = ? 
	               AND report_date BETWEEN ? AND ? 
	               AND status = 0 
	             LIMIT 1";
	
	    $stmt1 = $db->prepare($sql1);
	    $stmt1->bind_param("sss", $branch, $datefrom, $dateto);
	    $stmt1->execute();
	    $result1 = $stmt1->get_result();
	    $hasOverhead = $result1->num_rows > 0;
	    $stmt1->close();
	
	    // If overhead already has unsubmitted, return 1 early
	    if ($hasOverhead) {
	        return 1;
	    }
	
	    // Prepare expense query
	    $sql2 = "SELECT id FROM store_brrr_expense_data 
	             WHERE branch = ? 
	               AND report_date BETWEEN ? AND ? 
	               AND status = 0 
	             LIMIT 1";
	
	    $stmt2 = $db->prepare($sql2);
	    $stmt2->bind_param("sss", $branch, $datefrom, $dateto);
	    $stmt2->execute();
	    $result2 = $stmt2->get_result();
	    $hasExpense = $result2->num_rows > 0;
	    $stmt2->close();
	
	    // Return 1 if found in either, otherwise 0
	    return ($hasExpense) ? 1 : 0;
	}


	public function presentdaysviaidcode($branch, $idcode, $datefrom, $dateto, $db)
	{
	    $stmt = $db->prepare("SELECT COUNT(*) as present_days FROM store_brrr_overhead_data WHERE report_date BETWEEN ? AND ? AND branch = ? AND idcode = ?");
	
	    if (!$stmt) {
	        return "Prepare failed: " . $db->error;
	    }
	
	    $stmt->bind_param("ssss", $datefrom, $dateto, $branch, $idcode);
	
	    if (!$stmt->execute()) {
	        return "Execute failed: " . $stmt->error;
	    }
	
	    $result = $stmt->get_result();
	    $row = $result->fetch_assoc();
	
	    $stmt->close();
	
	    return (int)$row['present_days']; // i-return as integer
	}

	
	
	public function getZeroSalaryBakers($branch, $datefrom, $dateto, $db) {
	    $idcodes = [];
	
	    // Step 1: Kuhaon lang ang mga baker
	    $stmt = $db->prepare("SELECT DISTINCT idcode 
	                          FROM store_brrr_overhead_data 
	                          WHERE report_date BETWEEN ? AND ? 
	                          AND branch = ? 
	                          AND LOWER(position) LIKE '%baker%'");
	    if (!$stmt) {
	        return "Prepare failed (Step 1): " . $db->error;
	    }
	
	    $stmt->bind_param("sss", $datefrom, $dateto, $branch);
	    $stmt->execute();
	    $result = $stmt->get_result();
	
	    while ($row = $result->fetch_assoc()) {
	        $idcodes[] = $row['idcode'];
	    }
	    $stmt->close();
	
	    if (empty($idcodes)) return [];
	
	    // Step 2: Pangitaon kinsa sa mga bakers ang walay sweldo
	    $placeholders = implode(',', array_fill(0, count($idcodes), '?'));
	    $types = str_repeat('s', count($idcodes));
	    $sql = "SELECT lastname FROM tbl_employees_ho WHERE salary_daily = 0 AND idcode IN ($placeholders)";
	
	    $stmt = $db->prepare($sql);
	    if (!$stmt) return "Prepare failed (Step 2): " . $db->error;
	
	    $stmt->bind_param($types, ...$idcodes);
	    $stmt->execute();
	    $result = $stmt->get_result();
	
	    $acctnames = [];
	    while ($row = $result->fetch_assoc()) {
	        $acctnames[] = $row['lastname'];
	    }
	    $stmt->close();
	
	    return $acctnames;
	}
	

	public function getZeroSalaryBakersconn($branch, $datefrom, $dateto, $db) {
	    $idcodes = [];
	
	    // Step 1: Kuhaon lang ang mga baker
	    $stmt = $db->prepare("SELECT DISTINCT idcode 
	                          FROM store_brrr_overhead_data 
	                          WHERE report_date BETWEEN ? AND ? 
	                          AND branch = ? 
	                          AND LOWER(position) LIKE '%baker%'");
	    if (!$stmt) {
	        return "Prepare failed (Step 1): " . $db->error;
	    }
	
	    $stmt->bind_param("sss", $datefrom, $dateto, $branch);
	    $stmt->execute();
	    $result = $stmt->get_result();
	
	    while ($row = $result->fetch_assoc()) {
	        $idcodes[] = $row['idcode'];
	    }
	    $stmt->close();
	
	    if (empty($idcodes)) return [];
	
	    // Step 2: Pangitaon kinsa sa mga bakers ang walay sweldo
	    $placeholders = implode(',', array_fill(0, count($idcodes), '?'));
	    $types = str_repeat('s', count($idcodes));
	    $sql = "SELECT lastname FROM tbl_employees WHERE salary_daily = 0 AND idcode IN ($placeholders)";
	
	    $stmt = $db->prepare($sql);
	    if (!$stmt) return "Prepare failed (Step 2): " . $db->error;
	
	    $stmt->bind_param($types, ...$idcodes);
	    $stmt->execute();
	    $result = $stmt->get_result();
	
	    $acctnames = [];
	    while ($row = $result->fetch_assoc()) {
	        $acctnames[] = $row['lastname'];
	    }
	    $stmt->close();
	
	    return $acctnames;
	}
	
	
	
	public function getZeroSalarySelling($branch, $datefrom, $dateto, $db) {
	    $idcodes = [];
	
	    // Step 1: Kuhaon tanan dili baker
	    $stmt = $db->prepare("SELECT DISTINCT idcode 
	                          FROM store_brrr_overhead_data 
	                          WHERE report_date BETWEEN ? AND ? 
	                          AND branch = ? 
	                          AND LOWER(position) NOT LIKE '%baker%'");
	    if (!$stmt) {
	        return "Prepare failed (Step 1): " . $db->error;
	    }
	
	    $stmt->bind_param("sss", $datefrom, $dateto, $branch);
	    $stmt->execute();
	    $result = $stmt->get_result();
	
	    while ($row = $result->fetch_assoc()) {
	        $idcodes[] = $row['idcode'];
	    }
	    $stmt->close();
	
	    if (empty($idcodes)) return [];
	
	    // Step 2: Pangitaon kinsa sa non-bakers ang walay sweldo
	    $placeholders = implode(',', array_fill(0, count($idcodes), '?'));
	    $types = str_repeat('s', count($idcodes));
	    $sql = "SELECT lastname FROM tbl_employees_ho WHERE salary_daily = 0 AND idcode IN ($placeholders)";
	
	    $stmt = $db->prepare($sql);
	    if (!$stmt) return "Prepare failed (Step 2): " . $db->error;
	
	    $stmt->bind_param($types, ...$idcodes);
	    $stmt->execute();
	    $result = $stmt->get_result();
	
	    $acctnames = [];
	    while ($row = $result->fetch_assoc()) {
	        $acctnames[] = $row['lastname'];
	    }
	    $stmt->close();
	
	    return $acctnames;
	}	
	
	public function getZeroSalarySellingconn($branch, $datefrom, $dateto, $db) {
	    $idcodes = [];
	
	    // Step 1: Kuhaon tanan dili baker
	    $stmt = $db->prepare("SELECT DISTINCT idcode 
	                          FROM store_brrr_overhead_data 
	                          WHERE report_date BETWEEN ? AND ? 
	                          AND branch = ? 
	                          AND LOWER(position) NOT LIKE '%baker%'");
	    if (!$stmt) {
	        return "Prepare failed (Step 1): " . $db->error;
	    }
	
	    $stmt->bind_param("sss", $datefrom, $dateto, $branch);
	    $stmt->execute();
	    $result = $stmt->get_result();
	
	    while ($row = $result->fetch_assoc()) {
	        $idcodes[] = $row['idcode'];
	    }
	    $stmt->close();
	
	    if (empty($idcodes)) return [];
	
	    // Step 2: Pangitaon kinsa sa non-bakers ang walay sweldo
	    $placeholders = implode(',', array_fill(0, count($idcodes), '?'));
	    $types = str_repeat('s', count($idcodes));
	    $sql = "SELECT lastname FROM tbl_employees WHERE salary_daily = 0 AND idcode IN ($placeholders)";
	
	    $stmt = $db->prepare($sql);
	    if (!$stmt) return "Prepare failed (Step 2): " . $db->error;
	
	    $stmt->bind_param($types, ...$idcodes);
	    $stmt->execute();
	    $result = $stmt->get_result();
	
	    $acctnames = [];
	    while ($row = $result->fetch_assoc()) {
	        $acctnames[] = $row['lastname'];
	    }
	    $stmt->close();
	
	    return $acctnames;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	public function summaryCogsHo($branch, $dateselectfrom, $dateselectto, $db)
	{

	
	    $sql = "SELECT item_id, item_name, SUM(sold) AS total_sold FROM store_brrr_summary_ho_data WHERE branch = ? AND report_date BETWEEN ? AND ? GROUP BY item_id";
	    
	    $stmt = $db->prepare($sql);
	    $stmt->bind_param("sss", $branch, $dateselectfrom, $dateselectto);
	    $stmt->execute();
	    $result = $stmt->get_result();
	
	    $cogs_breakdown = [];
	    $totalcogs = 0;
	    
	    $firstday = date('Y-m-01', strtotime($dateselectfrom));
		$lastday   = date('Y-m-t', strtotime($dateselectfrom));
	
	    while ($row = $result->fetch_assoc()) {
	        $itemid = $row['item_id'];
	        $itemname = $row['item_name'];
	        $quantitysold = $row['total_sold'];
		        
	        $costpc = $this->getCostPc($itemid, $firstday, $lastday, $db);
	
	        $cogsamount = $quantitysold * $costpc;
	        $totalcogs += $cogsamount;
	
	        $cogs_breakdown[] = [
	            'item_id' => $itemid,
	            'item_name' => $itemname,
	            'sold' => $quantitysold,
	            'cost_pc' => $costpc,
	            'total_cogs' => $cogsamount
	        ];
	    }
	
	    return [
	        'breakdown' => $cogs_breakdown,
	        'total_cogs' => $totalcogs
	    ];
	}

	
	public function salessummaryho($branch, $reportdate, $db)
	{	
	    $sql = "SELECT SUM(amount) AS TOTALAMOUNT FROM store_brrr_summary_ho_data WHERE report_date = ? AND branch = ?";
	    $stmt = $db->prepare($sql);
	    $stmt->bind_param("ss", $reportdate, $branch);
	    $stmt->execute();
	    $result = $stmt->get_result();
	    
	    $row = $result->fetch_assoc();
	    return floatval($row['TOTALAMOUNT'] ?? 0);
	}

	
	
	public function summarydatahodetectionexist($datefrom, $dateto, $branch, $db, $maindb)
	{
	    // Head Office Query
	    $sql_main = "SELECT 
	                    IFNULL(SUM(sold), 0) AS totalsold, 
	                    IFNULL(SUM(amount), 0) AS totalamount 
	                 FROM store_summary_data 
	                 WHERE report_date BETWEEN ? AND ? AND branch = ?";
	    
	    $stmt_main = $maindb->prepare($sql_main);
	    $stmt_main->bind_param("sss", $datefrom, $dateto, $branch);
	    $stmt_main->execute();
	    $result_main = $stmt_main->get_result()->fetch_assoc();
	    $stmt_main->close();
	    
	    $main_sold = $result_main['totalsold'];
	    $main_amount = $result_main['totalamount'];
	
	    // Local Table Query (Correct Table)
	    $sql_local = "SELECT 
	                    IFNULL(SUM(sold), 0) AS totalsold, 
	                    IFNULL(SUM(amount), 0) AS totalamount 
	                 FROM store_brrr_summary_ho_data 
	                 WHERE report_date BETWEEN ? AND ? AND branch = ?";
	
	    $stmt_local = $db->prepare($sql_local);
	    $stmt_local->bind_param("sss", $datefrom, $dateto, $branch);
	    $stmt_local->execute();
	    $result_local = $stmt_local->get_result()->fetch_assoc();
	    $stmt_local->close();
	
	    $local_sold = $result_local['totalsold'];
	    $local_amount = $result_local['totalamount'];
	
	    // Compare
	    if ($main_sold == $local_sold && $main_amount == $local_amount) {
	        return 0; // same, no need to update
	    } else {
	        return 1; // not same, needs update
	    }
	}
		
	
	
	public function getAllUniqueIdcodes($datefrom, $dateto, $db)
	{
	    $data = [];
	
	    $sql = "SELECT DISTINCT idcode 
	            FROM store_brrr_overhead_data 
	            WHERE report_date BETWEEN ? AND ?";
	
	    $stmt = $db->prepare($sql);
	    if (!$stmt) {
	        die("Prepare failed: " . $db->error);
	    }
	
	    $stmt->bind_param("ss", $datefrom, $dateto);
	    $stmt->execute();
	    $result = $stmt->get_result();
	
	    while ($row = $result->fetch_assoc()) {
	        if (!empty($row['idcode'])) {
	            $data[] = $row['idcode'];
	        }
	    }
	
	    $stmt->close();
	    return $data;
	}
	
	public function salaryemployeemonthly($column, $idcode, $db)
	{

	    $allowed_columns = ['salary_monthly', 'salary_daily'];
	
	    if (!in_array($column, $allowed_columns)) {
	        die("Invalid column requested.");
	    }
	
	    $sql = "SELECT `$column` FROM tbl_employees_ho WHERE idcode = ?";
	    $stmt = $db->prepare($sql);
	    if (!$stmt) {
	        die("Prepare failed: " . $db->error);
	    }
	
	    $stmt->bind_param("s", $idcode);
	    $stmt->execute();
	    $stmt->bind_result($value);
	    $stmt->fetch();
	    $stmt->close();
	
	    return $value;
	}
	
	public function salaryemployeemonthlyconn($column, $idcode, $db)
	{

	    $allowed_columns = ['salary_monthly', 'salary_daily'];
	
	    if (!in_array($column, $allowed_columns)) {
	        die("Invalid column requested.");
	    }
	
	    $sql = "SELECT `$column` FROM tbl_employees WHERE idcode = ?";
	    $stmt = $db->prepare($sql);
	    if (!$stmt) {
	        die("Prepare failed: " . $db->error);
	    }
	
	    $stmt->bind_param("s", $idcode);
	    $stmt->execute();
	    $stmt->bind_result($value);
	    $stmt->fetch();
	    $stmt->close();
	
	    return $value;
	}

	
	
	public function categorySelectValue($column, $category, $db)
	{
	    $columnValue = '';
	
	    $sql = "SELECT $column FROM store_brrr_category WHERE category_name = ?";
	    $stmt = $db->prepare($sql);
	    $stmt->bind_param("s", $category);
	    $stmt->execute();
	    $result = $stmt->get_result();
	
	    if ($row = $result->fetch_assoc()) {
	        $columnValue = $row[$column];
	    }
	
	    return $columnValue;
	}

	public function summaryCogs($branch, $dateselectfrom, $dateselectto, $db)
	{

	
	    $sql = "SELECT item_id, item_name, SUM(sold) AS total_sold FROM store_summary_data WHERE branch = ? AND report_date BETWEEN ? AND ? GROUP BY item_id";
	    
	    $stmt = $db->prepare($sql);
	    $stmt->bind_param("sss", $branch, $dateselectfrom, $dateselectto);
	    $stmt->execute();
	    $result = $stmt->get_result();
	
	    $cogs_breakdown = [];
	    $totalcogs = 0;
	    
	    $firstday = date('Y-m-01', strtotime($dateselectfrom));
		$lastday   = date('Y-m-t', strtotime($dateselectfrom));
	
	    while ($row = $result->fetch_assoc()) {
	        $itemid = $row['item_id'];
	        $itemname = $row['item_name'];
	        $quantitysold = $row['total_sold'];
		        
	        $costpc = $this->getCostPcconn($itemid, $firstday, $lastday, $db);
	
	        $cogsamount = $quantitysold * $costpc;
	        $totalcogs += $cogsamount;
	
	        $cogs_breakdown[] = [
	            'item_id' => $itemid,
	            'item_name' => $itemname,
	            'sold' => $quantitysold,
	            'cost_pc' => $costpc,
	            'total_cogs' => $cogsamount
	        ];
	    }
	
	    return [
	        'breakdown' => $cogs_breakdown,
	        'total_cogs' => $totalcogs
	    ];
	}
	
	public function summaryCogsconn($branch, $dateselectfrom, $dateselectto, $db)
	{

	
	    $sql = "SELECT item_id, item_name, SUM(sold) AS total_sold FROM store_summary_data WHERE branch = ? AND report_date BETWEEN ? AND ? GROUP BY item_id";
	    
	    $stmt = $db->prepare($sql);
	    $stmt->bind_param("sss", $branch, $dateselectfrom, $dateselectto);
	    $stmt->execute();
	    $result = $stmt->get_result();
	
	    $cogs_breakdown = [];
	    $totalcogs = 0;
	    
	    $firstday = date('Y-m-01', strtotime($dateselectfrom));
		$lastday   = date('Y-m-t', strtotime($dateselectfrom));
	
	    while ($row = $result->fetch_assoc()) {
	        $itemid = $row['item_id'];
	        $itemname = $row['item_name'];
	        $quantitysold = $row['total_sold'];
		        
	        $costpc = $this->getCostPcconn($itemid, $firstday, $lastday, $db);
	
	        $cogsamount = $quantitysold * $costpc;
	        $totalcogs += $cogsamount;
	
	        $cogs_breakdown[] = [
	            'item_id' => $itemid,
	            'item_name' => $itemname,
	            'sold' => $quantitysold,
	            'cost_pc' => $costpc,
	            'total_cogs' => $cogsamount
	        ];
	    }
	
	    return [
	        'breakdown' => $cogs_breakdown,
	        'total_cogs' => $totalcogs
	    ];
	}
	
	
	public function getCostPc($itemid, $datefrom, $dateto, $db)
	{
	    $sql = "SELECT cost_pc FROM store_brrr_cogs_data WHERE item_id = ? AND date_from = ? AND date_to = ? LIMIT 1";
	
	    $stmt = $db->prepare($sql);
	    $stmt->bind_param("iss", $itemid, $datefrom, $dateto);
	    $stmt->execute();
	    $result = $stmt->get_result();
	    $row = $result->fetch_assoc();
	
	    return $row['cost_pc'] ?? 0; 

	}
	
	public function getCostPcconn($itemid, $datefrom, $dateto, $db)
	{
	    $sql = "SELECT cost_pc FROM store_cogs_setting_data WHERE item_id = ? AND date_from = ? AND date_to = ? LIMIT 1";
	
	    $stmt = $db->prepare($sql);
	    $stmt->bind_param("iss", $itemid, $datefrom, $dateto);
	    $stmt->execute();
	    $result = $stmt->get_result();
	    $row = $result->fetch_assoc();
	
	    return $row['cost_pc'] ?? 0; 

	}

	
	
	
	
	
	
	public function otherexpensedetectionexistwms($datefrom, $dateto, $branch, $db, $maindb)
	{
	    $sql = "SELECT wbo.id, wbo.item_description
	            FROM wms_branch_order wbo
	            JOIN wms_order_request wor ON wbo.control_no = wor.control_no
	            WHERE wbo.branch = ?
	              AND wor.delivery_date BETWEEN ? AND ?
	              AND (wor.status = 'CLOSED' OR wor.status = 'In-Transit')";
	
	    $stmt = $maindb->prepare($sql);
	    if (!$stmt) {
	        error_log("WMS JOIN query failed: " . $maindb->error);
	        return 0;
	    }
	
	    $stmt->bind_param("sss", $branch, $datefrom, $dateto);
	    $stmt->execute();
	    $result = $stmt->get_result();
	
	    $valid_ids = [];
	    while ($row = $result->fetch_assoc()) {
	        $desc = strtoupper(trim($row['item_description']));
	        if (
	            strpos($desc, 'JS -') === 0 ||
	            strpos($desc, 'PM -') === 0 ||
	            strpos($desc, 'OS -') === 0 ||
	            strpos($desc, 'BM -') === 0
	        ) {
	            $valid_ids[] = (string)$row['id'];
	        }
	    }
	    $stmt->close();
	

	
	    if (empty($valid_ids)) {
	        return 0;
	    }
	
	    $total_matched = 0;
	    $chunks = array_chunk($valid_ids, 500);
	
	    foreach ($chunks as $chunk) {
	        $placeholders = implode(',', array_fill(0, count($chunk), '?'));
	        $sql_local = "SELECT COUNT(hid) AS matched FROM store_brrr_wms_data WHERE hid IN ($placeholders)";
	        $stmt_local = $db->prepare($sql_local);
	        if (!$stmt_local) {
	            error_log("Local query prepare failed: " . $db->error);
	            continue;
	        }
	
	        $types = str_repeat('s', count($chunk));
	        $stmt_local->bind_param($types, ...$chunk);
	        $stmt_local->execute();
	        $result_local = $stmt_local->get_result();
	        $matched = $result_local->fetch_assoc()['matched'] ?? 0;
	        $total_matched += (int)$matched;
	        $stmt_local->close();
	    }
	
	
	    return ($total_matched === count($valid_ids)) ? 0 : 1;
	}

	
	
	




	public function dayscount($datefrom, $dateto)
	{
	    $start = new DateTime($datefrom);
	    $end = new DateTime($dateto);
	    
	    $interval = $start->diff($end);
	    
	    return $interval->days + 1;
	}
	
	public function SubmitSummaryToServer($branch, $transdate, $localconn, $liveconn)
	{
	    $table = "store_brrr_summary_data";
	

	    $sql = "SELECT * FROM $table WHERE branch = ? AND report_date = ?";
	    $stmt = $localconn->prepare($sql);
	    $stmt->bind_param("ss", $branch, $transdate);
	    $stmt->execute();
	    $result = $stmt->get_result();
	
	    if ($result->num_rows === 0) {
	        return "No summary data found for submission.";
	    }
	
	    $row = $result->fetch_assoc();
	

	    $check = $liveconn->prepare("SELECT id FROM $table WHERE branch = ? AND report_date = ?");
	    $check->bind_param("ss", $branch, $transdate);
	    $check->execute();
	    $res = $check->get_result();
	
	    if ($res->num_rows > 0) {
	        return "Summary already exists in Head Office.";
	    }
	

	    $insert = $liveconn->prepare("INSERT INTO $table 
	        (bid, branch, report_date, report_month, baker_headcount, selling_headcount, total_headcount, 
	         category_json, amount_json, actual_amount_total, created_date, created_by) 
	        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
	
	    if (!$insert) {
	        return "Insert prepare failed: " . $liveconn->error;
	    }
	
	    $insert->bind_param(
	        "isssiiissdss",
	        $row['id'],
	        $row['branch'],
	        $row['report_date'],
	        $row['report_month'],
	        $row['baker_headcount'],
	        $row['selling_headcount'],
	        $row['total_headcount'],
	        $row['category_json'],
	        $row['amount_json'],
	        $row['actual_amount_total'],
	        $row['created_date'],
	        $row['created_by']
	    );
	
	    if ($insert->execute()) {
	        return "Summary submitted successfully to Head Office.";
	    } else {
	        return "Insert failed: " . $insert->error;
	    }
	}
	
	
	
	
	
	
	
	public function SubmitExpenseToServer($branch, $transdate, $localconn, $liveconn)
	{
	    $table = "store_brrr_expense_data";
	
	    $sql = "SELECT id, branch, report_date, category, actual_amount, remarks, created_date, created_by 
	            FROM $table 
	            WHERE branch = ? AND report_date = ?";
	
	    $stmt = $localconn->prepare($sql);
	    $stmt->bind_param("ss", $branch, $transdate);
	    $stmt->execute();
	    $result = $stmt->get_result();
	
	    $inserted = 0;
	
	    echo "<div style='background:#eef;border:1px solid #99f;padding:10px'>";
	    echo "<strong>DEBUG: Submitting EXPENSE records to live DB</strong><br><br>";
	
	    while ($row = $result->fetch_assoc()) {
	        $bid = $row['id'];
	
	        $checkSql = "SELECT COUNT(*) FROM $table WHERE bid = ? AND branch = ? AND report_date = ? AND category = ?";
	        $checkStmt = $liveconn->prepare($checkSql);
	        if (!$checkStmt) {
	            echo "<pre style='color:red'>Prepare (check) failed: {$liveconn->error}</pre>";
	            continue;
	        }
	
	        $checkStmt->bind_param("isss", $bid, $row['branch'], $row['report_date'], $row['category']);
	        if (!$checkStmt->execute()) {
	            echo "<pre style='color:red'>Execute (check) failed: {$checkStmt->error}</pre>";
	            continue;
	        }
	
	        $checkStmt->bind_result($count);
	        $checkStmt->fetch();
	        $checkStmt->close();
	
	        if ((int)$count > 0) {
	            echo "<pre style='color:red'>EXISTING: Local ID {$bid} already in live DB</pre>";
	        } else {
	            echo "<pre style='color:green'>INSERTING: Local ID {$bid} not found - inserting to live DB</pre>";
	
	            $insertSql = "INSERT INTO $table 
	                (bid, branch, report_date, category, actual_amount, remarks, created_date, created_by) 
	                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
	
	            $insertStmt = $liveconn->prepare($insertSql);
	            if (!$insertStmt) {
	                echo "<pre style='color:red'>Prepare (insert) failed: {$liveconn->error}</pre>";
	                continue;
	            }
	
	            $insertStmt->bind_param(
	                "isssdsss",
	                $bid,
	                $row['branch'],
	                $row['report_date'],
	                $row['category'],
	                $row['actual_amount'],
	                $row['remarks'],
	                $row['created_date'],
	                $row['created_by']
	            );
	
	            if ($insertStmt->execute()) {
	                $inserted++;
	            } else {
	                echo "<pre style='color:red'>Insert failed for ID {$bid}: {$insertStmt->error}</pre>";
	            }
	
	            $insertStmt->close();
	        }
	    }
	
	    echo "<br><strong style='color:blue'>TOTAL EXPENSE INSERTED: $inserted</strong>";
	    echo "</div>";
	
	    return $inserted;
	}

	
	
	


	public function SubmitOverheadToServer($branch, $transdate, $localconn, $liveconn)
	{
	    $module = 'overhead';
	    $table = "store_brrr_overhead_data";
	

	    if ($localconn->connect_error || $liveconn->connect_error) {
	        echo "<div class='alert alert-danger'>Connection error: Local or Live DB not connected.</div>";
	        return 0;
	    }
	
	    $sql = "SELECT id, branch, report_date, idcode, acctname, position, baker, selling, created_date, created_by 
	            FROM $table 
	            WHERE branch = ? AND report_date = ?";
	
	    $stmt = $localconn->prepare($sql);
	    $stmt->bind_param("ss", $branch, $transdate);
	    $stmt->execute();
	    $result = $stmt->get_result();
	
	    $inserted = 0;
	
	    echo "<div style='background:#eef;border:1px solid #99f;padding:10px'>";
	    echo "<strong>DEBUG: Processing records from local to live</strong><br><br>";
	
	    while ($row = $result->fetch_assoc()) {
	        $bid = $row['id'];
	

	        $checkSql = "SELECT COUNT(*) FROM $table WHERE bid = ? AND branch = ? AND report_date = ?";
	        $checkStmt = $liveconn->prepare($checkSql);
	        if (!$checkStmt) {
	            echo "<pre style='color:red'>Prepare (check) failed: {$liveconn->error}</pre>";
	            continue;
	        }
	
	        $checkStmt->bind_param("sss", $bid, $row['branch'], $row['report_date']);
	        if (!$checkStmt->execute()) {
	            echo "<pre style='color:red'>Execute (check) failed: {$checkStmt->error}</pre>";
	            $checkStmt->close();
	            continue;
	        }
	
	        $checkStmt->bind_result($count);
	        $checkStmt->fetch();
	        $checkStmt->close();
	
	        if ((int)$count > 0) {
	            echo "<pre style='color:red'>EXISTING: Local ID {$bid} already in live DB</pre>";
	        } else {
	            echo "<pre style='color:green'>INSERTING: Local ID {$bid} not found - inserting to live DB</pre>";
	

	            $insertSql = "INSERT INTO $table 
	                (bid, branch, report_date, idcode, acctname, position, baker, selling, created_date, created_by, status) 
	                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
	
	            $insertStmt = $liveconn->prepare($insertSql);
	            if (!$insertStmt) {
	                echo "<pre style='color:red'>Prepare (insert) failed: {$liveconn->error}</pre>";
	                continue;
	            }
	
	            $status = 1;
	            $insertStmt->bind_param(
	                "isssssiissi",
	                $row['id'],
	                $row['branch'],
	                $row['report_date'],
	                $row['idcode'],
	                $row['acctname'],
	                $row['position'],
	                $row['baker'],
	                $row['selling'],
	                $row['created_date'],
	                $row['created_by'],
	                $status
	            );
	
	            if ($insertStmt->execute()) {
	                $inserted++;
	            } else {
	                echo "<pre style='color:red'>Insert failed for ID {$bid}: {$insertStmt->error}</pre>";
	            }
	
	            $insertStmt->close();
	        }
	    }
	
	    $result->free();
	    $stmt->close();
	
	    echo "<br><strong style='color:blue'>TOTAL INSERTED: $inserted</strong>";
	    echo "</div>";
	
	    return $inserted;
	}







	
	
	public function GetSubmittedData($module, $branch, $transdate, $conn)
	{
	    if ($module == 'overhead') {
	        $sql = "SELECT COUNT(*) as total FROM store_brrr_overhead_data WHERE branch = ? AND report_date = ?";
	    } else if ($module == 'expense') {
	        $sql = "SELECT COUNT(*) as total FROM store_brrr_expense_data WHERE branch = ? AND report_date = ?";
	    } else if ($module == 'summary') {
	        $sql = "SELECT COUNT(*) as total FROM store_brrr_summary_data WHERE branch = ? AND report_date = ?";
	    } else {
	        return 0; // invalid module
	    }
	
	    $stmt = $conn->prepare($sql);
	    if (!$stmt) {
	        return 0;
	    }
	
	    $stmt->bind_param("ss", $branch, $transdate);
	    $stmt->execute();
	
	    $stmt->bind_result($count);
	    if ($stmt->fetch()) {
	        $stmt->close();
	        return (int)$count;
	    }
	
	    $stmt->close();
	    return 0;
	}



/////////////////////////////////////// ######### ///////////////////////////////////////
	
	
	
	

	public function otherexpensedetectionexist($datefrom, $dateto, $branch, $db, $maindb)
	{
	    $datefrom = date("Y-m-01", strtotime($datefrom));
	    $dateto = date("Y-m-t", strtotime($dateto));
	

	    $sql_main = "SELECT COUNT(*) as count FROM store_brrr_expense_ho_data 
	                 WHERE branch = ? AND report_date BETWEEN ? AND ?";
	    $stmt_main = $maindb->prepare($sql_main);
	    if (!$stmt_main) {
	        error_log("HO prepare failed: " . $maindb->error);
	        return 0;
	    }
	    $stmt_main->bind_param("sss", $branch, $datefrom, $dateto);
	    $stmt_main->execute();
	    $result_main = $stmt_main->get_result();
	    $main_count = ($result_main->fetch_assoc()['count']) ?? 0;
	    $stmt_main->close();
	

	    $sql_local = "SELECT COUNT(*) as count FROM store_brrr_expense_ho_data 
	                  WHERE branch = ? AND report_date BETWEEN ? AND ?";
	    $stmt_local = $db->prepare($sql_local);
	    if (!$stmt_local) {
	        error_log("Local prepare failed: " . $db->error);
	        return 0;
	    }
	    $stmt_local->bind_param("sss", $branch, $datefrom, $dateto);
	    $stmt_local->execute();
	    $result_local = $stmt_local->get_result();
	    $local_count = ($result_local->fetch_assoc()['count']) ?? 0;
	    $stmt_local->close();
	

	    return ($main_count !== $local_count) ? 1 : 0;
	}
		
	
	public function cogsdetectionexist($datefrom, $dateto, $db, $maindb)
	{
	    $monthStart = date("Y-m-01", strtotime($datefrom));
	    $monthEnd = date("Y-m-t", strtotime($dateto));
	
	
	    $sql_ho = "SELECT COUNT(*) as count FROM store_cogs_setting_data WHERE date_from = ? AND date_to = ?";
	    $stmt_ho = $maindb->prepare($sql_ho);
	    if (!$stmt_ho) return 1;
	    $stmt_ho->bind_param("ss", $monthStart, $monthEnd);
	    $stmt_ho->execute();
	    $ho_count = $stmt_ho->get_result()->fetch_assoc()['count'] ?? 0;
	
	    $sql_local = "SELECT COUNT(*) as count FROM store_brrr_cogs_data WHERE date_from = ? AND date_to = ?";
	    $stmt_local = $db->prepare($sql_local);
	    if (!$stmt_local) return 1;
	    $stmt_local->bind_param("ss", $monthStart, $monthEnd);
	    $stmt_local->execute();
	    $local_count = $stmt_local->get_result()->fetch_assoc()['count'] ?? 0;
	
	    return ($ho_count !== $local_count) ? 1 : 0;
	}
		
		
		
		
/////////////////////////////////////// ######### ///////////////////////////////////////


	
	public function getDailyCogsTotalviadate($datefrom, $dateto, $db)
	{
	    $sql = "
	        SELECT 
	            c.item_id, 
	            c.category, 
	            c.cost_pc,
	            SUM(s.sold) AS total_sold
	        FROM store_brrr_cogs_data c
	        LEFT JOIN store_summary_data s 
	            ON c.item_id = s.item_id 
	            AND c.category = s.category 
	            AND s.report_date BETWEEN ? AND ?
	        GROUP BY c.item_id, c.category, c.cost_pc
	    ";
	
	    $stmt = $db->prepare($sql);
	    $stmt->bind_param("ss", $datefrom, $dateto);
	    $stmt->execute();
	    $result = $stmt->get_result();
	
	    $totalCOGS = 0;
	
	    while ($row = $result->fetch_assoc()) {
	        $total_sold = (float)($row['total_sold'] ?? 0);
	        $cost_pc = (float)$row['cost_pc'];
	        $item_cogs = $total_sold * $cost_pc;
	        $totalCOGS += $item_cogs;
	    }
	
	    return $totalCOGS;
	}

	

	public function summarygetdatacolumnviadate($column, $branch, $datefrom, $dateto, $db)
	{


	    $allowed_columns = ['baker_headcount', 'selling_headcount', 'total_headcount'];
	
	    if (!in_array($column, $allowed_columns)) {
	        die("Invalid column requested.");
	    }
	
	    $sql = "SELECT `$column` FROM store_brrr_summary_data WHERE report_date BETWEEN ? AND ? AND branch = ?";
	    $stmt = $db->prepare($sql);
	    $stmt->bind_param("sss", $datefrom, $dateto, $branch);
	    $stmt->execute();
	    $result = $stmt->get_result();
	    $row = $result->fetch_assoc();
	
	    $colvalue = floatval($row[$column] ?? 0);
	    return $colvalue;
	}
	
	
	public function month13bakerviadate($branch, $datefrom, $dateto, $db)
	{

		$salary1month = $this->minimumwage('monthly_wage', $db);
		$month13 = ($salary1month / 12/ 2);
		$totalheadcount = $this->summarygetdatacolumnviadate('baker_headcount', $branch, $datefrom, $dateto, $db);
		$total = $totalheadcount * $month13;

		return $total;
	}
	
	public function month13sellingviadate($branch, $datefrom, $dateto, $db)
	{

		$salary1month = $this->minimumwage('monthly_wage', $db);
		$month13 = ($salary1month / 12/ 2);
		$totalheadcount = $this->summarygetdatacolumnviadate('selling_headcount', $branch, $datefrom, $dateto, $db);
		$total = $totalheadcount * $month13;

		return $total;
	}



	public function sellingcostviadate($branch, $datefrom, $dateto, $db, $dayoffsPerWeek = 1)
	{
	    $idcodes = [];
	

	    $stmt = $db->prepare("SELECT DISTINCT idcode FROM store_brrr_overhead_data WHERE report_date BETWEEN ? AND ? AND branch = ? AND LOWER(position) NOT LIKE ?");
	    if (!$stmt) {
	        return "Prepare failed (Step 1): " . $db->error;
	    }
	
	    $notBaker = "%baker%";
	    $stmt->bind_param("ssss", $datefrom, $dateto, $branch, $notBaker);
	
	    if ($stmt->execute()) {
	        $result = $stmt->get_result();
	        while ($row = $result->fetch_assoc()) {
	            $idcodes[] = $row['idcode'];
	        }
	        $stmt->close();
	    } else {
	        return "Execute failed (Step 1): " . $stmt->error;
	    }
	
	    if (empty($idcodes)) {
	        return 0;
	    }
	

	    $placeholders = implode(',', array_fill(0, count($idcodes), '?'));
	    $types = str_repeat('s', count($idcodes));
	    $sql = "SELECT SUM(salary_daily) AS total_salary FROM tbl_employees_ho 
	            WHERE idcode IN ($placeholders)";
	
	    $stmt = $db->prepare($sql);
	    if (!$stmt) {
	        return "Prepare failed (Step 2): " . $db->error;
	    }
	
	    $stmt->bind_param($types, ...$idcodes);
	
	    if ($stmt->execute()) {
	        $result = $stmt->get_result();
	        $row = $result->fetch_assoc();
	        $total_daily_salary = (float)$row['total_salary'];
	

	        $start = new DateTime($datefrom);
	        $end = new DateTime($dateto);
	        $total_days = $start->diff($end)->days + 1;
	
	        $weeks = ceil($total_days / 7);
	        $estimated_dayoffs = $weeks * $dayoffsPerWeek;
	        $working_days = max($total_days - $estimated_dayoffs, 0);
	
	        return $total_daily_salary * $working_days;
	    } else {
	        return "Execute failed (Step 2): " . $stmt->error;
	    }
	}
	


/*	
	public function sellingcostviadate($branch, $datefrom, $dateto, $db)
	{
	    $sql = "SELECT selling_headcount FROM store_brrr_summary_data WHERE report_date BETWEEN ? AND ? AND branch = ?";
	    $stmt = $db->prepare($sql);
	    $stmt->bind_param("sss", $datefrom, $dateto, $branch);
	    $stmt->execute();
	    $result = $stmt->get_result();
	    $row = $result->fetch_assoc();
	
	    $selling_amount = floatval($row['selling_headcount'] ?? 0);
	    $wage = $this->minimumwage('min_wage',$db);
	
	    $totalsalary = $selling_amount * $wage;

	    return $totalsalary;
	}
*/


	
	public function bakercostviadate($branch, $datefrom, $dateto, $db)
	{
	    $bakerData = [];
	
	    $stmt = $db->prepare("SELECT idcode, COUNT(*) as present_days FROM store_brrr_overhead_data WHERE report_date BETWEEN ? AND ? AND branch = ? AND LOWER(position) LIKE ? GROUP BY idcode");
	
	    if (!$stmt) {
	        return "Prepare failed (Step 1): " . $db->error;
	    }
	
	    $like = "%baker%";
	    $stmt->bind_param("ssss", $datefrom, $dateto, $branch, $like);
	
	    if (!$stmt->execute()) {
	        return "Execute failed (Step 1): " . $stmt->error;
	    }
	
	    $result = $stmt->get_result();
	    while ($row = $result->fetch_assoc()) {
	        $bakerData[$row['idcode']] = $row['present_days'];
	    }
	    $stmt->close();
	
	    // Kung walay bakers, return 0
	    if (empty($bakerData)) {
	        return 0;
	    }
	
	    // Step 2: Kuhaon ilang salary_daily base sa ilang idcode
	    $idcodes = array_keys($bakerData);
	    $placeholders = implode(',', array_fill(0, count($idcodes), '?'));
	    $types = str_repeat('s', count($idcodes));
	
	    $sql = "SELECT idcode, salary_daily FROM tbl_employees_ho WHERE idcode IN ($placeholders)";
	    $stmt = $db->prepare($sql);
	    if (!$stmt) {
	        return "Prepare failed (Step 2): " . $db->error;
	    }
	
	    $stmt->bind_param($types, ...$idcodes);
	
	    if (!$stmt->execute()) {
	        return "Execute failed (Step 2): " . $stmt->error;
	    }
	
	    $result = $stmt->get_result();
	    $total = 0;
	
	    while ($row = $result->fetch_assoc()) {
	        $id = $row['idcode'];
	        $salary = (float)$row['salary_daily'];
	        $days_present = $bakerData[$id] ?? 0;
	        $total += $salary * $days_present;
	    }
	
	    $stmt->close();
	    return $total;
	}
	
	
	public function bakercostviadateconn($branch, $datefrom, $dateto, $db)
	{
	    $bakerData = [];
	
	    $stmt = $db->prepare("SELECT idcode, COUNT(*) as present_days FROM store_brrr_overhead_data WHERE report_date BETWEEN ? AND ? AND branch = ? AND LOWER(position) LIKE ? GROUP BY idcode");
	
	    if (!$stmt) {
	        return "Prepare failed (Step 1): " . $db->error;
	    }
	
	    $like = "%baker%";
	    $stmt->bind_param("ssss", $datefrom, $dateto, $branch, $like);
	
	    if (!$stmt->execute()) {
	        return "Execute failed (Step 1): " . $stmt->error;
	    }
	
	    $result = $stmt->get_result();
	    while ($row = $result->fetch_assoc()) {
	        $bakerData[$row['idcode']] = $row['present_days'];
	    }
	    $stmt->close();
	
	    // Kung walay bakers, return 0
	    if (empty($bakerData)) {
	        return 0;
	    }
	
	    // Step 2: Kuhaon ilang salary_daily base sa ilang idcode
	    $idcodes = array_keys($bakerData);
	    $placeholders = implode(',', array_fill(0, count($idcodes), '?'));
	    $types = str_repeat('s', count($idcodes));
	
	    $sql = "SELECT idcode, salary_daily FROM tbl_employees WHERE idcode IN ($placeholders)";
	    $stmt = $db->prepare($sql);
	    if (!$stmt) {
	        return "Prepare failed (Step 2): " . $db->error;
	    }
	
	    $stmt->bind_param($types, ...$idcodes);
	
	    if (!$stmt->execute()) {
	        return "Execute failed (Step 2): " . $stmt->error;
	    }
	
	    $result = $stmt->get_result();
	    $total = 0;
	
	    while ($row = $result->fetch_assoc()) {
	        $id = $row['idcode'];
	        $salary = (float)$row['salary_daily'];
	        $days_present = $bakerData[$id] ?? 0;
	        $total += $salary * $days_present;
	    }
	
	    $stmt->close();
	    return $total;
	}


	

	
	public function salessummary_bakeronlyviadate($branch, $datefrom, $dateto, $db)
	{
	    $excluded = ['BEVERAGES','BOTTLED WATER','COFFEE','MILK TEA','MERCHANDISE OTHERS','ICE CREAM'];
	    $placeholders = implode(',', array_fill(0, count($excluded), '?'));
	
	    $types = str_repeat('s', count($excluded)); // for bind_param
	    $params = array_merge([$datefrom, $dateto, $branch], $excluded);
	
	    $sql = "SELECT SUM(amount) AS TOTALAMOUNT 
	            FROM store_summary_data 
	            WHERE report_date BETWEEN ? AND ? 
	              AND branch = ? 
	              AND category NOT IN ($placeholders)";
	    
	    $stmt = $db->prepare($sql);
	
	    $bindTypes = "sss" . $types;
	    $stmt->bind_param($bindTypes, ...$params);
	
	    $stmt->execute();
	    $result = $stmt->get_result();
	    $row = $result->fetch_assoc();
	    return floatval($row['TOTALAMOUNT'] ?? 0);
	}

	
	
	public function salessummaryviadate($branch, $datefrom, $dateto, $db)
	{	
	    $sql = "SELECT SUM(amount) AS TOTALAMOUNT FROM store_summary_data WHERE report_date BETWEEN ? AND ? AND branch = ?";
	    $stmt = $db->prepare($sql);
	    $stmt->bind_param("sss", $datefrom, $dateto, $branch);
	    $stmt->execute();
	    $result = $stmt->get_result();
	    
	    $row = $result->fetch_assoc();
	    return floatval($row['TOTALAMOUNT'] ?? 0);
	}

	
	//////////////// ###################### /////////////////////	
	
	public function getDailyCogsTotal($reportdate, $db)
	{
	    $month = date("Y-m", strtotime($reportdate));
	    $firstdateofmonth = $month . "-01";
	    $lastdateofmonth = date("Y-m-t", strtotime($firstdateofmonth));
	
	    $sql = "
	        SELECT 
	            c.item_id, 
	            c.category, 
	            c.cost_pc,
	            SUM(s.sold) AS total_sold
	        FROM store_brrr_cogs_data c
	        LEFT JOIN store_summary_data s 
	            ON c.item_id = s.item_id 
	            AND c.category = s.category 
	            AND s.report_date = ?
	        WHERE c.date_from = ? AND c.date_to = ?
	        GROUP BY c.item_id, c.category, c.cost_pc
	    ";
	
	    $stmt = $db->prepare($sql);
	    $stmt->bind_param("sss", $reportdate, $firstdateofmonth, $lastdateofmonth);
	    $stmt->execute();
	    $result = $stmt->get_result();
	
	    $totalCOGS = 0;
	
	    while ($row = $result->fetch_assoc()) {
	        $total_sold = (float)($row['total_sold'] ?? 0);
	        $cost_pc = (float)$row['cost_pc'];
	        $item_cogs = $total_sold * $cost_pc;
	        $totalCOGS += $item_cogs;
	    }
	
	    return $totalCOGS;
	}











	
	public function mandatories($salary, $db)
	{
	    $sss = $this->mandatoriessss($salary, $db);
	    $pagibig = $this->mandatoriespagibig($salary, $db);
	    $philhealth = $this->mandatoriesphilhealth($salary, $db);
	
	    $totalmandatories = $sss + $pagibig + $philhealth;
	    
	    return $totalmandatories;
	}
	
	public function mandatoriessss($salary, $db)
	{
	    $sql = "SELECT employee_share FROM store_brrr_sss_table 
	            WHERE ? BETWEEN salary_from AND salary_to 
	            LIMIT 1";
	    $stmt = $db->prepare($sql);
	    $stmt->bind_param("d", $salary);
	    $stmt->execute();
	    $result = $stmt->get_result();
	    $row = $result->fetch_assoc();
	    return isset($row['employee_share']) ? (float)$row['employee_share'] : 0;
	}
	
	public function mandatoriespagibig($salary, $db)
	{
	    $sql = "SELECT employee_share FROM store_brrr_pagibig_table 
	            WHERE ? BETWEEN salary_from AND salary_to 
	            LIMIT 1";
	    $stmt = $db->prepare($sql);
	    $stmt->bind_param("d", $salary);
	    $stmt->execute();
	    $result = $stmt->get_result();
	    $row = $result->fetch_assoc();
	    return isset($row['employee_share']) ? (float)$row['employee_share'] : 0;
	}
	
	public function mandatoriesphilhealth($salary, $db)
	{
	    $sql = "SELECT employee_share FROM store_brrr_philhealth_table 
	            WHERE ? BETWEEN salary_from AND salary_to 
	            LIMIT 1";
	    $stmt = $db->prepare($sql);
	    $stmt->bind_param("d", $salary);
	    $stmt->execute();
	    $result = $stmt->get_result();
	    $row = $result->fetch_assoc();
	    return isset($row['employee_share']) ? (float)$row['employee_share'] : 0;
	}

	
	
	
	
	
	public function summarygetdatacolumn($column, $branch, $reportdate, $db)
	{


	    $allowed_columns = ['baker_headcount', 'selling_headcount', 'total_headcount'];
	
	    if (!in_array($column, $allowed_columns)) {
	        die("Invalid column requested.");
	    }
	
	    $sql = "SELECT `$column` FROM store_brrr_summary_data WHERE report_date = ? AND branch = ?";
	    $stmt = $db->prepare($sql);
	    $stmt->bind_param("ss", $reportdate, $branch);
	    $stmt->execute();
	    $result = $stmt->get_result();
	    $row = $result->fetch_assoc();
	
	    $colvalue = floatval($row[$column] ?? 0);
	    return $colvalue;
	}

	
	
	

	public function month13baker($branch, $reportdate, $db)
	{
	    $idcodes = [];
	

	    $stmt = $db->prepare("SELECT idcode FROM store_brrr_overhead_data WHERE report_date = ? AND branch = ? AND LOWER(position) LIKE ?");
	    if (!$stmt) {
	        return "Prepare failed (Step 1): " . $db->error;
	    }
	
	    $like = "%baker%";
	    $stmt->bind_param("sss", $reportdate, $branch, $like);
	
	    if ($stmt->execute()) {
	        $result = $stmt->get_result();
	        while ($row = $result->fetch_assoc()) {
	            $idcodes[] = $row['idcode'];
	        }
	        $stmt->close();
	    } else {
	        return "Execute failed (Step 1): " . $stmt->error;
	    }
	
	    if (empty($idcodes)) {
	        return 0;
	    }
	

	    $placeholders = implode(',', array_fill(0, count($idcodes), '?'));
	    $types = str_repeat('s', count($idcodes));
	    $sql = "SELECT SUM(salary_monthly) AS total_monthly FROM tbl_employees_ho WHERE idcode IN ($placeholders)";
	
	    $stmt = $db->prepare($sql);
	    if (!$stmt) {
	        return "Prepare failed (Step 2): " . $db->error;
	    }
	
	    $stmt->bind_param($types, ...$idcodes);
	
	    if ($stmt->execute()) {
	        $result = $stmt->get_result();
	        $row = $result->fetch_assoc();
	        $total_monthly = (float) $row['total_monthly'];
	
	        // Step 3: Return total / 12 / 2 (half of 13th month)
	        return $total_monthly / 12 / 2;
	    } else {
	        return "Execute failed (Step 2): " . $stmt->error;
	    }
	}
	

/*
	public function month13baker($branch, $reportdate, $db)
	{

		$salary1month = $this->minimumwage('monthly_wage', $db);
		$month13 = ($salary1month / 12/ 2);
		$totalheadcount = $this->summarygetdatacolumn('baker_headcount', $branch, $reportdate, $db);
		$total = $totalheadcount * $month13;

		return $total;
	}
*/
	
	public function month13selling($branch, $reportdate, $db)
	{
	    $idcodes = [];
	

	    $stmt = $db->prepare("SELECT idcode FROM store_brrr_overhead_data WHERE report_date = ? AND branch = ? AND LOWER(position) NOT LIKE ?");
	    if (!$stmt) {
	        return "Prepare failed (Step 1): " . $db->error;
	    }
	
	    $notLike = "%baker%";
	    $stmt->bind_param("sss", $reportdate, $branch, $notLike);
	
	    if ($stmt->execute()) {
	        $result = $stmt->get_result();
	        while ($row = $result->fetch_assoc()) {
	            $idcodes[] = $row['idcode'];
	        }
	        $stmt->close();
	    } else {
	        return "Execute failed (Step 1): " . $stmt->error;
	    }
	
	    if (empty($idcodes)) {
	        return 0;
	    }
	

	    $placeholders = implode(',', array_fill(0, count($idcodes), '?'));
	    $types = str_repeat('s', count($idcodes));
	    $sql = "SELECT SUM(salary_monthly) AS total_monthly FROM tbl_employees_ho WHERE idcode IN ($placeholders)";
	
	    $stmt = $db->prepare($sql);
	    if (!$stmt) {
	        return "Prepare failed (Step 2): " . $db->error;
	    }
	
	    $stmt->bind_param($types, ...$idcodes);
	
	    if ($stmt->execute()) {
	        $result = $stmt->get_result();
	        $row = $result->fetch_assoc();
	        $total_monthly = (float) $row['total_monthly'];
	

	        return $total_monthly / 12 / 2;
	    } else {
	        return "Execute failed (Step 2): " . $stmt->error;
	    }
	}
	
	
/*	
	public function month13selling($branch, $reportdate, $db)
	{

		$salary1month = $this->minimumwage('monthly_wage', $db);
		$month13 = ($salary1month / 12/ 2);
		$totalheadcount = $this->summarygetdatacolumn('selling_headcount', $branch, $reportdate, $db);
		$total = $totalheadcount * $month13;

		return $total;
	}
*/




	
	public function minimumwage($column, $db)
	{
	    $allowed_columns = ['min_wage', 'monthly_wage'];
	
	    if (!in_array($column, $allowed_columns)) {
	        die("Invalid column requested.");
	    }
	
	    $sql = "SELECT `$column` FROM store_brrr_wage_table WHERE id = 1";
	    $stmt = $db->prepare($sql);
	
	    if (!$stmt) {
	        die("Prepare failed: " . $db->error);
	    }
	
	    $stmt->execute();
	    $result = $stmt->get_result();
	
	    $row = $result->fetch_assoc();
	    return floatval($row[$column] ?? 0);
	}
	
	
	
	public function bakercost($branch, $reportdate, $db)
	{
	    $idcodes = [];
	

	    $stmt = $db->prepare("SELECT idcode FROM store_brrr_overhead_data WHERE report_date = ? AND branch = ? AND LOWER(position) LIKE ?");
	    if (!$stmt) {
	        return "Prepare failed (Step 1): " . $db->error;
	    }
	
	    $like = "%baker%";
	    $stmt->bind_param("sss", $reportdate, $branch, $like);
	    if ($stmt->execute()) {
	        $result = $stmt->get_result();
	        while ($row = $result->fetch_assoc()) {
	            $idcodes[] = $row['idcode'];
	        }
	        $stmt->close();
	    } else {
	        return "Execute failed (Step 1): " . $stmt->error;
	    }
	
	    if (empty($idcodes)) {
	        return 0;
	    }
	

	    $placeholders = implode(',', array_fill(0, count($idcodes), '?'));
	    $types = str_repeat('s', count($idcodes));
	    $sql = "SELECT SUM(salary_daily) AS total_salary FROM tbl_employees_ho WHERE idcode IN ($placeholders)";
	
	    $stmt = $db->prepare($sql);
	    if (!$stmt) {
	        return "Prepare failed (Step 2): " . $db->error;
	    }
	
	    $stmt->bind_param($types, ...$idcodes);
	    if ($stmt->execute()) {
	        $result = $stmt->get_result();
	        $row = $result->fetch_assoc();
	        return (float) $row['total_salary'];
	    } else {
	        return "Execute failed (Step 2): " . $stmt->error;
	    }
	}

/*	
	public function bakercost($branch, $reportdate, $db)
	{
	    $sql = "SELECT baker_headcount FROM store_brrr_summary_data WHERE report_date = ? AND branch = ?";
	    $stmt = $db->prepare($sql);
	    $stmt->bind_param("ss", $reportdate, $branch);
	    $stmt->execute();
	    $result = $stmt->get_result();
	    $row = $result->fetch_assoc();
	    

	
	    $baker_amount = floatval($row['baker_headcount'] ?? 0);
	    $wage = $this->minimumwage('min_wage',$db);
	
	    $totalsalary = $baker_amount * $wage;

	    return $totalsalary;
	}
*/

	
	public function sellingcost($branch, $datefrom, $dateto, $db)
	{
	    $idcodeDays = [];
	
	    // Step 1: Pangitaon ang mga dili baker ug ihap ilang adlaw nga ni-sulod
	    $stmt = $db->prepare("
	        SELECT idcode, COUNT(*) AS present_days
	        FROM store_brrr_overhead_data
	        WHERE report_date BETWEEN ? AND ? 
	        AND branch = ? 
	        AND LOWER(position) NOT LIKE ?
	        GROUP BY idcode
	    ");
	
	    if (!$stmt) {
	        return "Prepare failed (Step 1): " . $db->error;
	    }
	
	    $notlike = "%baker%";
	    $stmt->bind_param("ssss", $datefrom, $dateto, $branch, $notlike);
	
	    if (!$stmt->execute()) {
	        return "Execute failed (Step 1): " . $stmt->error;
	    }
	
	    $result = $stmt->get_result();
	    while ($row = $result->fetch_assoc()) {
	        $idcodeDays[$row['idcode']] = $row['present_days'];
	    }
	    $stmt->close();
	
	    // Kung walay result, return 0
	    if (empty($idcodeDays)) {
	        return 0;
	    }
	
	    // Step 2: Kuhaon ang salary_daily sa matag idcode
	    $idcodes = array_keys($idcodeDays);
	    $placeholders = implode(',', array_fill(0, count($idcodes), '?'));
	    $types = str_repeat('s', count($idcodes));
	
	    $sql = "SELECT idcode, salary_daily FROM tbl_employees_ho WHERE idcode IN ($placeholders)";
	    $stmt = $db->prepare($sql);
	    if (!$stmt) {
	        return "Prepare failed (Step 2): " . $db->error;
	    }
	
	    $stmt->bind_param($types, ...$idcodes);
	
	    if (!$stmt->execute()) {
	        return "Execute failed (Step 2): " . $stmt->error;
	    }
	
	    $result = $stmt->get_result();
	    $total = 0;
	
	    while ($row = $result->fetch_assoc()) {
	        $id = $row['idcode'];
	        $salary = (float)$row['salary_daily'];
	        $days_present = $idcodeDays[$id] ?? 0;
	        $total += $salary * $days_present;
	    }
	
	    $stmt->close();
	    return $total;
	}
	

	public function sellingcostconn($branch, $datefrom, $dateto, $db)
	{
	    $idcodeDays = [];
	
	    // Step 1: Pangitaon ang mga dili baker ug ihap ilang adlaw nga ni-sulod
	    $stmt = $db->prepare("
	        SELECT idcode, COUNT(*) AS present_days
	        FROM store_brrr_overhead_data
	        WHERE report_date BETWEEN ? AND ? 
	        AND branch = ? 
	        AND LOWER(position) NOT LIKE ?
	        GROUP BY idcode
	    ");
	
	    if (!$stmt) {
	        return "Prepare failed (Step 1): " . $db->error;
	    }
	
	    $notlike = "%baker%";
	    $stmt->bind_param("ssss", $datefrom, $dateto, $branch, $notlike);
	
	    if (!$stmt->execute()) {
	        return "Execute failed (Step 1): " . $stmt->error;
	    }
	
	    $result = $stmt->get_result();
	    while ($row = $result->fetch_assoc()) {
	        $idcodeDays[$row['idcode']] = $row['present_days'];
	    }
	    $stmt->close();
	
	    // Kung walay result, return 0
	    if (empty($idcodeDays)) {
	        return 0;
	    }
	
	    // Step 2: Kuhaon ang salary_daily sa matag idcode
	    $idcodes = array_keys($idcodeDays);
	    $placeholders = implode(',', array_fill(0, count($idcodes), '?'));
	    $types = str_repeat('s', count($idcodes));
	
	    $sql = "SELECT idcode, salary_daily FROM tbl_employees WHERE idcode IN ($placeholders)";
	    $stmt = $db->prepare($sql);
	    if (!$stmt) {
	        return "Prepare failed (Step 2): " . $db->error;
	    }
	
	    $stmt->bind_param($types, ...$idcodes);
	
	    if (!$stmt->execute()) {
	        return "Execute failed (Step 2): " . $stmt->error;
	    }
	
	    $result = $stmt->get_result();
	    $total = 0;
	
	    while ($row = $result->fetch_assoc()) {
	        $id = $row['idcode'];
	        $salary = (float)$row['salary_daily'];
	        $days_present = $idcodeDays[$id] ?? 0;
	        $total += $salary * $days_present;
	    }
	
	    $stmt->close();
	    return $total;
	}
	
/*	
	public function sellingcost($branch, $reportdate, $db)
	{
	    $sql = "SELECT selling_headcount FROM store_brrr_summary_data WHERE report_date = ? AND branch = ?";
	    $stmt = $db->prepare($sql);
	    $stmt->bind_param("ss", $reportdate, $branch);
	    $stmt->execute();
	    $result = $stmt->get_result();
	    $row = $result->fetch_assoc();
	
	    $selling_amount = floatval($row['selling_headcount'] ?? 0);
	    $wage = $this->minimumwage('min_wage',$db);
	
	    $totalsalary = $selling_amount * $wage;

	    return $totalsalary;
	}
*/






	public function salessummary($branch, $reportdate, $db)
	{	
	    $sql = "SELECT SUM(amount) AS TOTALAMOUNT FROM store_summary_data WHERE report_date = ? AND branch = ?";
	    $stmt = $db->prepare($sql);
	    $stmt->bind_param("ss", $reportdate, $branch);
	    $stmt->execute();
	    $result = $stmt->get_result();
	    
	    $row = $result->fetch_assoc();
	    return floatval($row['TOTALAMOUNT'] ?? 0);
	}



	public function salessummary_bakeronly($branch, $reportdate, $db)
	{
	    $excluded = ['BEVERAGES','BOTTLED WATER','COFFEE','MILK TEA','MERCHANDISE OTHERS','ICE CREAM'];
	    $placeholders = implode(',', array_fill(0, count($excluded), '?'));
	
	    $types = str_repeat('s', count($excluded));
	    $params = array_merge([$reportdate, $branch], $excluded);
	
	    $sql = "SELECT SUM(amount) AS TOTALAMOUNT 
	            FROM store_summary_data 
	            WHERE report_date = ? 
	              AND branch = ? 
	              AND category NOT IN ($placeholders)";
	    
	    $stmt = $db->prepare($sql);
	
	    $bindTypes = "ss" . $types;
	    $stmt->bind_param($bindTypes, ...$params);
	
	    $stmt->execute();
	    $result = $stmt->get_result();
	    $row = $result->fetch_assoc();
	    return floatval($row['TOTALAMOUNT'] ?? 0);
	}
	
	
	public function salessummary_bakeronly_ho($branch, $reportdate, $db)
	{
	    $excluded = ['BEVERAGES','BOTTLED WATER','COFFEE','MILK TEA','MERCHANDISE OTHERS','ICE CREAM'];
	    $placeholders = implode(',', array_fill(0, count($excluded), '?'));
	
	    $types = str_repeat('s', count($excluded));
	    $params = array_merge([$reportdate, $branch], $excluded);
	
	    $sql = "SELECT SUM(amount) AS TOTALAMOUNT 
	            FROM store_brrr_summary_ho_data 
	            WHERE report_date = ? 
	              AND branch = ? 
	              AND category NOT IN ($placeholders)";
	    
	    $stmt = $db->prepare($sql);
	
	    $bindTypes = "ss" . $types;
	    $stmt->bind_param($bindTypes, ...$params);
	
	    $stmt->execute();
	    $result = $stmt->get_result();
	    $row = $result->fetch_assoc();
	    return floatval($row['TOTALAMOUNT'] ?? 0);
	}







	public function expenseinputexisting($branch, $reportdate, $db)
	{	
		$sql = "SELECT id FROM store_brrr_expense_data WHERE report_date = ? AND branch = ? LIMIT 1";
		$stmt = $db->prepare($sql);
		$stmt->bind_param("ss", $reportdate, $branch);
		$stmt->execute();
		$result = $stmt->get_result();

		if ($result->num_rows > 0) {
			return 1;
		} else {
			return 0;
		}
	}
	
	public function expenseinputstatus($branch, $reportdate, $db)
	{	
		$sql = "SELECT status FROM store_brrr_expense_data WHERE report_date = ? AND branch = ? AND status = 1 LIMIT 1";
		$stmt = $db->prepare($sql);
		$stmt->bind_param("ss", $reportdate, $branch);
		$stmt->execute();
		$result = $stmt->get_result();

		if ($result->num_rows > 0) {
			return 1;
		} else {
			return 0;
		}
	}


	public function overheadcount($branch, $reportdate, $db)
	{
	    $sql = "SELECT COUNT(*) as total FROM store_brrr_overhead_data WHERE report_date = ? AND branch = ?";
	    $stmt = $db->prepare($sql);
	    $stmt->bind_param("ss", $reportdate, $branch);
	    $stmt->execute();
	    $result = $stmt->get_result();
	    
	    if ($row = $result->fetch_assoc()) {
	        return (int) $row['total'];
	    }
	
	    return 0;
	}
	
	public function overheadstatus($branch, $reportdate, $db)
	{	
		$sql = "SELECT status FROM store_brrr_overhead_data WHERE report_date = ? AND branch = ? AND status = 1 LIMIT 1";
		$stmt = $db->prepare($sql);
		$stmt->bind_param("ss", $reportdate, $branch);
		$stmt->execute();
		$result = $stmt->get_result();

		if ($result->num_rows > 0) {
			return 1;
		} else {
			return 0;
		}
	}
		
	
	public function GetSession($params)
	{
		if($params == 'appuser')
		{
			return $_SESSION['appstore_user'];
		}
		else if($params == 'branchdate')
		{
			if(isset($_SESSION['session_date']))
			{
				return $_SESSION['session_date'];
			} else {
				return " --- ";
			}
		}
		else if($params == 'cluster')
		{
			return $_SESSION['appstore_cluster'];
		} 
		else if($params == 'branch')
		{
			return $_SESSION['appstore_branch'];
		}
		else if($params == 'shift')
		{
			if(isset($_SESSION['session_shift']))
			{
				return $_SESSION['session_shift'];
			} else {
				return " --- ";
			}
		}
		else if($params == 'shifting')
		{	
			return $_SESSION['appstore_shifting'];
		}
		else if($params == 'encoder')
		{	
			return $_SESSION['appstore_appnameuser'];
		} 
		else if($params == 'idcode')
		{	
			return $_SESSION['appstore_idcode'];
		}
		else if($params == 'userlevel')
		{
			return $_SESSION['appstore_userlevel'];
		} 
		else if($params == 'userrole') 
		{
			return $_SESSION['appstore_userrole'];
		}
		else if($params == 'transfermode') 
		{
			return $_SESSION['session_transfer'];
		} else {
			return '';
		}
	}

	
	
	
	
}

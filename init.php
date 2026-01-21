<?PHP

header('Content-Type: text/html; charset=utf-8');
if (session_status() == PHP_SESSION_NONE) { session_start(); }



include 'db_config.php';
define('DB_HOST', $dbhost);
define('DB_USER', $dbuser);
define('DB_PASSWORD', $dbpass);
define('DB_NAME', $dbname);
require 'class/dropdowns.class.php';
require 'class/encrypted_password_class.php';
require 'class/functions.class.php';
require 'class/summary.class.php';
$summary = new TheSummary;
$functions = new TheFunctions;
$dropdown = new DropDowns;
$pass = new Password;
/* ################# STORE SETTINGS ################################ */
$dbe = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$qSettings = "SELECT * FROM store_settings WHERE id=1";
$res = mysqli_query($dbe, $qSettings);    
if ($res->num_rows > 0) 
{ 
    while($SETTROW = mysqli_fetch_array($res))  
	{
		define("APP_NAME", $SETTROW['app_name']);
		define("COMPANY", $SETTROW['company']);
		define("APP_LOGO", $SETTROW['app_logo']);
		define("VERSION_TEXT", $SETTROW['version_text']);
		// define("VERSION_NUMBER", $SETTROW['version_number']);
		if(isset( $SETTROW['theme_color']))
		{
			define("THEME_COLOR", $SETTROW['theme_color']);
		} else {
			define("THEME_COLOR", 0);
		}
	}
} else {
	echo "No Data";
}
if(THEME_COLOR == 0) {
	define("THEME", 'form_class_green');
} else if(THEME_COLOR == 1) {
	define("THEME", 'form_class_orange');
} else {
	define("THEME", 'form_class_green');
}

$url_host = '120.28.196.113';







$checkNav = $dbe->prepare("SELECT id FROM store_navigation WHERE category_id='102' AND page_name='rm_inventory_record' AND active='1' LIMIT 1");
$checkNav->execute();
$checkResult = $checkNav->get_result();

if ($checkResult->num_rows == 0) {

	
	$checkMergeItems = $dbe->query("SHOW TABLES LIKE 'store_merge_items'");
	if ($checkMergeItems->num_rows == 0) {
	
	    $dbe->query("
	        CREATE TABLE `store_merge_items` (
	          `id` INT NOT NULL AUTO_INCREMENT,
	          `item_id` INT DEFAULT NULL,
	          `item_name` VARCHAR(150) DEFAULT NULL,
	          `merge_to_item_id` INT DEFAULT NULL,
	          `merge_to_item_name` VARCHAR(150) DEFAULT NULL,
	          `created_by` VARCHAR(50) DEFAULT NULL,
	          `created_at` DATETIME DEFAULT NULL,
	          PRIMARY KEY (`id`)
	        )
	    ");
	
	}
	
	
    $insertNav = $dbe->prepare("INSERT INTO store_navigation(sorting, category_id, menu_name, page_name, display_icon, icon_color, active) VALUES (5, '102', 'Inventory Record', 'rm_inventory_record', 'fa-solid fa-book', 'text-primary', '1')
    ");
    $insertNav->execute();



    $dbe->query("UPDATE store_navigation SET category_id='102' WHERE page_name IN ('rm_receiving','rm_pcount','rm_badorder')");


    $dbe->query("UPDATE store_navigation SET active=0 WHERE page_name='rm_receiving'");
    $dbe->query("UPDATE store_navigation SET active=0 WHERE page_name='rm_pcount'");
    $dbe->query("UPDATE store_navigation SET active=0 WHERE page_name='rm_badorder'");
    $dbe->query("UPDATE store_navigation SET active=0 WHERE page_name='rm_inventory'");



    $checkTable = $dbe->query("SHOW TABLES LIKE 'store_rm_inventory_record_data'");
    if ($checkTable->num_rows == 0) {

        $dbe->query("
            CREATE TABLE `store_rm_inventory_record_data` (
              `id` int NOT NULL AUTO_INCREMENT,
              `branch` varchar(30) DEFAULT NULL,
              `report_date` date DEFAULT NULL,
              `shift` varchar(20) DEFAULT NULL,
              `employee_name` varchar(123) DEFAULT '',
              `supervisor` varchar(123) DEFAULT NULL,
              `category` varchar(80) DEFAULT NULL,
              `item_name` varchar(123) DEFAULT '',
              `item_id` int DEFAULT NULL,
              `date_created` datetime DEFAULT '0000-00-00 00:00:00',
              `date_updated` datetime DEFAULT NULL,
              `updated_by` varchar(50) DEFAULT NULL,
              `posted` varchar(6) DEFAULT 'No',
              `status` varchar(6) DEFAULT 'Open',
              `audit_mode` varchar(1) DEFAULT NULL,
              PRIMARY KEY (`id`)
            )
        ");
    }
    
    // Add column actual_usage to store_rm_summary_data if not exists
	$checkColumn = $dbe->query("
	    SHOW COLUMNS FROM store_rm_summary_data LIKE 'actual_usage'
	");
	
	if ($checkColumn->num_rows == 0) {
	
	    $dbe->query("
	        ALTER TABLE store_rm_summary_data
	        ADD COLUMN actual_usage DECIMAL(11,3) NOT NULL DEFAULT 0
	        AFTER amount
	    ");
	}
	
	
	
	$tables_to_index = [
	    'store_summary_data' => 'IDX_SUMMARY_DATA',
	    'store_receiving_data' => 'IDX_RECEIVING_DATA',
	    'store_frozendough_data' => 'IDX_FROZENDOUGH_DATA',
	    'store_transfer_data' => 'IDX_TRANSFER_DATA',
	    'store_charges_data' => 'IDX_CHARGES_DATA',
	    'store_badorder_data' => 'IDX_BADORDER_DATA',
	    'store_damage_data' => 'IDX_DAMAGE_DATA',
	    'store_pcount_data' => 'IDX_PCOUNT_DATA',
	    'store_cashcount_data' => 'IDX_CASHCOUNT_DATA',
	    'store_gcash_data' => 'IDX_GCASH_DATA',
	    'store_grab_data' => 'IDX_GRAB_DATA',
	    'store_foodpanda_data' => 'IDX_FOODPANDA_DATA',
	    'store_inventory_record_data' => 'IDX_INVENTORY_RECORDS_DATA',
	    'store_rm_summary_data' => 'IDX_RM_SUMMARY_DATA',
	    'store_rm_receiving_data' => 'IDX_RM_RECEIVING_DATA',
	    'store_rm_transfer_data' => 'IDX_RM_TRANSFER_DATA',
	    'store_rm_badorder_data' => 'IDX_RM_BADORDER_DATA',
	    'store_rm_pcount_data' => 'IDX_RM_PCOUNT_DATA',
	    'store_rm_inventory_record_data' => 'IDX_RM_INVENTORY_RECORDS_DATA'
	];
	
	foreach($tables_to_index as $table => $index_name){
	    // Check if index exists
	    $checkIndex = $dbe->query("SHOW INDEX FROM $table WHERE Key_name='$index_name'");
	    if($checkIndex->num_rows > 0){
	        // Drop existing index
	        $dbe->query("ALTER TABLE $table DROP INDEX $index_name");
	    }
	
	    // Add index
	    $dbe->query("ALTER TABLE $table ADD INDEX $index_name (branch, report_date, shift)");
	}

	

	
}












if ($dbe->query("SHOW TABLES LIKE 'store_branchlist_burgerbuns'")->num_rows == 0) {
	
	$tables = [
	    'store_branchlist_burgerbuns' => "
	        CREATE TABLE store_branchlist_burgerbuns (
	            id BIGINT(20) NOT NULL AUTO_INCREMENT,
	            branch_id BIGINT(20) DEFAULT NULL,
	            branch VARCHAR(100) DEFAULT NULL,
	            is_produce_burger_buns ENUM('YES','NO') DEFAULT 'YES',
	            created_by VARCHAR(50) DEFAULT NULL,
	            created_at DATETIME DEFAULT NULL,
	            PRIMARY KEY (id)
	        )
	    ",
	    'store_branchlist_production_exclude_items' => "
	    	CREATE TABLE `store_branchlist_production_exclude_items` (
				`id` bigint(20) NOT NULL AUTO_INCREMENT,
				`branch_id` bigint(20) DEFAULT NULL,
				`branch` varchar(100) DEFAULT NULL,
				`item_id` bigint(100) DEFAULT NULL,
				`item_name` varchar(150) DEFAULT NULL,
				`exclude_this_item` enum('NO','YES') DEFAULT 'NO',
				`created_by` varchar(50) DEFAULT NULL,
				`created_at` datetime DEFAULT NULL,
				PRIMARY KEY (`id`)
			)
	    ",
		'store_branchlist_wheatloaf' => "
	    	CREATE TABLE `store_branchlist_wheatloaf` (
				`id` bigint(20) NOT NULL AUTO_INCREMENT,
				`branch_id` bigint(20) DEFAULT NULL,
				`branch` varchar(100) DEFAULT NULL,
				`is_produce_wheat_loaf` enum('YES','NO') DEFAULT 'YES',
				`created_by` varchar(50) DEFAULT NULL,
				`created_at` datetime DEFAULT NULL,
				PRIMARY KEY (`id`)
			)
	    ",
		'store_ba_rm_header' => "
			CREATE TABLE `store_ba_rm_header` (
				`id` bigint(20) NOT NULL AUTO_INCREMENT,
				`item_id` bigint(20) DEFAULT NULL,
				`item_name` varchar(100) DEFAULT NULL,
				`is_rm_header` enum('YES','NO') DEFAULT 'YES',
				`created_by` varchar(50) DEFAULT NULL,
				`created_at` datetime DEFAULT NULL,
				PRIMARY KEY (`id`)
			)
	    "	    
	];
	
	
	foreach($tables as $table => $createSQL){
	    if($dbe->query("SHOW TABLES LIKE '$table'")->num_rows == 0){
	        $dbe->query($createSQL);
	    }
	}	
	
	    $dbe->query("UPDATE store_navigation 
	        SET sorting=3, category_id=103, menu_name='Build Assembly', display_icon='fa-solid fa-table', icon_color='text-primary', active=1 
	        WHERE page_name='build_assembly'");
		
		$dbe->query("UPDATE store_navigation 
	        SET sorting=6 WHERE page_name='rm_submitserver'");
	        
	        
	        
	        
}




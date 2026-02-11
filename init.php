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





$checkNav = $dbe->prepare("
    SELECT id 
    FROM store_navigation 
    WHERE category_id=? AND page_name=? AND active=? 
    LIMIT 1
");
$category_id = 1100;
$page_name = 'breads_forecasting';
$active = 1;

$checkNav->bind_param("isi", $category_id, $page_name, $active);
$checkNav->execute();
$checkResult = $checkNav->get_result();

if ($checkResult->num_rows == 0) {

    // 1. Create table if not exists
    $createTableSQL = "
        CREATE TABLE IF NOT EXISTS `store_forecasting` (
            `id` int NOT NULL AUTO_INCREMENT,
            `branch` varchar(100) DEFAULT NULL,
            `item_id` int DEFAULT NULL,
            `forecast_date` date DEFAULT NULL,
            `forecast_percent` decimal(5,2) DEFAULT NULL,
            `created_by` varchar(100) DEFAULT NULL,
            `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            UNIQUE KEY `uniq_forecast` (`branch`,`item_id`,`forecast_date`)
        )
    ";
    $dbe->query($createTableSQL);

    // 2. Insert navigation entry
    $insertNav = $dbe->prepare("
        INSERT INTO store_navigation 
        (sorting, category_id, menu_name, page_name, display_icon, icon_color, active) 
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    $sorting = 22;
    $menu_name = 'Forecasting';
    $display_icon = 'fa-solid fa-chart-line';
    $icon_color = 'text-primary';
    $insertNav->bind_param("iissssi", $sorting, $category_id, $menu_name, $page_name, $display_icon, $icon_color, $active);
    $insertNav->execute();

    // 3. Update sorting of other menu item
    $dbe->query("
        UPDATE store_navigation 
        SET sorting=23 
        WHERE page_name='submitserver' 
          AND category_id=1100 
          AND active=1
    ");
}






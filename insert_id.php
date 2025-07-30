<?PHP
include 'init.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);	


$query ="SELECT * FROM store_fgts_data";  
$result = mysqli_query($db, $query);  
while($ROWS = mysqli_fetch_array($result))  
{
	$item_name = $ROWS['item_name'];
///	echo $item_name;
	
	echo $item_name." -- ".setItemID($item_name,$db)."<br>";
}
function setItemID($item_name,$db)
{
	$query = "SELECT * FROM store_items WHERE product_name='$item_name'";
	$results = mysqli_query($db, $query);    
	if ( $results->num_rows > 0 ) 
	{ 
	    while($IDROW = mysqli_fetch_array($results))  
		{
			$id = $IDROW['id'];
			
			$queryDataUpdate = "UPDATE store_fgts_data SET item_id='$id' WHERE item_name='$item_name'";
			if ($db->query($queryDataUpdate) === TRUE)
			{
				return "SUCCESS";
			} else {
				return $db->error;
			}			
		}
	} else {
		return 0;
	}
}
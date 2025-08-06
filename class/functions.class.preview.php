<?php
class preview
{	

	public function getTableOfTransferOutBranch($itemname,$branch,$transdate,$shift,$db)
	{
		
		$query = "SELECT * FROM store_transfer_data WHERE transfer_to='$branch' AND report_date='$transdate' AND shift='$shift' AND item_name='$itemname'";
		$result = mysqli_query($db, $query);    
	    if ( $result->num_rows > 0 ) 
	    { 
	    	$value = '';
		    while($ROWS = mysqli_fetch_array($result))  
			{
				$value = $ROWS['transfer_to'];
			}
			return $value;
		} else {
			return '';
		}
	}

	public function getItemPricePreviePage($itemname,$db)
	{
		$query ="SELECT * FROM store_items WHERE product_name='$itemname'";  
		$result = mysqli_query($db, $query); 
		if ($result->num_rows > 0) {
			$unit_price = 0;
			while($ROWS = mysqli_fetch_array($result))  
			{
				$rowid = $ROWS['id'];
				$actual_yield = $ROWS['yield_per_kilo'];
				$standard_yield = $ROWS['yield_per_kilo'];
				$unit_price = $ROWS['unit_price'];
				$unit_price_new = $ROWS['unit_price_new'];
				$effectivity_date = $ROWS['effectivity_date'];
				$session_date = $_SESSION['session_date'];		
				$functions = new TheFunctions;
				
				if($session_date >= $effectivity_date)
				{
					$functions->validateDateWithFormat($effectivity_date) ? $unit_price = $ROWS['unit_price_new']:$unit_price = $ROWS['unit_price'];
				}
				else{
					$unit_price = $ROWS['unit_price'];
				}
			}
			return $unit_price;
			
		}
		else
		{
			return 0;
		}
	}
	
	public function getTableValueOnly($table,$column,$itemname,$branch,$transdate,$shift,$db)
	{
		$tbl = "store_".$table."_data";
		$query = "SELECT * FROM $tbl WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_name='$itemname'";
		$result = mysqli_query($db, $query);    
	    if ( $result->num_rows > 0 ) 
	    { 
	    	$value ='';
		    while($ROWS = mysqli_fetch_array($result))  
			{
				$value = $ROWS[$column];
			}
			return $value;
		} else {
			return '';
		}
	}

	public function getTableValue($table,$column,$itemname,$branch,$transdate,$shift,$db)
	{
		$tbl = "store_".$table."_data";
		$query = "SELECT SUM($column) AS value FROM $tbl WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_name='$itemname'";
		$result = mysqli_query($db, $query);    
	    if ( $result->num_rows > 0 ) 
	    { 
	    	$value = 0;
		    while($ROWS = mysqli_fetch_array($result))  
			{
				$value = $ROWS['value'];
			}
			return $value;
		} else {
			return 0;
		}
	}

	public function getTableValuetransferIn($table,$column,$itemname,$branch,$transdate,$shift,$db)
	{
		$tbl = "store_".$table."_data";
		$query = "SELECT SUM($column) AS value FROM $tbl WHERE transfer_to='$branch' AND report_date='$transdate' AND shift='$shift' AND item_name='$itemname'";
		$result = mysqli_query($db, $query);    
	    if ( $result->num_rows > 0 ) 
	    { 
	    	$value = 0;
		    while($ROWS = mysqli_fetch_array($result))  
			{
				$value = $ROWS['value'];
			}
			return $value;
		} else {
			return 0;
		}
	}
	

}
<?php
ini_set('display_errors',0);
class FunctionForms
{
	public function threeShiftingPostingStatusGetValue($itemid, $shift, $transdate, $branch, $db)
    {
		$val1 = 'FIRST SHIFT';
		$val2 = 'SECOND SHIFT';
		$val3 = 'THIRD SHIFT';
		$date = new DateTime($transdate);
		
		if ($shift == $val1) {
		    $date->modify('-1 day');
		    $transdate = $date->format('Y-m-d');
		    $shift = $val3;
		}
		elseif($shift == $val2){
			$shift = $val1;
		}
		else {
		    $shift = $val2;
		}
		
		$sql = "SELECT * FROM store_pcount_data WHERE branch='$branch' AND shift='$shift' AND report_date='$transdate' AND item_id='$itemid'";
		$result = $db->query($sql);
		$val = 0;
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc())
			{
				$val = $row['actual_count'];
			}
			return $val;			
		} 
		else
		{	  
			return $val; 
		}
		$conn->close();
    }

    public function twoShiftingPostingStatusGetValue($itemid, $shift, $transdate, $branch, $db)
	{
	    $val1 = 'FIRST SHIFT';
	    $val2 = 'SECOND SHIFT';
	    $date = new DateTime($transdate);
	
	    if ($shift == $val1) {
	        $date->modify('-1 day');
	        $transdate = $date->format('Y-m-d');
	        $shift = $val2;
	    } else {
	        $shift = $val1;
	    }
	    
	    $sql = "SELECT * FROM store_pcount_data WHERE branch='$branch' AND shift='$shift' AND report_date='$transdate' AND item_id='$itemid'";
		$result = $db->query($sql);
		$val = 0;
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc())
			{
				$val = $row['actual_count'];
			}
			return $val;			
		} 
		else
		{	  
			return $val; 
		}
		$conn->close();
	}
	
	
	public function threeShiftingPostingStatusGet($shift, $transdate, $branch, $db)
    {
		$val1 = 'FIRST SHIFT';
		$val2 = 'SECOND SHIFT';
		$val3 = 'THIRD SHIFT';
		$date = new DateTime($transdate);
		
		if ($shift == $val1) {
		    $date->modify('-1 day');
		    $transdate = $date->format('Y-m-d');
		    $shift = $val3;
		}
		elseif($shift == $val2){
			$shift = $val1;
		}
		else {
		    $shift = $val2;
		}
		
		$sql = "SELECT * FROM store_pcount_data WHERE branch='$branch' AND shift='$shift' AND report_date='$transdate' AND posted='Posted' AND status='Closed'";
		$result = $db->query($sql);
		if ($result->num_rows > 0) {
			return 1;
		} 
		else
		{	  
			return 0; 
		}
		$conn->close();
    }

    public function twoShiftingPostingStatusGet($shift, $transdate, $branch, $db)
	{
	    $val1 = 'FIRST SHIFT';
	    $val2 = 'SECOND SHIFT';
	    $date = new DateTime($transdate);
	
	    if ($shift == $val1) {
	        $date->modify('-1 day');
	        $transdate = $date->format('Y-m-d');
	        $shift = $val2;
	    } else {
	        $shift = $val1;
	    }
	    
	    $sql = "SELECT * FROM store_pcount_data WHERE branch='$branch' AND shift='$shift' AND report_date='$transdate' AND posted='Posted' AND status='Closed'";
		$result = $db->query($sql);
		if ($result->num_rows > 0) {
			return 1;
		} 
		else
		{	  
			return 0; 
		}
		$conn->close();
	}

	public function getItemsByItemId($itemid, $db)
    {		
		$sql = "SELECT * FROM store_items WHERE id='$itemid'";
		$result = $db->query($sql);
		if ($result->num_rows > 0) {
			$val = '';
			while($row = $result->fetch_assoc())
			{
				$val = $row['product_name'];
			}
			return $val;
		} 
		else
		{	  
			return ''; 
		}
		$conn->close();
    }
	public function InsertToSummaryIfNotExistNowModule($branch, $transdate, $shift, $supervisor, $category, $itemname, $itemid, $beg, $unitPrice, $db)
    {
    	$total = $beg;
    	$sold = $beg;
    	$shouldBe = $beg;
    	$amount = $sold * $unitPrice;
    	
		$insertQuery = "INSERT INTO store_summary_data(branch,report_date,shift,supervisor,category,item_name,item_id,beginning,should_be,total,sold,unit_price,amount) VALUES ('$branch','$transdate','$shift','$supervisor','$category','$itemname','$itemid','$beg','$shouldBe','$total','$sold','$unitPrice','$amount')";
 		if ($db->query($insertQuery) === TRUE) { } else { }
    }
    public function CheckingInsertToSummaryIfNotExistNowModule($branch,$reportDate,$shift,$itemid,$db)
    {
		$sql = "SELECT * FROM store_summary_data WHERE branch='$branch' AND report_date='$reportDate' AND shift='$shift' AND item_id='$itemid'";
	    $result = $db->query($sql);
	    if ($result->num_rows > 0) {
			return 1;
		}
		else{
			return 0;
		}
	}
	public function backTrachPcountLost($shiftnow, $shiftforward, $transdatenow, $transdateforward, $branch, $db)
	{	
	    $sql = "SELECT * FROM store_summary_data WHERE branch='$branch' AND report_date='$transdatenow' AND shift='$shiftnow' AND actual_count > 0";
	    $result = $db->query($sql);
	
	    if ($result->num_rows > 0) {
	        while ($row = $result->fetch_assoc()) {
	            $supervisor = $row['supervisor'];
	            $category = $row['category'];
	            
	          	
	            $item_id = $row['item_id'];
	            $beg = $row['actual_count'];
	            $unitPrice = $row['unit_price'];
	            
	            $itemname_itemname = $this->getItemsByItemId($item_id, $db);

	            $itemname = $itemname_itemname;
	            
				$this->CheckingInsertToSummaryIfNotExistNowModule($branch,$transdateforward,$shiftforward,$item_id,$db) == 0? $this->InsertToSummaryIfNotExistNowModule($branch, $transdateforward, $shiftforward, $supervisor, $category, $itemname, $item_id, $beg, $unitPrice, $db): '';
	        }
	    
	    } else {
	     return '';
	    }
	
	    $db->close();
	}

	/*######### ABANG na CODE ##############*/
	public function fgtsActualYieldGetPosted($itemid, $shift, $transdate, $branch, $db)
    {		
		$sql = "SELECT SUM(actual_yield) as actualyld FROM store_fgts_data WHERE branch='$branch' AND shift='$shift' AND report_date='$transdate' AND item_id='$itemid' AND posted='Posted'";
		$result = $db->query($sql);
		if ($result->num_rows > 0) {
			$val = 0;
			while($row = $result->fetch_assoc())
			{
				$val = $row['actualyld'];
			}
			return $val;
		} 
		else
		{	  
			return 0; 
		}
		$conn->close();
    }

	public function pcountPcountActualCountGet($itemid, $shift, $transdate, $branch, $db)
    {		
		$sql = "SELECT SUM(actual_count) AS actualCount FROM store_pcount_data WHERE branch='$branch' AND shift='$shift' AND report_date='$transdate' AND item_id='$itemid'";
		$result = $db->query($sql);
		if ($result->num_rows > 0) {
			$val = 0;
			while($row = $result->fetch_assoc())
			{
				$val = $row['actualCount'];
			}
			return $val;
		} 
		else
		{	  
			return 0; 
		}
		$conn->close();
    }
	public function transferTransferOutQuantityGet($itemid, $shift, $transdate, $branch, $db)
    {		
		$sql = "SELECT SUM(quantity) AS tout FROM store_transfer_data WHERE branch='$branch' AND shift='$shift' AND report_date='$transdate' AND item_id='$itemid'";
		$result = $db->query($sql);
		if ($result->num_rows > 0) {
			$val = 0;
			while($row = $result->fetch_assoc())
			{
				$val = $row['tout'];
			}
			return $val;
		} 
		else
		{	  
			return 0; 
		}
		$conn->close();
    }

	public function validateDateWithFormat($date, $format = 'Y-m-d')
    {
        $dateTimeObject = DateTime::createFromFormat($format, $date);

        return $dateTimeObject && $dateTimeObject->format($format) === $date;
    }

    public function itemItemsPriceGet($itemid, $transdate, $db)
    {
        $query = "SELECT * FROM store_items WHERE id='$itemid'";
        $result = mysqli_query($db, $query);

        if ($result->num_rows > 0) {
            while ($ROW = mysqli_fetch_array($result)) {
                $product = $ROW['product_name'];
                $unit_price = $ROW['unit_price'];
                $unit_price_new = $ROW['unit_price_new'];
                $effectivity_date = $ROW['effectivity_date'];
                
                $dateToValidate = $effectivity_date;
                $isValid = $this->validateDateWithFormat($dateToValidate);

                if ($transdate >= $effectivity_date) {
                    return $this->validateDateWithFormat($effectivity_date) ? $ROW['unit_price_new'] : $ROW['unit_price'];
                } else {
                    return $ROW['unit_price'];
                }
            }
        } else {
            return 0;
        }
    }
	public function kiloUsedGet($itemid, $shift, $transdate, $branch, $db)
    {		
		$sql = "SELECT * FROM store_summary_data WHERE branch='$branch' AND shift='$shift' AND report_date='$transdate' AND item_id='$itemid'";
		$result = $db->query($sql);
		if ($result->num_rows > 0) {
			$val = 0;
			while($row = $result->fetch_assoc())
			{
				$val = $row['kilo_used'];
			}
			return $val;
		} 
		else
		{	  
			return 0; 
		}
		$conn->close();
    }
	public function frozenFrozenKiloUsedGet($itemid, $shift, $transdate, $branch, $db)
    {		
		$sql = "SELECT SUM(kilo_used) as actualyld FROM store_frozendough_data WHERE branch='$branch' AND shift='$shift' AND report_date='$transdate' AND item_id='$itemid'";
		$result = $db->query($sql);
		if ($result->num_rows > 0) {
			$val = 0;
			while($row = $result->fetch_assoc())
			{
				$val = $row['actualyld'];
			}
			return $val;
		} 
		else
		{	  
			return 0; 
		}
		$conn->close();
    }

	public function frozenFrozenDoughGet($itemid, $shift, $transdate, $branch, $db)
    {		
		$sql = "SELECT SUM(actual_yield) as actualyld FROM store_frozendough_data WHERE branch='$branch' AND shift='$shift' AND report_date='$transdate' AND item_id='$itemid'";
		$result = $db->query($sql);
		if ($result->num_rows > 0) {
			$val = 0;
			while($row = $result->fetch_assoc())
			{
				$val = $row['actualyld'];
			}
			return $val;
		} 
		else
		{	  
			return 0; 
		}
		$conn->close();
    }
	public function snacksSnacksGet($itemid, $shift, $transdate, $branch, $db)
    {		
		$sql = "SELECT SUM(quantity) as actualyld FROM store_snacks_data WHERE branch='$branch' AND shift='$shift' AND report_date='$transdate' AND item_id='$itemid'";
		$result = $db->query($sql);
		if ($result->num_rows > 0) {
			$val = 0;
			while($row = $result->fetch_assoc())
			{
				$val = $row['actualyld'];
			}
			return $val;
		} 
		else
		{	  
			return 0; 
		}
		$conn->close();
    }

	public function chargesChargesGet($itemid, $shift, $transdate, $branch, $db)
    {		
		$sql = "SELECT SUM(quantity) as actualyld FROM store_charges_data WHERE branch='$branch' AND shift='$shift' AND report_date='$transdate' AND item_id='$itemid'";
		$result = $db->query($sql);
		if ($result->num_rows > 0) {
			$val = 0;
			while($row = $result->fetch_assoc())
			{
				$val = $row['actualyld'];
			}
			return $val;
		} 
		else
		{	  
			return 0; 
		}
		$conn->close();
    }

	public function transferOutActualYieldGet($itemid, $shift, $transdate, $branch, $db)
    {		
		$sql = "SELECT SUM(quantity) as vals FROM store_transfer_data WHERE branch='$branch' AND shift='$shift' AND report_date='$transdate' AND item_id='$itemid'";
		$result = $db->query($sql);
		if ($result->num_rows > 0) {
			$val = 0;
			while($row = $result->fetch_assoc())
			{
				$val = $row['vals'];
			}
			return $val;
		} 
		else
		{	  
			return 0; 
		}
		$conn->close();
    }

	public function stockInGet($itemid, $shift, $transdate, $branch, $db)
    {		
		$sql = "SELECT * FROM store_summary_data WHERE branch='$branch' AND shift='$shift' AND report_date='$transdate' AND item_id='$itemid'";
		$result = $db->query($sql);
		if ($result->num_rows > 0) {
			$val = 0;
			while($row = $result->fetch_assoc())
			{
				$val = $row['stock_in'];
			}
			return $val;
		} 
		else
		{	  
			return 0; 
		}
		$conn->close();
    }
	public function unitPriceGet($itemid, $shift, $transdate, $branch, $db)
    {		
		$sql = "SELECT * FROM store_summary_data WHERE branch='$branch' AND shift='$shift' AND report_date='$transdate' AND item_id='$itemid'";
		$result = $db->query($sql);
		if ($result->num_rows > 0) {
			$val = 0;
			while($row = $result->fetch_assoc())
			{
				$val = $row['unit_price'];
			}
			return $val;
		} 
		else
		{	  
			return 0; 
		}
		$conn->close();
    }

	public function fgtsActualYieldGet($itemid, $shift, $transdate, $branch, $db)
    {		
		$sql = "SELECT SUM(actual_yield) as actualyld FROM store_fgts_data WHERE branch='$branch' AND shift='$shift' AND report_date='$transdate' AND item_id='$itemid'";
		$result = $db->query($sql);
		if ($result->num_rows > 0) {
			$val = 0;
			while($row = $result->fetch_assoc())
			{
				$val = $row['actualyld'];
			}
			return $val;
		} 
		else
		{	  
			return 0; 
		}
		$conn->close();
    }
	public function fgtsKiloUsedGet($itemid, $shift, $transdate, $branch, $db)
    {		
		$sql = "SELECT SUM(kilo_used) as actualyld FROM store_fgts_data WHERE branch='$branch' AND shift='$shift' AND report_date='$transdate' AND item_id='$itemid'";
		$result = $db->query($sql);
		if ($result->num_rows > 0) {
			$val = 0;
			while($row = $result->fetch_assoc())
			{
				$val = $row['actualyld'];
			}
			return $val;
		} 
		else
		{	  
			return 0; 
		}
		$conn->close();
    }

	public function actualCountGet($itemid, $shift, $transdate, $branch, $db)
    {		
		$sql = "SELECT * FROM store_summary_data WHERE branch='$branch' AND shift='$shift' AND report_date='$transdate' AND item_id='$itemid'";
		$result = $db->query($sql);
		if ($result->num_rows > 0) {
			$val = 0;
			while($row = $result->fetch_assoc())
			{
				$val = $row['actual_count'];
			}
			return $val;
		} 
		else
		{	  
			return 0; 
		}
		$conn->close();
    }

	public function damageGet($itemid, $shift, $transdate, $branch, $db)
    {		
		$sql = "SELECT * FROM store_summary_data WHERE branch='$branch' AND shift='$shift' AND report_date='$transdate' AND item_id='$itemid'";
		$result = $db->query($sql);
		if ($result->num_rows > 0) {
			$val = 0;
			while($row = $result->fetch_assoc())
			{
				$val = $row['damaged'];
			}
			return $val;
		} 
		else
		{	  
			return 0; 
		}
		$conn->close();
    }

	public function complementaryGet($itemid, $shift, $transdate, $branch, $db)
    {		
		$sql = "SELECT * FROM store_summary_data WHERE branch='$branch' AND shift='$shift' AND report_date='$transdate' AND item_id='$itemid'";
		$result = $db->query($sql);
		if ($result->num_rows > 0) {
			$val = 0;
			while($row = $result->fetch_assoc())
			{
				$val = $row['complimentary'];
			}
			return $val;
		} 
		else
		{	  
			return 0; 
		}
		$conn->close();
    }
	public function boGet($itemid, $shift, $transdate, $branch, $db)
    {		
		$sql = "SELECT * FROM store_summary_data WHERE branch='$branch' AND shift='$shift' AND report_date='$transdate' AND item_id='$itemid'";
		$result = $db->query($sql);
		if ($result->num_rows > 0) {
			$val = 0;
			while($row = $result->fetch_assoc())
			{
				$val = $row['bo'];
			}
			return $val;
		} 
		else
		{	  
			return 0; 
		}
		$conn->close();
    }
	public function snacksGet($itemid, $shift, $transdate, $branch, $db)
    {		
		$sql = "SELECT * FROM store_summary_data WHERE branch='$branch' AND shift='$shift' AND report_date='$transdate' AND item_id='$itemid'";
		$result = $db->query($sql);
		if ($result->num_rows > 0) {
			$val = 0;
			while($row = $result->fetch_assoc())
			{
				$val = $row['snacks'];
			}
			return $val;
		} 
		else
		{	  
			return 0; 
		}
		$conn->close();
    }
	public function chargesGet($itemid, $shift, $transdate, $branch, $db)
    {		
		$sql = "SELECT * FROM store_summary_data WHERE branch='$branch' AND shift='$shift' AND report_date='$transdate' AND item_id='$itemid'";
		$result = $db->query($sql);
		if ($result->num_rows > 0) {
			$val = 0;
			while($row = $result->fetch_assoc())
			{
				$val = $row['charges'];
			}
			return $val;
		} 
		else
		{	  
			return 0; 
		}
		$conn->close();
    }

	public function transferOutGet($itemid, $shift, $transdate, $branch, $db)
    {		
		$sql = "SELECT * FROM store_summary_data WHERE branch='$branch' AND shift='$shift' AND report_date='$transdate' AND item_id='$itemid'";
		$result = $db->query($sql);
		if ($result->num_rows > 0) {
			$val = 0;
			while($row = $result->fetch_assoc())
			{
				$val = $row['t_out'];
			}
			return $val;
		} 
		else
		{	  
			return 0; 
		}
		$conn->close();
    }
	public function frozenDoughGet($itemid, $shift, $transdate, $branch, $db)
    {		
		$sql = "SELECT * FROM store_summary_data WHERE branch='$branch' AND shift='$shift' AND report_date='$transdate' AND item_id='$itemid'";
		$result = $db->query($sql);
		if ($result->num_rows > 0) {
			$val = 0;
			while($row = $result->fetch_assoc())
			{
				$val = $row['frozendough'];
			}
			return $val;
		} 
		else
		{	  
			return 0; 
		}
		$conn->close();
    }
	public function transferInGet($itemid, $shift, $transdate, $branch, $db)
    {		
		$sql = "SELECT * FROM store_summary_data WHERE branch='$branch' AND shift='$shift' AND report_date='$transdate' AND item_id='$itemid'";
		$result = $db->query($sql);
		if ($result->num_rows > 0) {
			$val = 0;
			while($row = $result->fetch_assoc())
			{
				$val = $row['t_in'];
			}
			return $val;
		} 
		else
		{	  
			return 0; 
		}
		$conn->close();
    }
	public function threeShiftingBegGet($itemid, $shift, $transdate, $branch, $db)
    {
		$val1 = 'FIRST SHIFT';
		$val2 = 'SECOND SHIFT';
		$val3 = 'THIRD SHIFT';
		$date = new DateTime($transdate);
		
		if ($shift == $val1) {
		    $date->modify('-1 day');
		    $transdate = $date->format('Y-m-d');
		    $shift = $val3;
		}
		elseif($shift == $val2){
			$shift = $val1;
		}
		else {
		    $shift = $val2;
		}
		
		$sql = "SELECT * FROM store_summary_data WHERE branch='$branch' AND shift='$shift' AND report_date='$transdate' AND item_id='$itemid'";
		$result = $db->query($sql);
		if ($result->num_rows > 0) {
			$val = 0;
			while($row = $result->fetch_assoc())
			{
				$val = $row['actual_count'];
			}
			return $val;
		} 
		else
		{	  
			return 0; 
		}
		$conn->close();
    }

    public function twoShiftingBegGet($itemid, $shift, $transdate, $branch, $db)
	{
	    $val1 = 'FIRST SHIFT';
	    $val2 = 'SECOND SHIFT';
	    $date = new DateTime($transdate);
	
	    if ($shift == $val1) {
	        $date->modify('-1 day');
	        $transdate = $date->format('Y-m-d');
	        $shift = $val2;
	    } else {
	        $shift = $val1;
	    }
	    
	    $sql = "SELECT * FROM store_summary_data WHERE branch='$branch' AND shift='$shift' AND report_date='$transdate' AND item_id='$itemid'";
		$result = $db->query($sql);
		if ($result->num_rows > 0) {
			$val = 0;
			while($row = $result->fetch_assoc())
			{
				$val = $row['actual_count'];
			}
			return $val;
		} 
		else
		{	  
			return 0; 
		}
		$conn->close();
	}
	public function threeShiftingShiftGet($shift)
    {
		$val1 = 'FIRST SHIFT';
		$val2 = 'SECOND SHIFT';
		$val3 = 'THIRD SHIFT';
		
		if ($shift == $val1) {
		    $shift = $val2;
		}
		elseif($shift == $val2){
			$shift = $val3;
		}
		else {
		    $shift = $val1;
		}
		return $shift;
    }

	public function twoShiftingShiftGet($shift)
	{
	    $val1 = 'FIRST SHIFT';
	    $val2 = 'SECOND SHIFT';
	
	    if ($shift == $val1) 
	    {
	        $shift = $val2;
	    } 
	    else 
	    {
	        $shift = $val1;
	    }
	    return $shift;   
	}
	public function threeShiftingTransDateGet($shift,$transdate)
    {
		$val1 = 'FIRST SHIFT';
		$val2 = 'SECOND SHIFT';
		$val3 = 'THIRD SHIFT';
		$date = new DateTime($transdate);
		
		if ($shift == $val3) {
		    $date->modify('+1 day');
		    $transdate = $date->format('Y-m-d');
		}
		return $transdate;
    }
	public function twoShiftingTransDateGet($shift,$transdate)
    {
		$val1 = 'FIRST SHIFT';
		$val2 = 'SECOND SHIFT';
		$date = new DateTime($transdate);
		
		if ($shift == $val2) {
		    $date->modify('+1 day');
		    $transdate = $date->format('Y-m-d');
		}
		return $transdate;
    }
    
    public function threeShiftingShiftGetback($shift)
    {
		$val1 = 'FIRST SHIFT';
		$val2 = 'SECOND SHIFT';
		$val3 = 'THIRD SHIFT';
		
		if ($shift == $val1) {
		    $shift = $val3;
		}
		elseif($shift == $val2){
			$shift = $val1;
		}
		else {
		    $shift = $val2;
		}
		return $shift;
    }

	public function twoShiftingShiftGetback($shift)
	{
	    $val1 = 'FIRST SHIFT';
	    $val2 = 'SECOND SHIFT';
	
	    if ($shift == $val1) 
	    {
	        $shift = $val2;
	    } 
	    else 
	    {
	        $shift = $val1;
	    }
	    return $shift;   
	}

    
	public function threeShiftingTransDateGetback($shift,$transdate)
    {
		$val1 = 'FIRST SHIFT';
		$val2 = 'SECOND SHIFT';
		$val3 = 'THIRD SHIFT';
		$date = new DateTime($transdate);
		
		if ($shift == $val1) {
		    $date->modify('-1 day');
		    $transdate = $date->format('Y-m-d');
		}
		return $transdate;
    }
	public function twoShiftingTransDateGetback($shift,$transdate)
    {
		$val1 = 'FIRST SHIFT';
		$val2 = 'SECOND SHIFT';
		$date = new DateTime($transdate);
		
		if ($shift == $val1) {
		    $date->modify('-1 day');
		    $transdate = $date->format('Y-m-d');
		}
		return $transdate;
    }
	
}
?>

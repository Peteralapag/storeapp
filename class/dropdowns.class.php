<?php
class DropDowns
{
	
	public function selectEmployeeAllHo($db){
		$query ="SELECT * FROM tbl_employees_ho WHERE status='Active'";  
		$result = mysqli_query($db, $query);  
		echo '<option value="">--- SELECT EMPLOYEE ---</option>';
		while($ROWS = mysqli_fetch_array($result))  
		{
			$idcode = $ROWS["idcode"];
			$acctname = $ROWS["acctname"];
			
			echo '<option value="'.$idcode.'">'.$acctname.'</option>';
		}
	}

	
	public function selectItemsViaCategory($category,$db){
		$query ="SELECT * FROM store_items WHERE category_name='$category'";  
		$result = mysqli_query($db, $query);  
		echo '<option value="">--- SELECT ITEM ---</option>';
		while($ROWS = mysqli_fetch_array($result))  
		{
			echo '<option>'.$ROWS["product_name"].'</option>';
		}
	}


	public function selectEmployeeViaBranch($branch,$db){
		$query ="SELECT * FROM tbl_employees WHERE branch='$branch' AND status='Active'";  
		$result = mysqli_query($db, $query);  
		echo '<option value="">--- SELECT EMPLOYEE ---</option>';
		while($ROWS = mysqli_fetch_array($result))  
		{
			$idcode = $ROWS["idcode"];
			$acctname = $ROWS["acctname"];
			
			echo '<option value="'.$idcode.'">'.$acctname.'</option>';
		}
	}

	public function selectItems($db){
		$query ="SELECT * FROM store_items";  
		$result = mysqli_query($db, $query);  
		echo '<option value="">--- SELECT ITEM ---</option>';
		while($ROWS = mysqli_fetch_array($result))  
		{
			echo '<option>'.$ROWS["product_name"].'</option>';
		}
	}

	public function DiscountType($type)
	{
		echo '<option value="">--- SELECT ---</option>';
		$includes = array("SENIOR DISCOUNT" => "SENIOR DISCOUNT","ICE CREAM DISCOUNT" => "ICE CREAM DISCOUNT","CAKE DISCOUNT" => "CAKE DISCOUNT","SPECIALS DISCOUNT" => "SPECIALS DISCOUNT");				
		foreach ( $includes as $key => $value )
		{
			$chosen = '';
			if($value == $type)		
			{
				$chosen = 'selected';
			}
			echo '<option '.$chosen.' value="'.$value.'">'.$key.'</option>';
		}
	}
	public function DiscountItemCategory($category)
	{
		$option = '<option value="">--- SELECT ---</option>';
		
		if($category=='SENIOR DISCOUNT'){
			$includes = array(
				"ROSE CLASSIC HOT BREAD" => "ROSE CLASSIC HOT BREAD",
				"ROSE HIGH-END BREADS" => "ROSE HIGH-END BREADS",
				"ROSE BINALOT & PASALUBONG" => "ROSE BINALOT & PASALUBONG",
				"ROSE NUTRI-DENSE" => "ROSE NUTRI-DENSE",
				"ROSE TASTY LOAF" => "ROSE TASTY LOAF",
				"ROSE CLASSIC SPECIAL" => "ROSE CLASSIC SPECIAL",
				"ALL TIME CAKE (CLASSIC)" => "ALL TIME CAKE (CLASSIC)",
				"CELEBRATION CAKES (FLAGSLIP)" => "CELEBRATION CAKES (FLAGSLIP)",
				"PREMIUM CAKES (HIGH-END)" => "PREMIUM CAKES (HIGH-END)",
				"HIGH-END COFFEE" => "HIGH-END COFFEE",
				"FGFRAP" => "FGFRAP"
			);
		}
		else if($category=='ICE CREAM DISCOUNT'){
			$includes = array("ICE CREAM" => "ICE CREAM");
		}
		else if($category=='CAKE DISCOUNT'){
			$includes = array(
				"ALL TIME CAKE (CLASSIC)" => "ALL TIME CAKE (CLASSIC)",
				"CELEBRATION CAKES (FLAGSLIP)" => "CELEBRATION CAKES (FLAGSLIP)",
				"PREMIUM CAKES (HIGH-END)" => "PREMIUM CAKES (HIGH-END)"
			);
		}
		else if($category=='SPECIALS DISCOUNT'){
			$includes = array(
				"ROSE CLASSIC SPECIAL" => "ROSE CLASSIC SPECIAL",
				"ALL TIME CAKE (CLASSIC)" => "ALL TIME CAKE (CLASSIC)"
			);
		}
		
												
		foreach ( $includes as $key => $value )
		{
			$chosen = '';
			if($value == $category)		
			{
				$chosen = 'selected';
			}
			echo '<option '.$chosen.' value="'.$value.'">'.$key.'</option>';
		}
	}

	public function GetPrefix($params)
    {
    	$return = '<option value="">-- SELECT --</option>';
        $units = array(            
            "PCF" => "PCF",            
            "WH" => "WH",
			"iBRANCH" => "iBRANCH",
			"SUP" => "SUP"
        );
        foreach ( $units as $key => $value )
        {
            $selected = '';
            if($value == $params)
            {
                $selected = 'selected';
            }
            $return .= '<option '.$selected.' value="'.$value.'">'.$key.'</option>';                        
        }
        return $return;

    }
	public function GetUOM($params)
    {
        $return = '<option value="">-- SELECT UNITS --</option>';
        $units = array(            
            "KILO" => "KILO",            
            "GRAM" => "GRAM",
			"PCS" => "PCS",
			"PADS" => "PADS",
			"LAYER" => "LAYER",
            "BOX" => "BOX",            
            "PACK" => "PACK",
            "PAIL" => "PAIL",
            "SACK" => "SACK",
            "BOTTLE" => "BOTTLE",
            "METER" => "METER",
            "FOOT" => "FOOT"
        );
        foreach ( $units as $key => $value )
        {
            $selected = '';
            if($value == $params)
            {
                $selected = 'selected';
            }
            $return .= '<option '.$selected.' value="'.$value.'">'.$key.'</option>';                        
        }
        return $return;
    }
	public function GetItems($items,$db)
	{
		$query = "SELECT * FROM store_items WHERE branch='$branch' AND status='Active'";
		$return = '';
		$result = mysqli_query($db, $query);    
	    if ( $result->num_rows > 0 ) 
	    { 
		    while($ROWS = mysqli_fetch_array($result))  
			{
				$employee = $ROWS['firstname']." ".$ROWS['lastname'];
				$return .= '<option value="'.$employee.'">';
			}
			return $return;
		} else {
			return '<option value="No Records">';
		}
	}
	public function ItemCategory($category)
	{
		$option = '<option value="">--- SELECT ---</option>';
		$includes = array(
		
				"ROSE CLASSIC HOT BREAD" => "ROSE CLASSIC HOT BREAD",
				"ROSE HIGH-END BREADS" => "ROSE HIGH-END BREADS",
				"ROSE BINALOT & PASALUBONG" => "ROSE BINALOT & PASALUBONG",
				"ROSE NUTRI-DENSE" => "ROSE NUTRI-DENSE",
				"ROSE TASTY LOAF" => "ROSE TASTY LOAF",
				"ROSE CLASSIC SPECIAL" => "ROSE CLASSIC SPECIAL",
				"ALL TIME CAKE (CLASSIC)" => "ALL TIME CAKE (CLASSIC)",
				"CELEBRATION CAKES (FLAGSLIP)" => "CELEBRATION CAKES (FLAGSLIP)",
				"PREMIUM CAKES (HIGH-END)" => "PREMIUM CAKES (HIGH-END)",
				"HIGH-END COFFEE" => "HIGH-END COFFEE",
				"FGFRAP" => "FGFRAP",
				"CAKES" => "CAKES",
				"BEVERAGES" => "BEVERAGES",
				"BOTTLED WATER" => "BOTTLED WATER",
				"ICE CREAM" => "ICE CREAM",
				"BREADS" => "BREADS",
				"COFFEE" => "COFFEE",
				"MILK TEA" => "MILK TEA",
				"MERCHANDISE OTHERS" => "MERCHANDISE OTHERS"
			);
		
				
		foreach ( $includes as $key => $value )
		{
			$chosen = '';
			if($value == $category)		
			{
				$chosen = 'selected';
			}
			echo '<option '.$chosen.' value="'.$value.'">'.$key.'</option>';
		}
	}
	public function GetPerson($branch,$position,$people,$db)
	{
		if($position == 'baker' || $position == 'cashier'){
			$q = "AND firstname LIKE '%$people%' OR lastname LIKE '%$people%'";
		}
		else{
			$q = "";
		}
		$query = "SELECT * FROM tbl_employees WHERE branch='$branch' AND status='Active'";
		$return = '';
		$result = mysqli_query($db, $query);    
	    if ( $result->num_rows > 0 ) 
	    { 
		    while($ROWS = mysqli_fetch_array($result))  
			{
				$selected = '';
				$employee = $ROWS['firstname']." ".$ROWS['lastname'];
				if($people == $employee)
				{
					$selected = 'selected';
				}
				$return .= '<option '.$selected.' value="'.$employee.'">';
			}
			return $return;
		} else {
			return '<option value="No Records">';
		}
	}
	public function GetStoreShifting($params)
	{	
		$shifting = array(
	        "TWO SHIFTING" => "2",
            "THREE SHIFTING" => "3"
        );
        foreach ( $shifting as $key => $value )
        {
            $selected = '';
            if($value == $params)
            {
                $selected = 'selected';
            }
            $return .= '<option '.$selected.' value="'.$value.'">'.$key.'</option>';                        
        }
        return $return;

	}
	public function GetShift($shift,$shifting)
	{
		$option = '<option value="">--- SELECT ---</option>';
		if($shifting == '2')
		{
			$includes = array("FIRST SHIFT" => "FIRST SHIFT","SECOND SHIFT" => "SECOND SHIFT");
		} else {
			$includes = array("FIRST SHIFT" => "FIRST SHIFT","SECOND SHIFT" => "SECOND SHIFT","THIRD SHIFT" => "THIRD SHIFT");
		}
		foreach ( $includes as $key => $value )
		{
			$chosen = '';
			if($value == $shift)
			{
				$chosen = 'selected';
			}
			$option .= '<option '.$chosen.' value="'.$value.'">'.$key.'</option>';
		}
		return $option;
	}
	public function GetTimeCovered($tcovered,$db)
	{
		$query = "SELECT * FROM shifting";
		$result = mysqli_query($db, $query);    
	    if ( $result->num_rows > 0 ) 
	    { 
	    	$option = '<option value="">-- SELECT --</option>';
		    while($ROWS = mysqli_fetch_array($result))  
			{
				$shift = $ROWS['mysked'];
				$chosen = '';
				if($shift == $tcovered)
				{
					$chosen = 'selected';
				}
				$option .= '<option '.$chosen.' value="'.$shift.'">'.$shift.'</option>';
			}
			return $option;			
		} else {
			$return = '<option value="">No Records</option>';
		}
	}

}
<?php
class UserLogin
{
	public function GetUsernames($db)
	{
		$query = "SELECT * FROM tbl_system_user WHERE void_access=0";
		$return = '<option value=""> --- SELECT USER --- </option>';
		$result = mysqli_query($db, $query);    
	    if ( $result->num_rows > 0 ) 
	    { 
		    while($ROWS = mysqli_fetch_array($result))  
			{
				$employee = $ROWS['firstname']." ".$ROWS['lastname'];
				$return .= '<option value="'.$employee.'">USER</option>';
			}
			return $return;
		} else {
			return '<option value="">No User</option>';
		}
	}
}
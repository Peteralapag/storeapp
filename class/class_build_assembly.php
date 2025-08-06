<?php
class BuildAssembly
{
	public function GetMatsTotal($mn,$item_code,$db)
	{
		$query = "SELECT * FROM store_bakers_guide WHERE itemcode='$item_code' AND rawmats_name='$mn' ORDER BY id ASC";
		$result = mysqli_query($db, $query);
		if ( $result->num_rows > 0 ) 
	    { 
			while ($ROWS = mysqli_fetch_array($result)) {
				return $ROWS['total'];
			}
		} else {
			return 0;
		}
	}
	public function GetYielding($item_id, $db)
	{
		$query = "SELECT * FROM store_items WHERE id='$item_id'";
		$result = mysqli_query($db, $query);    
	    if ( $result->num_rows > 0 ) 
	    { 
		    while($ROWS = mysqli_fetch_array($result))  
			{
				return $ROWS['yield_per_kilo'];
			}
		} else {
			return 0;
		}
	}	
	public function GetItemCode($item_id,$db)
	{
		$query = "SELECT * FROM store_items WHERE id='$item_id'";
		$result = mysqli_query($db, $query);    
	    if ( $result->num_rows > 0 ) 
	    { 
		    while($ROWS = mysqli_fetch_array($result))  
			{
				return $ROWS['itemcode'];
			}
		} else {
			return 0;
		}
	}
	public function GetBakersGuideData($item_code,$db)
	{
		$rms = '';
		$rms .= '<table style="width: 100%" class="table table-bordered table-data">
			<tr>
				<th style="width:50px;text-align:center">#</th>
				<th>RAWMATS</th>
				<th>DOUGH</th>
				<th>FILLINGS</th>
				<th>TOPPINGS</th>
				<th>COATING</th>
				<th>TOTAL</th>
			</tr>
		';
		$queryMats = "SELECT * FROM store_bakers_guide WHERE itemcode='$item_code'";
		$Matsresult = mysqli_query($db, $queryMats);		
		$rows = [];
		while ($DATAROWS = mysqli_fetch_array($Matsresult))
		{
		    $rows[] = $DATAROWS;
		}								
		
		if (empty($rows))
		{
			$rms .='
				<tr>
					<td colspan="7" style="text-align:center"><i class="fa fa-bell"></i>&nbsp;&nbsp;No Records</td>
				</tr>
			';
		} else {
			$alphabetIndices = range('A', 'Z');
		    foreach ($rows as $index => $DATAROWS) {
		        $alphabetIndex = $alphabetIndices[$index % count($alphabetIndices)];
		        $DATAROWS['alphabet_index'] = $alphabetIndex;					
		        $marsrowid = $DATAROWS['id'];
		        $rawmats_name = $DATAROWS['rawmats_name'];
		        $dough = $DATAROWS['dough'];
		        $fillings = $DATAROWS['fillings'];
		        $toppings = $DATAROWS['toppings'];
		        $coating = $DATAROWS['coating'];
		        $total_mats = $DATAROWS['total'];
			$rms .='
				<tr>
					<td style="text-align:center;font-weight:600">'.$alphabetIndex.'</td>
					<td>'.$rawmats_name.'</td>
					<td style="text-align:right; padding-right:10px;">'.$dough.'</td>
					<td style="text-align:right; padding-right:10px;">'.$fillings.'</td>
					<td style="text-align:right; padding-right:10px;">'.$toppings.'</td>
					<td style="text-align:right; padding-right:10px;">'.$coating.'</td>
					<td style="text-align:right; padding-right:10px;">'.$total_mats.'</td>
				</tr>
			';
			}
		} 
		$rms .='
			</table>
		';
		return $rms;
	}
}
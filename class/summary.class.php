<?php
class TheSummary
{
	public function SendPakatiToServer($storebranch,$transdate,$shift,$time_stamp,$table,$rowid,$db,$mainconn)
	{
		$query ="SELECT * FROM $table WHERE branch='$storebranch' AND report_date='$transdate' AND shift='$shift' AND id='$rowid'";
		$result = mysqli_query($db, $query);  
		while($ROW = mysqli_fetch_array($result))  
		{
			$bid = $ROW['id'];$branch = $ROW['branch'];$report_date = $ROW['report_date'];$shift = $ROW['shift'];$time_covered = $ROW['time_covered'];$employee_name = $ROW['employee_name'];
			$supervisor = $ROW['supervisor'];$slip_no = $ROW['slip_no'];$denomination = $ROW['denomination'];$quantity = $ROW['quantity'];$total_amount = $ROW['total_amount'];
			$date_created = $ROW['date_created'];$updated_by = $ROW['updated_by'];$date_updated = $ROW['date_updated'];$audit_mode = $ROW['audit_mode'];$posted = $ROW['posted'];$status = $ROW['status'];
			
			$update = "
				branch='$branch',report_date='$report_date',shift='$shift',time_covered='$time_covered',employee_name='$employee_name',supervisor='$supervisor',
				slip_no='$slip_no',denomination='$denomination',quantity='$quantity',total_amount='$total_amount',date_created='$date_created',
				updated_by='$updated_by',date_updated='$date_updated',audit_mode='$audit_mode',posted='$posted',status='$status'
			";			
		}		
		$queryRemote ="SELECT * FROM $table WHERE branch='$storebranch' AND report_date='$transdate' AND shift='$shift' AND bid='$bid'";
		$remoteResult = mysqli_query($mainconn, $queryRemote);  
		if($remoteResult->num_rows > 0)
		{
			$queryDataUpdate = "UPDATE $table SET $update WHERE bid='$rowid' AND branch='$storebranch' AND report_date='$transdate' AND shift='$shift'";
			if ($mainconn->query($queryDataUpdate) === TRUE) { } else { echo "ERROR::: ".$mainconn->error; }
		} else {
			$queryInsert = "INSERT INTO $table (`bid`,`branch`,`report_date`,`shift`,`time_covered`,`employee_name`,`supervisor`,`slip_no`,`denomination`,`quantity`,`total_amount`,`date_created`,`updated_by`,`date_updated`,`audit_mode`,`posted`,`status`)";
			$queryInsert .= "VALUES('$bid','$branch','$report_date','$shift','$time_covered','$employee_name','$supervisor','$slip_no','$denomination','$quantity','$total_amount','$date_created','$updated_by','$date_updated','$audit_mode','$posted','$status')";
			if ($mainconn->query($queryInsert) === TRUE) { } else { echo "ERROR::: ".$mainconn->error; }
		}
	}

	public function SendGrabToServer($storebranch,$transdate,$shift,$time_stamp,$table,$rowid,$db,$mainconn)
	{
		$query ="SELECT * FROM $table WHERE branch='$storebranch' AND report_date='$transdate' AND shift='$shift' AND id='$rowid'";
		$result = mysqli_query($db, $query);  
		while($ROW = mysqli_fetch_array($result))  
		{
			$bid = $ROW['id'];$branch = $ROW['branch'];$report_date = $ROW['report_date'];$shift = $ROW['shift'];$time_covered = $ROW['time_covered'];$employee_name = $ROW['employee_name'];
			$supervisor = $ROW['supervisor'];$slip_no = $ROW['slip_no'];$category = $ROW['category'];$item_id = $ROW['item_id'];$item_name = $ROW['item_name'];$quantity = $ROW['quantity'];
			$unit_price = $ROW['unit_price'];$total = $ROW['total'];$remarks = $ROW['remarks'];$date_created = $ROW['date_created'];$date_updated = $ROW['date_updated'];
			$updated_by = $ROW['updated_by'];$posted = $ROW['posted'];$status = $ROW['status'];
			
			$update = "
				branch='$branch',report_date='$report_date',shift='$shift',time_covered='$time_covered',employee_name='$employee_name',supervisor='$supervisor',
				slip_no='$slip_no',category='$category',item_id='$item_id',item_name='$item_name',quantity='$quantity',unit_price='$unit_price',total='$total',remarks='$remarks',
				date_created='$date_created',date_updated='$date_updated',updated_by='$updated_by',posted='$posted',status='$status'				
			";			
		}		
		$queryRemote ="SELECT * FROM $table WHERE branch='$storebranch' AND report_date='$transdate' AND shift='$shift' AND bid='$bid'";
		$remoteResult = mysqli_query($mainconn, $queryRemote);  
		if($remoteResult->num_rows > 0)
		{
			$queryDataUpdate = "UPDATE $table SET $update WHERE bid='$rowid' AND branch='$storebranch' AND report_date='$transdate' AND shift='$shift'";
			if ($mainconn->query($queryDataUpdate) === TRUE) { } else { echo "ERROR::: ".$mainconn->error; }
		} else {
			$queryInsert = "INSERT INTO $table (`bid`,`branch`,`report_date`,`shift`,`time_covered`,`employee_name`,`supervisor`,`slip_no`,`category`,`item_id`,`item_name`,`quantity`,`unit_price`,`total`,`remarks`,`date_created`,`date_updated`,`updated_by`,`posted`,`status`)";
			$queryInsert .= "VALUES('$bid','$branch','$report_date','$shift','$time_covered','$employee_name','$supervisor','$slip_no','$category','$item_id','$item_name','$quantity','$unit_price','$total','$remarks','$date_created','$date_updated','$updated_by','$posted','$status')";
			if ($mainconn->query($queryInsert) === TRUE) { } else { echo "ERROR::: ".$mainconn->error; }
		}
	}
	
	public function SendFoodPandaToServer($storebranch,$transdate,$shift,$time_stamp,$table,$rowid,$db,$mainconn)
	{
		$query ="SELECT * FROM $table WHERE branch='$storebranch' AND report_date='$transdate' AND shift='$shift' AND id='$rowid'";
		$result = mysqli_query($db, $query);  
		while($ROW = mysqli_fetch_array($result))  
		{
			$bid = $ROW['id'];$branch = $ROW['branch'];$report_date = $ROW['report_date'];$shift = $ROW['shift'];$time_covered = $ROW['time_covered'];$employee_name = $ROW['employee_name'];
			$supervisor = $ROW['supervisor'];$slip_no = $ROW['slip_no'];$category = $ROW['category'];$item_id = $ROW['item_id'];$item_name = $ROW['item_name'];$quantity = $ROW['quantity'];
			$unit_price = $ROW['unit_price'];$total = $ROW['total'];$remarks = $ROW['remarks'];$date_created = $ROW['date_created'];$date_updated = $ROW['date_updated'];
			$updated_by = $ROW['updated_by'];$posted = $ROW['posted'];$status = $ROW['status'];
			
			$update = "
				branch='$branch',report_date='$report_date',shift='$shift',time_covered='$time_covered',employee_name='$employee_name',supervisor='$supervisor',
				slip_no='$slip_no',category='$category',item_id='$item_id',item_name='$item_name',quantity='$quantity',unit_price='$unit_price',total='$total',remarks='$remarks',
				date_created='$date_created',date_updated='$date_updated',updated_by='$updated_by',posted='$posted',status='$status'				
			";			
		}		
		$queryRemote ="SELECT * FROM $table WHERE branch='$storebranch' AND report_date='$transdate' AND shift='$shift' AND bid='$bid'";
		$remoteResult = mysqli_query($mainconn, $queryRemote);  
		if($remoteResult->num_rows > 0)
		{
			$queryDataUpdate = "UPDATE $table SET $update WHERE bid='$rowid' AND branch='$storebranch' AND report_date='$transdate' AND shift='$shift'";
			if ($mainconn->query($queryDataUpdate) === TRUE) { } else { echo "ERROR::: ".$mainconn->error; }
		} else {
			$queryInsert = "INSERT INTO $table (`bid`,`branch`,`report_date`,`shift`,`time_covered`,`employee_name`,`supervisor`,`slip_no`,`category`,`item_id`,`item_name`,`quantity`,`unit_price`,`total`,`remarks`,`date_created`,`date_updated`,`updated_by`,`posted`,`status`)";
			$queryInsert .= "VALUES('$bid','$branch','$report_date','$shift','$time_covered','$employee_name','$supervisor','$slip_no','$category','$item_id','$item_name','$quantity','$unit_price','$total','$remarks','$date_created','$date_updated','$updated_by','$posted','$status')";
			if ($mainconn->query($queryInsert) === TRUE) { } else { echo "ERROR::: ".$mainconn->error; }
		}
	}


	public function SendGcashToServer($storebranch,$transdate,$shift,$time_stamp,$table,$rowid,$db,$mainconn)
	{
		$query ="SELECT * FROM $table WHERE branch='$storebranch' AND report_date='$transdate' AND shift='$shift' AND id='$rowid'";
		$result = mysqli_query($db, $query);  
		while($ROW = mysqli_fetch_array($result))  
		{
			$bid = $ROW['id'];$branch = $ROW['branch'];$report_date = $ROW['report_date'];$shift = $ROW['shift'];$time_covered = $ROW['time_covered'];$employee_name = $ROW['employee_name'];
			$supervisor = $ROW['supervisor'];$slip_no = $ROW['slip_no'];$category = $ROW['category'];$item_id = $ROW['item_id'];$item_name = $ROW['item_name'];$quantity = $ROW['quantity'];
			$unit_price = $ROW['unit_price'];$total = $ROW['total'];$remarks = $ROW['remarks'];$date_created = $ROW['date_created'];$date_updated = $ROW['date_updated'];
			$updated_by = $ROW['updated_by'];$posted = $ROW['posted'];$status = $ROW['status'];
			
			$update = "
				branch='$branch',report_date='$report_date',shift='$shift',time_covered='$time_covered',employee_name='$employee_name',supervisor='$supervisor',
				slip_no='$slip_no',category='$category',item_id='$item_id',item_name='$item_name',quantity='$quantity',unit_price='$unit_price',total='$total',remarks='$remarks',
				date_created='$date_created',date_updated='$date_updated',updated_by='$updated_by',posted='$posted',status='$status'				
			";			
		}		
		$queryRemote ="SELECT * FROM $table WHERE branch='$storebranch' AND report_date='$transdate' AND shift='$shift' AND bid='$bid'";
		$remoteResult = mysqli_query($mainconn, $queryRemote);  
		if($remoteResult->num_rows > 0)
		{
			$queryDataUpdate = "UPDATE $table SET $update WHERE bid='$rowid' AND branch='$storebranch' AND report_date='$transdate' AND shift='$shift'";
			if ($mainconn->query($queryDataUpdate) === TRUE) { } else { echo "ERROR::: ".$mainconn->error; }
		} else {
			$queryInsert = "INSERT INTO $table (`bid`,`branch`,`report_date`,`shift`,`time_covered`,`employee_name`,`supervisor`,`slip_no`,`category`,`item_id`,`item_name`,`quantity`,`unit_price`,`total`,`remarks`,`date_created`,`date_updated`,`updated_by`,`posted`,`status`)";
			$queryInsert .= "VALUES('$bid','$branch','$report_date','$shift','$time_covered','$employee_name','$supervisor','$slip_no','$category','$item_id','$item_name','$quantity','$unit_price','$total','$remarks','$date_created','$date_updated','$updated_by','$posted','$status')";
			if ($mainconn->query($queryInsert) === TRUE) { } else { echo "ERROR::: ".$mainconn->error; }
		}
	}

	public function SendBoInventorySummaryToServer($storebranch,$transdate,$shift,$time_stamp,$table,$rowid,$db,$mainconn)
	{
		$query ="SELECT * FROM $table WHERE branch='$storebranch' AND report_date='$transdate' AND shift='$shift' AND id='$rowid'";
		$result = mysqli_query($db, $query);  
		while($ROW = mysqli_fetch_array($result))  
		{
			$bid = $ROW['id'];$branch = $ROW['branch'];$report_date = $ROW['report_date'];$shift = $ROW['shift'];$time_covered= $ROW['time_covered'];$category= $ROW['category'];
			$item_id = $ROW['item_id'];$item_name = $ROW['item_name'];$beginning = $ROW['beginning'];$stock_in = $ROW['stock_in'];$receiving_in = $ROW['receiving_in'];$transfer_in = $ROW['transfer_in'];
			$sub_total = $ROW['sub_total'];$transfer_out = $ROW['transfer_out'];$bo = $ROW['bo'];$total = $ROW['total'];$actual_count = $ROW['actual_count'];$difference = $ROW['difference'];
			$price_kg = $ROW['price_kg'];$amount = $ROW['amount'];$date_created = $ROW['date_created'];$posted = $ROW['posted'];$status = $ROW['status'];
					
			$update = "
				branch='$branch',report_date='$report_date',shift='$shift',time_covered='$time_covered',category='$category',
				item_id='$item_id',item_name='$item_name',beginning='$beginning',stock_in='$stock_in',receiving_in='$receiving_in',transfer_in='$transfer_in',
				sub_total='sub_total',transfer_out='$transfer_out',bo='$bo',total='$total',actual_count='$actual_count',difference='$difference',
				price_kg='$price_kg',amount='$amount',date_created='$date_created',posted='$posted',status='$status'
			";			
		}		
		$queryRemote ="SELECT * FROM $table WHERE branch='$storebranch' AND report_date='$transdate' AND shift='$shift' AND bid='$bid'";
		$remoteResult = mysqli_query($mainconn, $queryRemote);  
		if($remoteResult->num_rows > 0)
		{
			$queryDataUpdate = "UPDATE $table SET $update WHERE bid='$rowid' AND branch='$storebranch' AND report_date='$transdate' AND shift='$shift'";
			if ($mainconn->query($queryDataUpdate) === TRUE) { } else { echo "ERROR::: ".$mainconn->error; }
		} else {
			$queryInsert = "INSERT INTO $table (`bid`,`branch`,`report_date`,`shift`,`time_covered`,`category`,`item_id`,`item_name`,`beginning`,`stock_in`,`receiving_in`,`transfer_in`,`sub_total`,`transfer_out`,`bo`,`total`,`actual_count`,`difference`,`price_kg`,`amount`,`date_created`,`posted`,`status`)";
			$queryInsert .= "VALUES('$bid','$branch','$report_date','$shift','$time_covered','$category','$item_id','$item_name','$beginning','$stock_in','$receiving_in','$transfer_in','$sub_total','$transfer_out','$bo','$total','$actual_count','$difference','$price_kg','$amount','$date_created','$posted','$status')";
			if ($mainconn->query($queryInsert) === TRUE) { } else { echo "ERROR::: ".$mainconn->error; }
		}
	}

	public function SendBoInventoryPCountToServer($storebranch,$transdate,$shift,$time_stamp,$table,$rowid,$db,$mainconn)
	{
		$query ="SELECT * FROM $table WHERE branch='$storebranch' AND report_date='$transdate' AND shift='$shift' AND id='$rowid'";
		$result = mysqli_query($db, $query);  
		while($ROW = mysqli_fetch_array($result))  
		{
			$bid = $ROW['id'];$branch = $ROW['branch'];$report_date = $ROW['report_date'];$shift = $ROW['shift'];$time_covered = $ROW['time_covered'];
			$employee_name = $ROW['employee_name'];$supervisor = $ROW['supervisor'];$category = $ROW['category'];$item_id = $ROW['item_id'];
			$item_name = $ROW['item_name'];$actual_count = $ROW['actual_count'];$units = $ROW['units'];$date_created = $ROW['date_created'];$date_updated = $ROW['date_updated'];
			$updated_by = $ROW['updated_by'];$posted = $ROW['posted'];$status = $ROW['status'];$from_shift = $ROW['from_shift'];$to_shift = $ROW['to_shift'];
			$to_shift_date = $ROW['to_shift_date'];
						
			$update = "
				branch='$branch',report_date='$report_date',shift='$shift',time_covered='$time_covered',employee_name='$employee_name',supervisor='$supervisor',category='$category',
				item_id='$item_id',item_name='$item_name',actual_count='$actual_count',units='$units',date_created='$date_created',date_updated='$date_updated',updated_by='$updated_by',posted='$posted',
				status='$status',from_shift='$from_shift',to_shift='$to_shift',to_shift_date='$to_shift_date'
			";			
		}		
		$queryRemote ="SELECT * FROM $table WHERE branch='$storebranch' AND report_date='$transdate' AND shift='$shift' AND bid='$bid'";
		$remoteResult = mysqli_query($mainconn, $queryRemote);  
		if($remoteResult->num_rows > 0)
		{
			$queryDataUpdate = "UPDATE $table SET $update WHERE bid='$rowid' AND branch='$storebranch' AND report_date='$transdate' AND shift='$shift'";
			if ($mainconn->query($queryDataUpdate) === TRUE) { } else { echo "ERROR::: ".$mainconn->error; }
		} else {
			$queryInsert = "INSERT INTO $table (`bid`,`branch`,`report_date`,`shift`,`time_covered`,`employee_name`,`supervisor`,`category`,`item_id`,`item_name`,`actual_count`,`units`,`date_created`,`date_updated`,`updated_by`,`posted`,`status`,`from_shift`,`to_shift`,`to_shift_date`)";
			$queryInsert .= "VALUES('$bid','$branch','$report_date','$shift','$time_covered','$employee_name','$supervisor','$category','$item_id','$item_name','$actual_count','$units','$date_created','$date_updated','$updated_by','$posted','$status','$from_shift','$to_shift','$to_shift_date')";
			if ($mainconn->query($queryInsert) === TRUE) { } else { echo "ERROR::: ".$mainconn->error; }
		}
	}

	public function SendBoInventoryBOToServer($storebranch,$transdate,$shift,$time_stamp,$table,$rowid,$db,$mainconn)
	{
		$query ="SELECT * FROM $table WHERE branch='$storebranch' AND report_date='$transdate' AND shift='$shift' AND id='$rowid'";
		$result = mysqli_query($db, $query);  
		while($ROW = mysqli_fetch_array($result))  
		{
			$bid = $ROW['id'];$branch = $ROW['branch'];$report_date = $ROW['report_date'];$shift = $ROW['shift'];$time_covered = $ROW['time_covered'];$employee_name = $ROW['employee_name'];
			$supervisor = $ROW['supervisor'];$slip_no = $ROW['slip_no'];$category = $ROW['category'];$item_id = $ROW['item_id'];$item_name = $ROW['item_name'];$actual_count = $ROW['actual_count'];
			$units = $ROW['units'];$total = $ROW['total'];$remarks = $ROW['remarks'];$date_created = $ROW['date_created'];$date_updated = $ROW['date_updated'];
			$updated_by = $ROW['updated_by'];$posted = $ROW['posted'];$status = $ROW['status'];
			
			$update = "
				branch='$branch',report_date='$report_date',shift='$shift',time_covered='$time_covered',employee_name='$employee_name',supervisor='$supervisor',
				slip_no='$slip_no',category='$category',item_id='$item_id',item_name='$item_name',actual_count='$actual_count',units='$units',total='$total',remarks='$remarks',
				date_created='$date_created',date_updated='$date_updated',updated_by='$updated_by',posted='$posted',status='$status'				
			";			
		}		
		$queryRemote ="SELECT * FROM $table WHERE branch='$storebranch' AND report_date='$transdate' AND shift='$shift' AND bid='$bid'";
		$remoteResult = mysqli_query($mainconn, $queryRemote);  
		if($remoteResult->num_rows > 0)
		{
			$queryDataUpdate = "UPDATE $table SET $update WHERE bid='$rowid' AND branch='$storebranch' AND report_date='$transdate' AND shift='$shift'";
			if ($mainconn->query($queryDataUpdate) === TRUE) { } else { echo "ERROR::: ".$mainconn->error; }
		} else {
			$queryInsert = "INSERT INTO $table (`bid`,`branch`,`report_date`,`shift`,`time_covered`,`employee_name`,`supervisor`,`slip_no`,`category`,`item_id`,`item_name`,`actual_count`,`units`,`total`,`remarks`,`date_created`,`date_updated`,`updated_by`,`posted`,`status`)";
			$queryInsert .= "VALUES('$bid','$branch','$report_date','$shift','$time_covered','$employee_name','$supervisor','$slip_no','$category','$item_id','$item_name','$actual_count','$units','$total','$remarks','$date_created','$date_updated','$updated_by','$posted','$status')";
			if ($mainconn->query($queryInsert) === TRUE) { } else { echo "ERROR::: ".$mainconn->error; }
		}
	}
	public function SendBoInventoryTransferToServer($recipient,$transdate,$shift,$time_stamp,$table,$rowid,$db,$mainconn)
	{
		$query ="SELECT * FROM store_boinventory_transfer_data WHERE $recipient AND report_date='$transdate' AND shift='$shift' AND id='$rowid'";
		$result = mysqli_query($db, $query);  
		while($ROW = mysqli_fetch_array($result))  
		{
			$bid = $ROW['id'];$tid = $ROW['tid'];$branch = $ROW['branch'];$report_date = $ROW['report_date'];$shift = $ROW['shift'];$time_covered = $ROW['time_covered'];
			$employee_name = $ROW['employee_name'];$supervisor = $ROW['supervisor'];$category = $ROW['category'];$item_id = $ROW['item_id'];$item_name = $ROW['item_name'];
			$weight = $ROW['weight'];$units = $ROW['units'];$transfer_to = $ROW['transfer_to'];$date_created = $ROW['date_created'];$date_updated = $ROW['date_updated'];
			$updated_by = $ROW['updated_by'];$posted = $ROW['posted'];$status = $ROW['status'];
	
			$update = "			
				bid='$bid',branch='$branch',report_date='$report_date',shift='$shift',time_covered='$time_covered',employee_name='$employee_name',
				supervisor='$supervisor',category='$category',item_id='$item_id',item_name='$item_name',weight='$weight',units='$units',transfer_to='$transfer_to',
				date_created='$date_created',date_updated='$date_updated',updated_by='$updated_by',posted='$posted',status='$status'
			";			
		}		
		$queryRemote ="SELECT * FROM store_boinventory_transfer_data WHERE $recipient AND report_date='$transdate' AND shift='$shift' AND bid='$bid'";		
		$remoteResult = mysqli_query($mainconn, $queryRemote);  
		if($remoteResult->num_rows > 0)
		{
			$queryDataUpdate = "UPDATE store_boinventory_transfer_data SET $update WHERE $recipient AND report_date='$transdate' AND shift='$shift' AND bid='$bid'";			
			if ($mainconn->query($queryDataUpdate) === TRUE) { } else { echo "ERROR::: ".$mainconn->error; }
		} else {
			$queryInsert = "INSERT INTO store_boinventory_transfer_data (`bid`,`branch`,`report_date`,`shift`,`time_covered`,`employee_name`,`supervisor`,`category`,`item_id`,`item_name`,`weight`,`units`,`transfer_to`,`date_created`,`date_updated`,`updated_by`,`posted`,`status`)";
			$queryInsert .= "VALUES('$bid','$branch','$report_date','$shift','$time_covered','$employee_name','$supervisor','$category','$item_id','$item_name','$weight','$units','$transfer_to','$date_created','$date_updated','$updated_by','$posted','$status')";
			if ($mainconn->query($queryInsert) === TRUE) { } else { echo "ERRORS::: ".$mainconn->error; }
		}
	}

	public function SendBoInventoryReceivingToServer($storebranch,$transdate,$shift,$time_stamp,$table,$rowid,$db,$mainconn)
	{
		$query ="SELECT * FROM $table WHERE branch='$storebranch' AND report_date='$transdate' AND shift='$shift' AND id='$rowid'";
		$result = mysqli_query($db, $query);  
		while($ROW = mysqli_fetch_array($result))  
		{
			$bid = $ROW['id'];$branch = $ROW['branch'];$report_date = $ROW['report_date'];$shift = $ROW['shift'];$time_covered = $ROW['time_covered'];$employee_name = $ROW['employee_name'];
			$supervisor = $ROW['supervisor'];$remarks = $ROW['remarks'];$category = $ROW['category'];$item_id = $ROW['item_id'];$item_name = $ROW['item_name'];$quantity = $ROW['quantity'];
			$units = $ROW['units'];$supp_prefix = $ROW['supp_prefix'];$supplier = $ROW['supplier'];$invdr_no = $ROW['invdr_no'];$date_created = $ROW['date_created'];
			$date_updated = $ROW['date_updated'];$updated_by = $ROW['updated_by'];$posted = $ROW['posted'];$status = $ROW['status'];$slip_no = $ROW['slip_no'];
			
			$update = "
				branch='$branch',report_date='$report_date',shift='$shift',time_covered='$time_covered',employee_name='$employee_name',supervisor='$supervisor',remarks='$remarks',
				category='$category',item_id='$item_id',item_name='$item_name',quantity='$quantity',units='$units',supp_prefix='$supp_prefix',supplier='$supplier',invdr_no='$invdr_no',
				date_created='$date_created',date_updated='$date_updated',updated_by='$updated_by',posted='$posted',status='$status',slip_no='$slip_no'
			";			
		}		
		$queryRemote ="SELECT * FROM $table WHERE branch='$storebranch' AND report_date='$transdate' AND shift='$shift' AND bid='$bid'";
		$remoteResult = mysqli_query($mainconn, $queryRemote);  
		if($remoteResult->num_rows > 0)
		{
			$queryDataUpdate = "UPDATE $table SET $update WHERE bid='$rowid' AND branch='$storebranch' AND report_date='$transdate' AND shift='$shift'";
			if ($mainconn->query($queryDataUpdate) === TRUE) { } else { echo "ERROR::: ".$mainconn->error; }
		} else {
			$queryInsert = "INSERT INTO $table (`bid`,`branch`,`report_date`,`shift`,`time_covered`,`employee_name`,`supervisor`,`remarks`,`category`,`item_id`,`item_name`,`quantity`,`units`,`supp_prefix`,`supplier`,`invdr_no`,`date_created`,`date_updated`,`updated_by`,`posted`,`status`,`slip_no`)";
			$queryInsert .= "VALUES('$bid','$branch','$report_date','$shift','$time_covered','$employee_name','$supervisor','$remarks','$category','$item_id','$item_name','$quantity','$units','$supp_prefix','$supplier','$invdr_no','$date_created','$date_updated','$updated_by','$posted','$status','$slip_no')";
			if ($mainconn->query($queryInsert) === TRUE) { } else { echo "ERROR::: ".$mainconn->error; }
		}
	}
	public function SendScrapInventorySummaryToServer($storebranch,$transdate,$shift,$time_stamp,$table,$rowid,$db,$mainconn)
	{
		$query ="SELECT * FROM $table WHERE branch='$storebranch' AND report_date='$transdate' AND shift='$shift' AND id='$rowid'";
		$result = mysqli_query($db, $query);  
		while($ROW = mysqli_fetch_array($result))  
		{
			$bid = $ROW['id'];$branch = $ROW['branch'];$report_date = $ROW['report_date'];$shift = $ROW['shift'];$time_covered= $ROW['time_covered'];$category= $ROW['category'];
			$item_id = $ROW['item_id'];$item_name = $ROW['item_name'];$beginning = $ROW['beginning'];$stock_in = $ROW['stock_in'];$receiving_in = $ROW['receiving_in'];$transfer_in = $ROW['transfer_in'];
			$sub_total = $ROW['sub_total'];$transfer_out = $ROW['transfer_out'];$bo = $ROW['bo'];$total = $ROW['total'];$actual_count = $ROW['actual_count'];$difference = $ROW['difference'];
			$price_kg = $ROW['price_kg'];$amount = $ROW['amount'];$date_created = $ROW['date_created'];$posted = $ROW['posted'];$status = $ROW['status'];
					
			$update = "
				branch='$branch',report_date='$report_date',shift='$shift',time_covered='$time_covered',category='$category',
				item_id='$item_id',item_name='$item_name',beginning='$beginning',stock_in='$stock_in',receiving_in='$receiving_in',transfer_in='$transfer_in',
				sub_total='sub_total',transfer_out='$transfer_out',bo='$bo',total='$total',actual_count='$actual_count',difference='$difference',
				price_kg='$price_kg',amount='$amount',date_created='$date_created',posted='$posted',status='$status'
			";			
		}		
		$queryRemote ="SELECT * FROM $table WHERE branch='$storebranch' AND report_date='$transdate' AND shift='$shift' AND bid='$bid'";
		$remoteResult = mysqli_query($mainconn, $queryRemote);  
		if($remoteResult->num_rows > 0)
		{
			$queryDataUpdate = "UPDATE $table SET $update WHERE bid='$rowid' AND branch='$storebranch' AND report_date='$transdate' AND shift='$shift'";
			if ($mainconn->query($queryDataUpdate) === TRUE) { } else { echo "ERROR::: ".$mainconn->error; }
		} else {
			$queryInsert = "INSERT INTO $table (`bid`,`branch`,`report_date`,`shift`,`time_covered`,`category`,`item_id`,`item_name`,`beginning`,`stock_in`,`receiving_in`,`transfer_in`,`sub_total`,`transfer_out`,`bo`,`total`,`actual_count`,`difference`,`price_kg`,`amount`,`date_created`,`posted`,`status`)";
			$queryInsert .= "VALUES('$bid','$branch','$report_date','$shift','$time_covered','$category','$item_id','$item_name','$beginning','$stock_in','$receiving_in','$transfer_in','$sub_total','$transfer_out','$bo','$total','$actual_count','$difference','$price_kg','$amount','$date_created','$posted','$status')";
			if ($mainconn->query($queryInsert) === TRUE) { } else { echo "ERROR::: ".$mainconn->error; }
		}
	}
	public function SendScrapInventoryPCountToServer($storebranch,$transdate,$shift,$time_stamp,$table,$rowid,$db,$mainconn)
	{
		$query ="SELECT * FROM $table WHERE branch='$storebranch' AND report_date='$transdate' AND shift='$shift' AND id='$rowid'";
		$result = mysqli_query($db, $query);  
		while($ROW = mysqli_fetch_array($result))  
		{
			$bid = $ROW['id'];$branch = $ROW['branch'];$report_date = $ROW['report_date'];$shift = $ROW['shift'];$time_covered = $ROW['time_covered'];
			$employee_name = $ROW['employee_name'];$supervisor = $ROW['supervisor'];$category = $ROW['category'];$item_id = $ROW['item_id'];
			$item_name = $ROW['item_name'];$actual_count = $ROW['actual_count'];$units = $ROW['units'];$date_created = $ROW['date_created'];$date_updated = $ROW['date_updated'];
			$updated_by = $ROW['updated_by'];$posted = $ROW['posted'];$status = $ROW['status'];$from_shift = $ROW['from_shift'];$to_shift = $ROW['to_shift'];
			$to_shift_date = $ROW['to_shift_date'];
						
			$update = "
				branch='$branch',report_date='$report_date',shift='$shift',time_covered='$time_covered',employee_name='$employee_name',supervisor='$supervisor',category='$category',
				item_id='$item_id',item_name='$item_name',actual_count='$actual_count',units='$units',date_created='$date_created',date_updated='$date_updated',updated_by='$updated_by',posted='$posted',
				status='$status',from_shift='$from_shift',to_shift='$to_shift',to_shift_date='$to_shift_date'
			";			
		}		
		$queryRemote ="SELECT * FROM $table WHERE branch='$storebranch' AND report_date='$transdate' AND shift='$shift' AND bid='$bid'";
		$remoteResult = mysqli_query($mainconn, $queryRemote);  
		if($remoteResult->num_rows > 0)
		{
			$queryDataUpdate = "UPDATE $table SET $update WHERE bid='$rowid' AND branch='$storebranch' AND report_date='$transdate' AND shift='$shift'";
			if ($mainconn->query($queryDataUpdate) === TRUE) { } else { echo "ERROR::: ".$mainconn->error; }
		} else {
			$queryInsert = "INSERT INTO $table (`bid`,`branch`,`report_date`,`shift`,`time_covered`,`employee_name`,`supervisor`,`category`,`item_id`,`item_name`,`actual_count`,`units`,`date_created`,`date_updated`,`updated_by`,`posted`,`status`,`from_shift`,`to_shift`,`to_shift_date`)";
			$queryInsert .= "VALUES('$bid','$branch','$report_date','$shift','$time_covered','$employee_name','$supervisor','$category','$item_id','$item_name','$actual_count','$units','$date_created','$date_updated','$updated_by','$posted','$status','$from_shift','$to_shift','$to_shift_date')";
			if ($mainconn->query($queryInsert) === TRUE) { } else { echo "ERROR::: ".$mainconn->error; }
		}
	}
	public function SendScrapInventoryBOToServer($storebranch,$transdate,$shift,$time_stamp,$table,$rowid,$db,$mainconn)
	{
		$query ="SELECT * FROM $table WHERE branch='$storebranch' AND report_date='$transdate' AND shift='$shift' AND id='$rowid'";
		$result = mysqli_query($db, $query);  
		while($ROW = mysqli_fetch_array($result))  
		{
			$bid = $ROW['id'];$branch = $ROW['branch'];$report_date = $ROW['report_date'];$shift = $ROW['shift'];$time_covered = $ROW['time_covered'];$employee_name = $ROW['employee_name'];
			$supervisor = $ROW['supervisor'];$slip_no = $ROW['slip_no'];$category = $ROW['category'];$item_id = $ROW['item_id'];$item_name = $ROW['item_name'];$actual_count = $ROW['actual_count'];
			$units = $ROW['units'];$total = $ROW['total'];$remarks = $ROW['remarks'];$date_created = $ROW['date_created'];$date_updated = $ROW['date_updated'];
			$updated_by = $ROW['updated_by'];$posted = $ROW['posted'];$status = $ROW['status'];
			
			$update = "
				branch='$branch',report_date='$report_date',shift='$shift',time_covered='$time_covered',employee_name='$employee_name',supervisor='$supervisor',
				slip_no='$slip_no',category='$category',item_id='$item_id',item_name='$item_name',actual_count='$actual_count',units='$units',total='$total',remarks='$remarks',
				date_created='$date_created',date_updated='$date_updated',updated_by='$updated_by',posted='$posted',status='$status'				
			";			
		}		
		$queryRemote ="SELECT * FROM $table WHERE branch='$storebranch' AND report_date='$transdate' AND shift='$shift' AND bid='$bid'";
		$remoteResult = mysqli_query($mainconn, $queryRemote);  
		if($remoteResult->num_rows > 0)
		{
			$queryDataUpdate = "UPDATE $table SET $update WHERE bid='$rowid' AND branch='$storebranch' AND report_date='$transdate' AND shift='$shift'";
			if ($mainconn->query($queryDataUpdate) === TRUE) { } else { echo "ERROR::: ".$mainconn->error; }
		} else {
			$queryInsert = "INSERT INTO $table (`bid`,`branch`,`report_date`,`shift`,`time_covered`,`employee_name`,`supervisor`,`slip_no`,`category`,`item_id`,`item_name`,`actual_count`,`units`,`total`,`remarks`,`date_created`,`date_updated`,`updated_by`,`posted`,`status`)";
			$queryInsert .= "VALUES('$bid','$branch','$report_date','$shift','$time_covered','$employee_name','$supervisor','$slip_no','$category','$item_id','$item_name','$actual_count','$units','$total','$remarks','$date_created','$date_updated','$updated_by','$posted','$status')";
			if ($mainconn->query($queryInsert) === TRUE) { } else { echo "ERROR::: ".$mainconn->error; }
		}
	}
	public function SendScrapInventoryTransferToServer($recipient,$transdate,$shift,$time_stamp,$table,$rowid,$db,$mainconn)
	{
		$query ="SELECT * FROM store_scrapinventory_transfer_data WHERE $recipient AND report_date='$transdate' AND shift='$shift' AND id='$rowid'";
		$result = mysqli_query($db, $query);  
		while($ROW = mysqli_fetch_array($result))  
		{
			$bid = $ROW['id'];$tid = $ROW['tid'];$branch = $ROW['branch'];$report_date = $ROW['report_date'];$shift = $ROW['shift'];$time_covered = $ROW['time_covered'];
			$employee_name = $ROW['employee_name'];$supervisor = $ROW['supervisor'];$category = $ROW['category'];$item_id = $ROW['item_id'];$item_name = $ROW['item_name'];
			$weight = $ROW['weight'];$units = $ROW['units'];$transfer_to = $ROW['transfer_to'];$date_created = $ROW['date_created'];$date_updated = $ROW['date_updated'];
			$updated_by = $ROW['updated_by'];$posted = $ROW['posted'];$status = $ROW['status'];
	
			$update = "			
				bid='$bid',branch='$branch',report_date='$report_date',shift='$shift',time_covered='$time_covered',employee_name='$employee_name',
				supervisor='$supervisor',category='$category',item_id='$item_id',item_name='$item_name',weight='$weight',units='$units',transfer_to='$transfer_to',
				date_created='$date_created',date_updated='$date_updated',updated_by='$updated_by',posted='$posted',status='$status'
			";			
		}		
		$queryRemote ="SELECT * FROM store_scrapinventory_transfer_data WHERE $recipient AND report_date='$transdate' AND shift='$shift' AND bid='$bid'";		
		$remoteResult = mysqli_query($mainconn, $queryRemote);  
		if($remoteResult->num_rows > 0)
		{
			$queryDataUpdate = "UPDATE store_scrapinventory_transfer_data SET $update WHERE $recipient AND report_date='$transdate' AND shift='$shift' AND bid='$bid'";			
			if ($mainconn->query($queryDataUpdate) === TRUE) { } else { echo "ERROR::: ".$mainconn->error; }
		} else {
			$queryInsert = "INSERT INTO store_scrapinventory_transfer_data (`bid`,`branch`,`report_date`,`shift`,`time_covered`,`employee_name`,`supervisor`,`category`,`item_id`,`item_name`,`weight`,`units`,`transfer_to`,`date_created`,`date_updated`,`updated_by`,`posted`,`status`)";
			$queryInsert .= "VALUES('$bid','$branch','$report_date','$shift','$time_covered','$employee_name','$supervisor','$category','$item_id','$item_name','$weight','$units','$transfer_to','$date_created','$date_updated','$updated_by','$posted','$status')";
			if ($mainconn->query($queryInsert) === TRUE) { } else { echo "ERRORS::: ".$mainconn->error; }
		}
	}

	public function SendScrapInventoryReceivingToServer($storebranch,$transdate,$shift,$time_stamp,$table,$rowid,$db,$mainconn)
	{
		$query ="SELECT * FROM $table WHERE branch='$storebranch' AND report_date='$transdate' AND shift='$shift' AND id='$rowid'";
		$result = mysqli_query($db, $query);  
		while($ROW = mysqli_fetch_array($result))  
		{
			$bid = $ROW['id'];$branch = $ROW['branch'];$report_date = $ROW['report_date'];$shift = $ROW['shift'];$time_covered = $ROW['time_covered'];$employee_name = $ROW['employee_name'];
			$supervisor = $ROW['supervisor'];$remarks = $ROW['remarks'];$category = $ROW['category'];$item_id = $ROW['item_id'];$item_name = $ROW['item_name'];$quantity = $ROW['quantity'];
			$units = $ROW['units'];$supp_prefix = $ROW['supp_prefix'];$supplier = $ROW['supplier'];$invdr_no = $ROW['invdr_no'];$date_created = $ROW['date_created'];
			$date_updated = $ROW['date_updated'];$updated_by = $ROW['updated_by'];$posted = $ROW['posted'];$status = $ROW['status'];$slip_no = $ROW['slip_no'];
			
			$update = "
				branch='$branch',report_date='$report_date',shift='$shift',time_covered='$time_covered',employee_name='$employee_name',supervisor='$supervisor',remarks='$remarks',
				category='$category',item_id='$item_id',item_name='$item_name',quantity='$quantity',units='$units',supp_prefix='$supp_prefix',supplier='$supplier',invdr_no='$invdr_no',
				date_created='$date_created',date_updated='$date_updated',updated_by='$updated_by',posted='$posted',status='$status',slip_no='$slip_no'
			";			
		}		
		$queryRemote ="SELECT * FROM $table WHERE branch='$storebranch' AND report_date='$transdate' AND shift='$shift' AND bid='$bid'";
		$remoteResult = mysqli_query($mainconn, $queryRemote);  
		if($remoteResult->num_rows > 0)
		{
			$queryDataUpdate = "UPDATE $table SET $update WHERE bid='$rowid' AND branch='$storebranch' AND report_date='$transdate' AND shift='$shift'";
			if ($mainconn->query($queryDataUpdate) === TRUE) { } else { echo "ERROR::: ".$mainconn->error; }
		} else {
			$queryInsert = "INSERT INTO $table (`bid`,`branch`,`report_date`,`shift`,`time_covered`,`employee_name`,`supervisor`,`remarks`,`category`,`item_id`,`item_name`,`quantity`,`units`,`supp_prefix`,`supplier`,`invdr_no`,`date_created`,`date_updated`,`updated_by`,`posted`,`status`,`slip_no`)";
			$queryInsert .= "VALUES('$bid','$branch','$report_date','$shift','$time_covered','$employee_name','$supervisor','$remarks','$category','$item_id','$item_name','$quantity','$units','$supp_prefix','$supplier','$invdr_no','$date_created','$date_updated','$updated_by','$posted','$status','$slip_no')";
			if ($mainconn->query($queryInsert) === TRUE) { } else { echo "ERROR::: ".$mainconn->error; }
		}
	}


	/* ################################################## Supplies Summary ################################################### */
	public function SendSuppliesSummaryToServer($storebranch,$transdate,$shift,$time_stamp,$table,$rowid,$db,$mainconn)
	{
		$query ="SELECT * FROM $table WHERE branch='$storebranch' AND report_date='$transdate' AND shift='$shift' AND id='$rowid'";
		$result = mysqli_query($db, $query);  
		while($ROW = mysqli_fetch_array($result))  
		{
			$bid = $ROW['id'];$branch = $ROW['branch'];$report_date = $ROW['report_date'];$shift = $ROW['shift'];$time_covered= $ROW['time_covered'];$category= $ROW['category'];
			$item_id = $ROW['item_id'];$item_name = $ROW['item_name'];$beginning = $ROW['beginning'];$stock_in = $ROW['stock_in'];$receiving_in = $ROW['receiving_in'];$transfer_in = $ROW['transfer_in'];
			$sub_total = $ROW['sub_total'];$transfer_out = $ROW['transfer_out'];$bo = $ROW['bo'];$total = $ROW['total'];$actual_count = $ROW['actual_count'];$difference = $ROW['difference'];
			$price_kg = $ROW['price_kg'];$amount = $ROW['amount'];$date_created = $ROW['date_created'];$posted = $ROW['posted'];$status = $ROW['status'];
					
			$update = "
				branch='$branch',report_date='$report_date',shift='$shift',time_covered='$time_covered',category='$category',
				item_id='$item_id',item_name='$item_name',beginning='$beginning',stock_in='$stock_in',receiving_in='$receiving_in',transfer_in='$transfer_in',
				sub_total='sub_total',transfer_out='$transfer_out',bo='$bo',total='$total',actual_count='$actual_count',difference='$difference',
				price_kg='$price_kg',amount='$amount',date_created='$date_created',posted='$posted',status='$status'
			";			
		}		
		$queryRemote ="SELECT * FROM $table WHERE branch='$storebranch' AND report_date='$transdate' AND shift='$shift' AND bid='$bid'";
		$remoteResult = mysqli_query($mainconn, $queryRemote);  
		if($remoteResult->num_rows > 0)
		{
			$queryDataUpdate = "UPDATE $table SET $update WHERE bid='$rowid' AND branch='$storebranch' AND report_date='$transdate' AND shift='$shift'";
			if ($mainconn->query($queryDataUpdate) === TRUE) { } else { echo "ERROR::: ".$mainconn->error; }
		} else {
			$queryInsert = "INSERT INTO $table (`bid`,`branch`,`report_date`,`shift`,`time_covered`,`category`,`item_id`,`item_name`,`beginning`,`stock_in`,`receiving_in`,`transfer_in`,`sub_total`,`transfer_out`,`bo`,`total`,`actual_count`,`difference`,`price_kg`,`amount`,`date_created`,`posted`,`status`)";
			$queryInsert .= "VALUES('$bid','$branch','$report_date','$shift','$time_covered','$category','$item_id','$item_name','$beginning','$stock_in','$receiving_in','$transfer_in','$sub_total','$transfer_out','$bo','$total','$actual_count','$difference','$price_kg','$amount','$date_created','$posted','$status')";
			if ($mainconn->query($queryInsert) === TRUE) { } else { echo "ERROR::: ".$mainconn->error; }
		}
	}
	/* ################################################## Supplies PHYSICAL COUNT ################################################### */
	public function SendSuppliesPCountToServer($storebranch,$transdate,$shift,$time_stamp,$table,$rowid,$db,$mainconn)
	{
		$query ="SELECT * FROM $table WHERE branch='$storebranch' AND report_date='$transdate' AND shift='$shift' AND id='$rowid'";
		$result = mysqli_query($db, $query);  
		while($ROW = mysqli_fetch_array($result))  
		{
			$bid = $ROW['id'];$branch = $ROW['branch'];$report_date = $ROW['report_date'];$shift = $ROW['shift'];$time_covered = $ROW['time_covered'];
			$employee_name = $ROW['employee_name'];$supervisor = $ROW['supervisor'];$category = $ROW['category'];$item_id = $ROW['item_id'];
			$item_name = $ROW['item_name'];$actual_count = $ROW['actual_count'];$units = $ROW['units'];$date_created = $ROW['date_created'];$date_updated = $ROW['date_updated'];
			$updated_by = $ROW['updated_by'];$posted = $ROW['posted'];$status = $ROW['status'];$from_shift = $ROW['from_shift'];$to_shift = $ROW['to_shift'];
			$to_shift_date = $ROW['to_shift_date'];
						
			$update = "
				branch='$branch',report_date='$report_date',shift='$shift',time_covered='$time_covered',employee_name='$employee_name',supervisor='$supervisor',category='$category',
				item_id='$item_id',item_name='$item_name',actual_count='$actual_count',units='$units',date_created='$date_created',date_updated='$date_updated',updated_by='$updated_by',posted='$posted',
				status='$status',from_shift='$from_shift',to_shift='$to_shift',to_shift_date='$to_shift_date'
			";			
		}		
		$queryRemote ="SELECT * FROM $table WHERE branch='$storebranch' AND report_date='$transdate' AND shift='$shift' AND bid='$bid'";
		$remoteResult = mysqli_query($mainconn, $queryRemote);  
		if($remoteResult->num_rows > 0)
		{
			$queryDataUpdate = "UPDATE $table SET $update WHERE bid='$rowid' AND branch='$storebranch' AND report_date='$transdate' AND shift='$shift'";
			if ($mainconn->query($queryDataUpdate) === TRUE) { } else { echo "ERROR::: ".$mainconn->error; }
		} else {
			$queryInsert = "INSERT INTO $table (`bid`,`branch`,`report_date`,`shift`,`time_covered`,`employee_name`,`supervisor`,`category`,`item_id`,`item_name`,`actual_count`,`units`,`date_created`,`date_updated`,`updated_by`,`posted`,`status`,`from_shift`,`to_shift`,`to_shift_date`)";
			$queryInsert .= "VALUES('$bid','$branch','$report_date','$shift','$time_covered','$employee_name','$supervisor','$category','$item_id','$item_name','$actual_count','$units','$date_created','$date_updated','$updated_by','$posted','$status','$from_shift','$to_shift','$to_shift_date')";
			if ($mainconn->query($queryInsert) === TRUE) { } else { echo "ERROR::: ".$mainconn->error; }
		}
	}	
	/* ################################################## SUPPLIES BAD ORDER ################################################### */
	public function SendSuppliesBOToServer($storebranch,$transdate,$shift,$time_stamp,$table,$rowid,$db,$mainconn)
	{
		$query ="SELECT * FROM $table WHERE branch='$storebranch' AND report_date='$transdate' AND shift='$shift' AND id='$rowid'";
		$result = mysqli_query($db, $query);  
		while($ROW = mysqli_fetch_array($result))  
		{
			$bid = $ROW['id'];$branch = $ROW['branch'];$report_date = $ROW['report_date'];$shift = $ROW['shift'];$time_covered = $ROW['time_covered'];$employee_name = $ROW['employee_name'];
			$supervisor = $ROW['supervisor'];$slip_no = $ROW['slip_no'];$category = $ROW['category'];$item_id = $ROW['item_id'];$item_name = $ROW['item_name'];$actual_count = $ROW['actual_count'];
			$units = $ROW['units'];$total = $ROW['total'];$remarks = $ROW['remarks'];$date_created = $ROW['date_created'];$date_updated = $ROW['date_updated'];
			$updated_by = $ROW['updated_by'];$posted = $ROW['posted'];$status = $ROW['status'];
			
			$update = "
				branch='$branch',report_date='$report_date',shift='$shift',time_covered='$time_covered',employee_name='$employee_name',supervisor='$supervisor',
				slip_no='$slip_no',category='$category',item_id='$item_id',item_name='$item_name',actual_count='$actual_count',units='$units',total='$total',remarks='$remarks',
				date_created='$date_created',date_updated='$date_updated',updated_by='$updated_by',posted='$posted',status='$status'				
			";			
		}		
		$queryRemote ="SELECT * FROM $table WHERE branch='$storebranch' AND report_date='$transdate' AND shift='$shift' AND bid='$bid'";
		$remoteResult = mysqli_query($mainconn, $queryRemote);  
		if($remoteResult->num_rows > 0)
		{
			$queryDataUpdate = "UPDATE $table SET $update WHERE bid='$rowid' AND branch='$storebranch' AND report_date='$transdate' AND shift='$shift'";
			if ($mainconn->query($queryDataUpdate) === TRUE) { } else { echo "ERROR::: ".$mainconn->error; }
		} else {
			$queryInsert = "INSERT INTO $table (`bid`,`branch`,`report_date`,`shift`,`time_covered`,`employee_name`,`supervisor`,`slip_no`,`category`,`item_id`,`item_name`,`actual_count`,`units`,`total`,`remarks`,`date_created`,`date_updated`,`updated_by`,`posted`,`status`)";
			$queryInsert .= "VALUES('$bid','$branch','$report_date','$shift','$time_covered','$employee_name','$supervisor','$slip_no','$category','$item_id','$item_name','$actual_count','$units','$total','$remarks','$date_created','$date_updated','$updated_by','$posted','$status')";
			if ($mainconn->query($queryInsert) === TRUE) { } else { echo "ERROR::: ".$mainconn->error; }
		}
	}
	/* ################################################## SUPPLIES TRANSFER ################################################### */
		public function SendSuppliesTransferToServer($recipient,$transdate,$shift,$time_stamp,$table,$rowid,$db,$mainconn)
	{
		$query ="SELECT * FROM store_supplies_transfer_data WHERE $recipient AND report_date='$transdate' AND shift='$shift' AND id='$rowid'";
		$result = mysqli_query($db, $query);  
		while($ROW = mysqli_fetch_array($result))  
		{
			$bid = $ROW['id'];$tid = $ROW['tid'];$branch = $ROW['branch'];$report_date = $ROW['report_date'];$shift = $ROW['shift'];$time_covered = $ROW['time_covered'];
			$employee_name = $ROW['employee_name'];$supervisor = $ROW['supervisor'];$category = $ROW['category'];$item_id = $ROW['item_id'];$item_name = $ROW['item_name'];
			$weight = $ROW['weight'];$units = $ROW['units'];$transfer_to = $ROW['transfer_to'];$date_created = $ROW['date_created'];$date_updated = $ROW['date_updated'];
			$updated_by = $ROW['updated_by'];$posted = $ROW['posted'];$status = $ROW['status'];
	
			$update = "			
				bid='$bid',branch='$branch',report_date='$report_date',shift='$shift',time_covered='$time_covered',employee_name='$employee_name',
				supervisor='$supervisor',category='$category',item_id='$item_id',item_name='$item_name',weight='$weight',units='$units',transfer_to='$transfer_to',
				date_created='$date_created',date_updated='$date_updated',updated_by='$updated_by',posted='$posted',status='$status'
			";			
		}		
		$queryRemote ="SELECT * FROM store_supplies_transfer_data WHERE $recipient AND report_date='$transdate' AND shift='$shift' AND bid='$bid'";		
		$remoteResult = mysqli_query($mainconn, $queryRemote);  
		if($remoteResult->num_rows > 0)
		{
			$queryDataUpdate = "UPDATE store_supplies_transfer_data SET $update WHERE $recipient AND report_date='$transdate' AND shift='$shift' AND bid='$bid'";			
			if ($mainconn->query($queryDataUpdate) === TRUE) { } else { echo "ERROR::: ".$mainconn->error; }
		} else {
			$queryInsert = "INSERT INTO store_supplies_transfer_data (`bid`,`branch`,`report_date`,`shift`,`time_covered`,`employee_name`,`supervisor`,`category`,`item_id`,`item_name`,`weight`,`units`,`transfer_to`,`date_created`,`date_updated`,`updated_by`,`posted`,`status`)";
			$queryInsert .= "VALUES('$bid','$branch','$report_date','$shift','$time_covered','$employee_name','$supervisor','$category','$item_id','$item_name','$weight','$units','$transfer_to','$date_created','$date_updated','$updated_by','$posted','$status')";
			if ($mainconn->query($queryInsert) === TRUE) { } else { echo "ERRORS::: ".$mainconn->error; }
		}
	}

	/* ################################################## SUPPLIES RECEIVING ################################################### */
	public function SendSuppliesReceivingToServer($storebranch,$transdate,$shift,$time_stamp,$table,$rowid,$db,$mainconn)
	{
		$query ="SELECT * FROM $table WHERE branch='$storebranch' AND report_date='$transdate' AND shift='$shift' AND id='$rowid'";
		$result = mysqli_query($db, $query);  
		while($ROW = mysqli_fetch_array($result))  
		{
			$bid = $ROW['id'];$branch = $ROW['branch'];$report_date = $ROW['report_date'];$shift = $ROW['shift'];$time_covered = $ROW['time_covered'];$employee_name = $ROW['employee_name'];
			$supervisor = $ROW['supervisor'];$remarks = $ROW['remarks'];$category = $ROW['category'];$item_id = $ROW['item_id'];$item_name = $ROW['item_name'];$quantity = $ROW['quantity'];
			$units = $ROW['units'];$supp_prefix = $ROW['supp_prefix'];$supplier = $ROW['supplier'];$invdr_no = $ROW['invdr_no'];$date_created = $ROW['date_created'];
			$date_updated = $ROW['date_updated'];$updated_by = $ROW['updated_by'];$posted = $ROW['posted'];$status = $ROW['status'];$slip_no = $ROW['slip_no'];
			
			$update = "
				branch='$branch',report_date='$report_date',shift='$shift',time_covered='$time_covered',employee_name='$employee_name',supervisor='$supervisor',remarks='$remarks',
				category='$category',item_id='$item_id',item_name='$item_name',quantity='$quantity',units='$units',supp_prefix='$supp_prefix',supplier='$supplier',invdr_no='$invdr_no',
				date_created='$date_created',date_updated='$date_updated',updated_by='$updated_by',posted='$posted',status='$status',slip_no='$slip_no'
			";			
		}		
		$queryRemote ="SELECT * FROM $table WHERE branch='$storebranch' AND report_date='$transdate' AND shift='$shift' AND bid='$bid'";
		$remoteResult = mysqli_query($mainconn, $queryRemote);  
		if($remoteResult->num_rows > 0)
		{
			$queryDataUpdate = "UPDATE $table SET $update WHERE bid='$rowid' AND branch='$storebranch' AND report_date='$transdate' AND shift='$shift'";
			if ($mainconn->query($queryDataUpdate) === TRUE) { } else { echo "ERROR::: ".$mainconn->error; }
		} else {
			$queryInsert = "INSERT INTO $table (`bid`,`branch`,`report_date`,`shift`,`time_covered`,`employee_name`,`supervisor`,`remarks`,`category`,`item_id`,`item_name`,`quantity`,`units`,`supp_prefix`,`supplier`,`invdr_no`,`date_created`,`date_updated`,`updated_by`,`posted`,`status`,`slip_no`)";
			$queryInsert .= "VALUES('$bid','$branch','$report_date','$shift','$time_covered','$employee_name','$supervisor','$remarks','$category','$item_id','$item_name','$quantity','$units','$supp_prefix','$supplier','$invdr_no','$date_created','$date_updated','$updated_by','$posted','$status','$slip_no')";
			if ($mainconn->query($queryInsert) === TRUE) { } else { echo "ERROR::: ".$mainconn->error; }
		}
	}

	/* ################################################## FGTS ################################################### */
	public function SendFGTSToServer($storebranch,$transdate,$shift,$time_stamp,$table,$rowid,$db,$mainconn)
	{
		$query ="SELECT * FROM $table WHERE branch='$storebranch' AND report_date='$transdate' AND shift='$shift' AND id='$rowid'";
		$result = mysqli_query($db, $query);  
		while($ROW = mysqli_fetch_array($result))  
		{
			$id = $ROW['id'];$branch = $ROW['branch'];$report_date = $ROW['report_date'];$shift = $ROW['shift'];$time_covered = $ROW['time_covered'];$employee_name = $ROW['employee_name'];
			$supervisor = $ROW['supervisor'];$inputtime = $ROW['inputtime'];$category = $ROW['category'];$item_id = $ROW['item_id'];$item_name = $ROW['item_name'];
			$standard_yield = $ROW['standard_yield'];$kilo_used = $ROW['kilo_used'];$actual_yield = $ROW['actual_yield'];$unit_price = $ROW['unit_price'];$slip_no = $ROW['slip_no'];
			$date_created = $ROW['date_created'];$date_updated = $ROW['date_updated'];$updated_by = $ROW['updated_by'];$posted = $ROW['posted'];$status = $ROW['status'];			
			$update = "
				branch='$branch',report_date='$report_date',shift='$shift',time_covered='$time_covered',employee_name='$employee_name',supervisor='$supervisor',inputtime='$inputtime',
				category='$category',item_id='$item_id',item_name='$item_name',standard_yield='$standard_yield',kilo_used='$kilo_used',actual_yield='$actual_yield',unit_price='$unit_price',
				slip_no='$slip_no',date_created='$date_created',date_updated='$date_updated',updated_by='$updated_by',posted='$posted',status='$status'
			";			
		}		
		$queryRemote ="SELECT * FROM $table WHERE branch='$storebranch' AND report_date='$transdate' AND shift='$shift' AND bid='$rowid'";
		$remoteResult = mysqli_query($mainconn, $queryRemote);  
		if($remoteResult->num_rows > 0)
		{
			$queryDataUpdate = "UPDATE $table SET $update WHERE bid='$rowid' AND branch='$storebranch' AND report_date='$transdate' AND shift='$shift'";
			if ($mainconn->query($queryDataUpdate) === TRUE) { } else { echo "ERROR::: ".$mainconn->error; }
		} else {
			$queryInsert = "INSERT INTO $table (`bid`,`branch`,`report_date`,`shift`,`time_covered`,`employee_name`,`supervisor`,`inputtime`,`category`,`item_id`,`item_name`,`standard_yield`,`kilo_used`,`actual_yield`,`unit_price`,`slip_no`,`date_created`,`date_updated`,`updated_by`,`posted`,`status`)";
			$queryInsert .= "VALUES('$id','$branch','$report_date','$shift','$time_covered','$employee_name','$supervisor','$inputtime','$category','$item_id','$item_name','$standard_yield','$kilo_used','$actual_yield','$unit_price','$slip_no','$date_created','$date_updated','$updated_by','$posted','$status')";
			if ($mainconn->query($queryInsert) === TRUE) { } else { echo "ERROR::: ".$mainconn->error; }
		}
	}
	/* ################################################## TRANSFER ################################################### */
	public function SendTransferToServer($recipient,$transdate,$shift,$time_stamp,$table,$rowid,$db,$mainconn)
	{
		$query ="SELECT * FROM $table WHERE $recipient AND report_date='$transdate' AND shift='$shift' AND id='$rowid'";
		$result = mysqli_query($db, $query);  
		while($ROW = mysqli_fetch_array($result))  
		{
		
			$bid = $ROW['id'];
			$tid = $ROW['tid'];
			$branch = $ROW['branch'];
			$report_date = $ROW['report_date'];
			$shift = $ROW['shift'];
			$time_covered = $ROW['time_covered'];
			$employee_name = $ROW['employee_name'];
			$supervisor = $ROW['supervisor'];
			$category = $ROW['category'];
			$item_id = $ROW['item_id'];
			$item_name = $ROW['item_name'];
			$quantity = $ROW['quantity'];
			$unit_price = $ROW['unit_price'];
			$amount = $ROW['amount'];
			$transfer_to = $ROW['transfer_to'];
			$date_created = $ROW['date_created'];
			$date_updated = $ROW['date_updated'];
			$updated_by = $ROW['updated_by'];
			$form_no = $ROW['form_no'];
			$posted = $ROW['posted'];
			$status = $ROW['status'];
			
			$update = "
				tid='$tid',branch='$branch',report_date='$report_date',shift='$shift',time_covered='$time_covered',employee_name='$employee_name',supervisor='$supervisor',
				category='$category',item_id='$item_id',item_name='$item_name',quantity='$quantity',unit_price='$unit_price',amount='$amount',transfer_to='$transfer_to',
				date_created='$date_created',date_updated='$date_updated',updated_by='$updated_by',form_no='$form_no',posted='$posted',status='$status'
			";			
		}
		$queryRemote ="SELECT * FROM $table WHERE $recipient AND report_date='$transdate' AND shift='$shift' AND bid='$bid'";
		$remoteResult = mysqli_query($mainconn, $queryRemote);  
		if($remoteResult->num_rows > 0)
		{
			$queryDataUpdate = "UPDATE $table SET $update WHERE $recipient AND report_date='$transdate' AND shift='$shift' AND bid='$bid'";
			if ($mainconn->query($queryDataUpdate) === TRUE) { } else { echo "ERROR::: ".$mainconn->error; }
		} else {
			$queryInsert = "INSERT INTO $table (`bid`,`tid`,`branch`,`report_date`,`shift`,`time_covered`,`employee_name`,`supervisor`,`category`,`item_id`,`item_name`,`quantity`,`unit_price`,`amount`,`transfer_to`,`date_created`,`date_updated`,`updated_by`,`form_no`,`posted`,`status`)";
			$queryInsert .= "VALUES('$bid','$tid','$branch','$report_date','$shift','$time_covered','$employee_name','$supervisor','$category','$item_id','$item_name','$quantity','$unit_price','$amount','$transfer_to','$date_created','$date_updated','$updated_by','$form_no','$posted','$status')";
			if ($mainconn->query($queryInsert) === TRUE) { } else { echo "ERROR::: ".$mainconn->error; }
		}
	}
	
	
	
	
	/* ################################################## CHARGES ################################################### */
	public function SendChargesToServer($storebranch, $transdate, $shift, $time_stamp, $table, $rowid, $db, $mainconn)
	{
		$query = "SELECT * FROM $table WHERE branch='$storebranch' AND report_date='$transdate' AND shift='$shift' AND id='$rowid'";
		$result = mysqli_query($db, $query);  
	
		while ($ROW = mysqli_fetch_array($result))  
		{
			$bid = $ROW['id'];
			$branch = $ROW['branch'];
			$report_date = $ROW['report_date'];
			$shift = $ROW['shift'];
			$time_covered = $ROW['time_covered'];
			$idcode = $ROW['idcode'];
			$employee_name = $ROW['employee_name'];
			$supervisor = $ROW['supervisor'];
			$slip_no = $ROW['slip_no'];
			$category = $ROW['category'];
			$item_id = $ROW['item_id'];
			$item_name = $ROW['item_name'];
			$quantity = $ROW['quantity'];
			$unit_price = $ROW['unit_price'];
	

			$charges_type = $ROW['charges_type'];
			$personal_charges = $ROW['personal_charges'];
			$inventory_charges = $ROW['inventory_charges'];
			$raw_material_charges = $ROW['raw_material_charges'];
			$infraction_charges = $ROW['infraction_charges'];
			$other_charges = $ROW['other_charges'];
	
			$total = $ROW['total'];
			$remarks = $ROW['remarks'];
			$date_created = $ROW['date_created'];
			$date_updated = $ROW['date_updated'];
			$updated_by = $ROW['updated_by'];
			$posted = $ROW['posted'];
			$status = $ROW['status'];
	

			$update = "
				branch='$branch',
				report_date='$report_date',
				shift='$shift',
				time_covered='$time_covered',
				idcode='$idcode',
				employee_name='$employee_name',
				supervisor='$supervisor',
				slip_no='$slip_no',
				category='$category',
				item_id='$item_id',
				item_name='$item_name',
				quantity='$quantity',
				unit_price='$unit_price',
				charges_type='$charges_type',
				personal_charges='$personal_charges',
				inventory_charges='$inventory_charges',
				raw_material_charges='$raw_material_charges',
				infraction_charges='$infraction_charges',
				other_charges='$other_charges',
				total='$total',
				remarks='$remarks',
				date_created='$date_created',
				date_updated='$date_updated',
				updated_by='$updated_by',
				posted='$posted',
				status='$status'
			";			
		}		
	

		$queryRemote = "SELECT * FROM $table WHERE branch='$storebranch' AND report_date='$transdate' AND shift='$shift' AND bid='$bid'";
		$remoteResult = mysqli_query($mainconn, $queryRemote);  
	
		if ($remoteResult->num_rows > 0) {

			$queryDataUpdate = "UPDATE $table SET $update WHERE bid='$rowid' AND branch='$storebranch' AND report_date='$transdate' AND shift='$shift'";
			if ($mainconn->query($queryDataUpdate) === TRUE) {

			} else {
				echo "ERROR::: " . $mainconn->error;
			}
		} else {

			$queryInsert = "INSERT INTO $table (`bid`,`branch`,`report_date`,`shift`,`time_covered`,`idcode`,`employee_name`,`supervisor`,`slip_no`,`category`,`item_id`,`item_name`,`quantity`,`unit_price`,
			`charges_type`, `personal_charges`, `inventory_charges`, `raw_material_charges`, `infraction_charges`, `other_charges`,
			`total`,`remarks`,`date_created`,`date_updated`,`updated_by`,`posted`,`status`)
			VALUES (
				'$bid','$branch','$report_date','$shift','$time_covered','$idcode','$employee_name','$supervisor','$slip_no','$category','$item_id','$item_name','$quantity','$unit_price',
				'$charges_type','$personal_charges','$inventory_charges','$raw_material_charges','$infraction_charges','$other_charges',
				'$total','$remarks','$date_created','$date_updated','$updated_by','$posted','$status'
			)";
			if ($mainconn->query($queryInsert) === TRUE) {

			} else {
				echo "ERROR::: " . $mainconn->error;
			}
		}
	}



	
	
	
	
	/* ################################################## SNACKS ################################################### */
	public function SendSnacksToServer($storebranch,$transdate,$shift,$time_stamp,$table,$rowid,$db,$mainconn)
	{
		$query ="SELECT * FROM $table WHERE branch='$storebranch' AND report_date='$transdate' AND shift='$shift' AND id='$rowid'";
		$result = mysqli_query($db, $query);  
		while($ROW = mysqli_fetch_array($result))  
		{
			$bid = $ROW['id'];$branch = $ROW['branch'];$report_date = $ROW['report_date'];$shift = $ROW['shift'];$time_covered = $ROW['time_covered'];
			$employee_name = $ROW['employee_name'];$supervisor = $ROW['supervisor'];$slip_no = $ROW['slip_no'];$category = $ROW['category'];$item_id = $ROW['item_id'];
			$item_name = $ROW['item_name'];$quantity = $ROW['quantity'];$unit_price = $ROW['unit_price'];$total = $ROW['total'];$remarks = $ROW['remarks'];
			$date_created = $ROW['date_created'];$date_updated = $ROW['date_updated'];$updated_by = $ROW['updated_by'];$posted = $ROW['posted'];$status = $ROW['status'];			

			$update = "
				branch='$branch',report_date='$report_date',shift='$shift',time_covered='$time_covered',employee_name='$employee_name',supervisor='$supervisor',
				slip_no='$slip_no',category='$category',item_id='$item_id',item_name='$item_name',quantity='$quantity',unit_price='$unit_price',total='$total',
				remarks='$remarks',date_created='$date_created',date_updated='$date_updated',updated_by='$updated_by',posted='$posted',status='$status'
			";			
		}		
		$queryRemote ="SELECT * FROM $table WHERE branch='$storebranch' AND report_date='$transdate' AND shift='$shift' AND bid='$bid'";
		$remoteResult = mysqli_query($mainconn, $queryRemote);  
		if($remoteResult->num_rows > 0)
		{
			$queryDataUpdate = "UPDATE $table SET $update WHERE bid='$rowid' AND branch='$storebranch' AND report_date='$transdate' AND shift='$shift'";
			if ($mainconn->query($queryDataUpdate) === TRUE) { } else { echo "ERROR::: ".$mainconn->error; }
		} else {
			$queryInsert = "INSERT INTO $table (`bid`,`branch`,`report_date`,`shift`,`time_covered`,`employee_name`,`supervisor`,`slip_no`,`category`,`item_id`,`item_name`,`quantity`,`unit_price`,`total`,`remarks`,`date_created`,`date_updated`,`updated_by`,`posted`,`status`)";
			$queryInsert .= "VALUES('$bid','$branch','$report_date','$shift','$time_covered','$employee_name','$supervisor','$slip_no','$category','$item_id','$item_name','$quantity','$unit_price','$total','$remarks','$date_created','$date_updated','$updated_by','$posted','$status')";
			if ($mainconn->query($queryInsert) === TRUE) { } else { echo "ERROR::: ".$mainconn->error; }
		}
	}
	/* ################################################## BAD ORDER ################################################### */
	public function SendBOToServer($storebranch,$transdate,$shift,$time_stamp,$table,$rowid,$db,$mainconn)
	{
		$query ="SELECT * FROM $table WHERE branch='$storebranch' AND report_date='$transdate' AND shift='$shift' AND id='$rowid'";
		$result = mysqli_query($db, $query);  
		while($ROW = mysqli_fetch_array($result))  
		{
			$bid = $ROW['id'];$branch = $ROW['branch'];$report_date = $ROW['report_date'];$shift = $ROW['shift'];$time_covered = $ROW['time_covered'];$employee_name = $ROW['employee_name'];
			$supervisor = $ROW['supervisor'];$slip_no = $ROW['slip_no'];$category = $ROW['category'];$item_id = $ROW['item_id'];$item_name = $ROW['item_name'];$quantity = $ROW['quantity'];
			$unit_price = $ROW['unit_price'];$total = $ROW['total'];$remarks = $ROW['remarks'];$date_created = $ROW['date_created'];$date_updated = $ROW['date_updated'];
			$updated_by = $ROW['updated_by'];$posted = $ROW['posted'];$status = $ROW['status'];
			
			$update = "
				branch='$branch',report_date='$report_date',shift='$shift',time_covered='$time_covered',employee_name='$employee_name',supervisor='$supervisor',
				slip_no='$slip_no',category='$category',item_id='$item_id',item_name='$item_name',quantity='$quantity',unit_price='$unit_price',total='$total',remarks='$remarks',
				date_created='$date_created',date_updated='$date_updated',updated_by='$updated_by',posted='$posted',status='$status'				
			";			
		}		
		$queryRemote ="SELECT * FROM $table WHERE branch='$storebranch' AND report_date='$transdate' AND shift='$shift' AND bid='$bid'";
		$remoteResult = mysqli_query($mainconn, $queryRemote);  
		if($remoteResult->num_rows > 0)
		{
			$queryDataUpdate = "UPDATE $table SET $update WHERE bid='$rowid' AND branch='$storebranch' AND report_date='$transdate' AND shift='$shift'";
			if ($mainconn->query($queryDataUpdate) === TRUE) { } else { echo "ERROR::: ".$mainconn->error; }
		} else {
			$queryInsert = "INSERT INTO $table (`bid`,`branch`,`report_date`,`shift`,`time_covered`,`employee_name`,`supervisor`,`slip_no`,`category`,`item_id`,`item_name`,`quantity`,`unit_price`,`total`,`remarks`,`date_created`,`date_updated`,`updated_by`,`posted`,`status`)";
			$queryInsert .= "VALUES('$bid','$branch','$report_date','$shift','$time_covered','$employee_name','$supervisor','$slip_no','$category','$item_id','$item_name','$quantity','$unit_price','$total','$remarks','$date_created','$date_updated','$updated_by','$posted','$status')";
			if ($mainconn->query($queryInsert) === TRUE) { } else { echo "ERROR::: ".$mainconn->error; }
		}
	}
	/* ################################################## DAMAGE ################################################### */
	public function SendDamageToServer($storebranch,$transdate,$shift,$time_stamp,$table,$rowid,$db,$mainconn)
	{
		$query ="SELECT * FROM $table WHERE branch='$storebranch' AND report_date='$transdate' AND shift='$shift' AND id='$rowid'";
		$result = mysqli_query($db, $query);  
		while($ROW = mysqli_fetch_array($result))  
		{
			$bid = $ROW['id'];$branch = $ROW['branch'];$report_date = $ROW['report_date'];$shift = $ROW['shift'];$time_covered = $ROW['time_covered'];
			$employee_name = $ROW['employee_name'];$supervisor = $ROW['supervisor'];$slip_no = $ROW['slip_no'];$category = $ROW['category'];$item_id = $ROW['item_id'];
			$item_name = $ROW['item_name'];$quantity = $ROW['quantity'];$unit_price = $ROW['unit_price'];$amount = $ROW['amount'];$date_created = $ROW['date_created'];
			$date_updated = $ROW['date_updated'];$updated_by = $ROW['updated_by'];$posted = $ROW['posted'];$status = $ROW['status'];			

			$update = "
				branch='$branch',report_date='$report_date',shift='$shift',time_covered='$time_covered',employee_name='$employee_name',supervisor='$supervisor',slip_no='$slip_no',category='$category',item_id='$item_id',
				item_name='$item_name',quantity='$quantity',unit_price='$unit_price',amount='$amount',date_created='$date_created',date_updated='$date_updated',updated_by='$updated_by',posted='$posted',status='$status'				
			";			
		}		
		$queryRemote ="SELECT * FROM $table WHERE branch='$storebranch' AND report_date='$transdate' AND shift='$shift' AND bid='$bid'";
		$remoteResult = mysqli_query($mainconn, $queryRemote);  
		if($remoteResult->num_rows > 0)
		{
			$queryDataUpdate = "UPDATE $table SET $update WHERE bid='$rowid' AND branch='$storebranch' AND report_date='$transdate' AND shift='$shift'";
			if ($mainconn->query($queryDataUpdate) === TRUE) { } else { echo "ERROR::: ".$mainconn->error; }
		} else {
			$queryInsert = "INSERT INTO $table (`bid`,`branch`,`report_date`,`shift`,`time_covered`,`employee_name`,`supervisor`,`slip_no`,`category`,`item_id`,`item_name`,`quantity`,`unit_price`,`amount`,`date_created`,`date_updated`,`updated_by`,`posted`,`status`)";
			$queryInsert .= "VALUES('$bid','$branch','$report_date','$shift','$time_covered','$employee_name','$supervisor','$slip_no','$category','$item_id','$item_name','$quantity','$unit_price','$amount','$date_created','$date_updated','$updated_by','$posted','$status')";
			if ($mainconn->query($queryInsert) === TRUE) { } else { echo "ERROR::: ".$mainconn->error; }
		}
	}	
	/* ################################################## COMPLIMENTARY ################################################### */
	public function SendComplimentaryToServer($storebranch,$transdate,$shift,$time_stamp,$table,$rowid,$db,$mainconn)
	{
		$query ="SELECT * FROM $table WHERE branch='$storebranch' AND report_date='$transdate' AND shift='$shift' AND id='$rowid'";
		$result = mysqli_query($db, $query);  
		while($ROW = mysqli_fetch_array($result))  
		{
			$bid = $ROW['id'];$branch = $ROW['branch'];$report_date = $ROW['report_date'];$shift = $ROW['shift'];$time_covered = $ROW['time_covered'];
			$employee_name = $ROW['employee_name'];$supervisor = $ROW['supervisor'];$slip_no = $ROW['slip_no'];$category = $ROW['category'];$item_id = $ROW['item_id'];
			$item_name = $ROW['item_name'];$quantity = $ROW['quantity'];$unit_price = $ROW['unit_price'];$amount = $ROW['amount'];$remarks = $ROW['remarks'];
			$date_created = $ROW['date_created'];$date_updated = $ROW['date_updated'];$updated_by = $ROW['updated_by'];$posted = $ROW['posted'];$status = $ROW['status'];
			
			$update = "
				branch='$branch',report_date='$report_date',shift='$shift',time_covered='$time_covered',employee_name='$employee_name',supervisor='$supervisor',
				slip_no='$slip_no',category='$category',item_id='$item_id',item_name='$item_name',quantity='$quantity',unit_price='$unit_price',amount='$amount',
				remarks='$remarks',date_created='$date_created',date_updated='$date_updated',updated_by='$updated_by',posted='$posted',status='$status'
			";			
		}		
		$queryRemote ="SELECT * FROM $table WHERE branch='$storebranch' AND report_date='$transdate' AND shift='$shift' AND bid='$bid'";
		$remoteResult = mysqli_query($mainconn, $queryRemote);  
		if($remoteResult->num_rows > 0)
		{
			$queryDataUpdate = "UPDATE $table SET $update WHERE bid='$rowid' AND branch='$storebranch' AND report_date='$transdate' AND shift='$shift'";
			if ($mainconn->query($queryDataUpdate) === TRUE) { } else { echo "ERROR::: ".$mainconn->error; }
		} else {
			$queryInsert = "INSERT INTO $table (`bid`,`branch`,`report_date`,`shift`,`time_covered`,`employee_name`,`supervisor`,`slip_no`,`category`,`item_id`,`item_name`,`quantity`,`unit_price`,`amount`,`remarks`,`date_created`,`date_updated`,`updated_by`,`posted`,`status`)";
			$queryInsert .= "VALUES('$bid','$branch','$report_date','$shift','$time_covered','$employee_name','$supervisor','$slip_no','$category','$item_id','$item_name','$quantity','$unit_price','$amount','$remarks','$date_created','$date_updated','$updated_by','$posted','$status')";
			if ($mainconn->query($queryInsert) === TRUE) { } else { echo "ERROR::: ".$mainconn->error; }
		}
	}
	/* ################################################## RECEIVING ################################################### */
	public function SendReceivingToServer($storebranch,$transdate,$shift,$time_stamp,$table,$rowid,$db,$mainconn)
	{
		$query ="SELECT * FROM $table WHERE branch='$storebranch' AND report_date='$transdate' AND shift='$shift' AND id='$rowid'";
		$result = mysqli_query($db, $query);  
		while($ROW = mysqli_fetch_array($result))  
		{
			$bid = $ROW['id'];$branch = $ROW['branch'];$report_date = $ROW['report_date'];$shift = $ROW['shift'];$time_covered = $ROW['time_covered'];$employee_name = $ROW['employee_name'];
			$supervisor = $ROW['supervisor'];$remarks = $ROW['remarks'];$category = $ROW['category'];$item_id = $ROW['item_id'];$item_name = $ROW['item_name'];$quantity = $ROW['quantity'];
			$units = $ROW['units'];$supp_prefix = $ROW['supp_prefix'];$supplier = $ROW['supplier'];$invdr_no = $ROW['invdr_no'];$date_created = $ROW['date_created'];
			$date_updated = $ROW['date_updated'];$updated_by = $ROW['updated_by'];$posted = $ROW['posted'];$status = $ROW['status'];$slip_no = $ROW['slip_no'];
			
			$update = "
				branch='$branch',report_date='$report_date',shift='$shift',time_covered='$time_covered',employee_name='$employee_name',supervisor='$supervisor',remarks='$remarks',
				category='$category',item_id='$item_id',item_name='$item_name',quantity='$quantity',units='$units',supp_prefix='$supp_prefix',supplier='$supplier',invdr_no='$invdr_no',
				date_created='$date_created',date_updated='$date_updated',updated_by='$updated_by',posted='$posted',status='$status',slip_no='$slip_no'
			";			
		}		
		$queryRemote ="SELECT * FROM $table WHERE branch='$storebranch' AND report_date='$transdate' AND shift='$shift' AND bid='$bid'";
		$remoteResult = mysqli_query($mainconn, $queryRemote);  
		if($remoteResult->num_rows > 0)
		{
			$queryDataUpdate = "UPDATE $table SET $update WHERE bid='$rowid' AND branch='$storebranch' AND report_date='$transdate' AND shift='$shift'";
			if ($mainconn->query($queryDataUpdate) === TRUE) { } else { echo "ERROR::: ".$mainconn->error; }
		} else {
			$queryInsert = "INSERT INTO $table (`bid`,`branch`,`report_date`,`shift`,`time_covered`,`employee_name`,`supervisor`,`remarks`,`category`,`item_id`,`item_name`,`quantity`,`units`,`supp_prefix`,`supplier`,`invdr_no`,`date_created`,`date_updated`,`updated_by`,`posted`,`status`,`slip_no`)";
			$queryInsert .= "VALUES('$bid','$branch','$report_date','$shift','$time_covered','$employee_name','$supervisor','$remarks','$category','$item_id','$item_name','$quantity','$units','$supp_prefix','$supplier','$invdr_no','$date_created','$date_updated','$updated_by','$posted','$status','$slip_no')";
			if ($mainconn->query($queryInsert) === TRUE) { } else { echo "ERROR::: ".$mainconn->error; }
		}
	}
	/* ################################################## CASH COUNT ################################################### */
	public function SendCashCountToServer($storebranch,$transdate,$shift,$time_stamp,$table,$rowid,$db,$mainconn)
	{
		$query ="SELECT * FROM $table WHERE branch='$storebranch' AND report_date='$transdate' AND shift='$shift' AND id='$rowid'";
		$result = mysqli_query($db, $query);  
		while($ROW = mysqli_fetch_array($result))  
		{
			$bid = $ROW['id'];$branch = $ROW['branch'];$report_date = $ROW['report_date'];$shift = $ROW['shift'];$time_covered = $ROW['time_covered'];$employee_name = $ROW['employee_name'];
			$supervisor = $ROW['supervisor'];$slip_no = $ROW['slip_no'];$denomination = $ROW['denomination'];$quantity = $ROW['quantity'];$total_amount = $ROW['total_amount'];
			$date_created = $ROW['date_created'];$updated_by = $ROW['updated_by'];$date_updated = $ROW['date_updated'];$audit_mode = $ROW['audit_mode'];$posted = $ROW['posted'];$status = $ROW['status'];
			
			$update = "
				branch='$branch',report_date='$report_date',shift='$shift',time_covered='$time_covered',employee_name='$employee_name',supervisor='$supervisor',
				slip_no='$slip_no',denomination='$denomination',quantity='$quantity',total_amount='$total_amount',date_created='$date_created',
				updated_by='$updated_by',date_updated='$date_updated',audit_mode='$audit_mode',posted='$posted',status='$status'
			";			
		}		
		$queryRemote ="SELECT * FROM $table WHERE branch='$storebranch' AND report_date='$transdate' AND shift='$shift' AND bid='$bid'";
		$remoteResult = mysqli_query($mainconn, $queryRemote);  
		if($remoteResult->num_rows > 0)
		{
			$queryDataUpdate = "UPDATE $table SET $update WHERE bid='$rowid' AND branch='$storebranch' AND report_date='$transdate' AND shift='$shift'";
			if ($mainconn->query($queryDataUpdate) === TRUE) { } else { echo "ERROR::: ".$mainconn->error; }
		} else {
			$queryInsert = "INSERT INTO $table (`bid`,`branch`,`report_date`,`shift`,`time_covered`,`employee_name`,`supervisor`,`slip_no`,`denomination`,`quantity`,`total_amount`,`date_created`,`updated_by`,`date_updated`,`audit_mode`,`posted`,`status`)";
			$queryInsert .= "VALUES('$bid','$branch','$report_date','$shift','$time_covered','$employee_name','$supervisor','$slip_no','$denomination','$quantity','$total_amount','$date_created','$updated_by','$date_updated','$audit_mode','$posted','$status')";
			if ($mainconn->query($queryInsert) === TRUE) { } else { echo "ERROR::: ".$mainconn->error; }
		}
	}
	/* ################################################## FROZEN DOUGH ################################################### */
	public function SendFrozenDoughToServer($storebranch,$transdate,$shift,$time_stamp,$table,$rowid,$db,$mainconn)
	{
		$query ="SELECT * FROM $table WHERE branch='$storebranch' AND report_date='$transdate' AND shift='$shift' AND id='$rowid'";
		$result = mysqli_query($db, $query);  
		while($ROW = mysqli_fetch_array($result))  
		{
			$bid = $ROW['id'];$branch = $ROW['branch'];$report_date = $ROW['report_date'];$shift = $ROW['shift'];$time_covered = $ROW['time_covered'];
			$employee_name = $ROW['employee_name'];$supervisor = $ROW['supervisor'];$inputtime = $ROW['inputtime'];$category = $ROW['category'];
			$item_id = $ROW['item_id'];$item_name = $ROW['item_name'];$standard_yield = $ROW['standard_yield'];$kilo_used = $ROW['kilo_used'];
			$actual_yield = $ROW['actual_yield'];$unit_price = $ROW['unit_price'];$slip_no = $ROW['slip_no'];$date_created = $ROW['date_created'];
			$date_updated = $ROW['date_updated'];$updated_by = $ROW['updated_by'];$posted = $ROW['posted'];$status = $ROW['status'];
						
			$update = "
				branch='$branch',report_date='$report_date',shift='$shift',time_covered='$time_covered',employee_name='$employee_name',supervisor='$supervisor',inputtime='$inputtime',
				category='$category',item_id='$item_id',item_name='$item_name',standard_yield='$standard_yield',kilo_used='$kilo_used',actual_yield='$actual_yield',unit_price='$unit_price',
				slip_no='$slip_no',date_created='$date_created',date_updated='$date_updated',updated_by='$updated_by',posted='$posted',status='$status'
			";			
		}		
		$queryRemote ="SELECT * FROM $table WHERE branch='$storebranch' AND report_date='$transdate' AND shift='$shift' AND bid='$bid'";
		$remoteResult = mysqli_query($mainconn, $queryRemote);  
		if($remoteResult->num_rows > 0)
		{
			$queryDataUpdate = "UPDATE $table SET $update WHERE bid='$rowid' AND branch='$storebranch' AND report_date='$transdate' AND shift='$shift'";
			if ($mainconn->query($queryDataUpdate) === TRUE) { } else { echo "ERROR::: ".$mainconn->error; }
		} else {
			$queryInsert = "INSERT INTO $table (`bid`,`branch`,`report_date`,`shift`,`time_covered`,`employee_name`,`supervisor`,`inputtime`,`category`,`item_id`,`item_name`,`standard_yield`,`kilo_used`,`actual_yield`,`unit_price`,`slip_no`,`date_created`,`date_updated`,`updated_by`,`posted`,`status`)";
			$queryInsert .= "VALUES('$bid','$branch','$report_date','$shift','$time_covered','$employee_name','$supervisor','$inputtime','$category','$item_id','$item_name','$standard_yield','$kilo_used','$actual_yield','$unit_price','$slip_no','$date_created','$date_updated','$updated_by','$posted','$status')";
			if ($mainconn->query($queryInsert) === TRUE) { } else { echo "ERROR::: ".$mainconn->error; }
		}
	}	
	/* ################################################## PHYSICAL COUNT ################################################### */
	public function SendPCountToServer($storebranch,$transdate,$shift,$time_stamp,$table,$rowid,$db,$mainconn)
	{
		$query ="SELECT * FROM $table WHERE branch='$storebranch' AND report_date='$transdate' AND shift='$shift' AND id='$rowid'";
		$result = mysqli_query($db, $query);  
		while($ROW = mysqli_fetch_array($result))  
		{
			$bid = $ROW['id'];$branch = $ROW['branch'];$report_date = $ROW['report_date'];$shift = $ROW['shift'];$time_covered = $ROW['time_covered'];
			$employee_name = $ROW['employee_name'];$supervisor = $ROW['supervisor'];$category = $ROW['category'];$item_id = $ROW['item_id'];
			$item_name = $ROW['item_name'];$actual_count = $ROW['actual_count'];$date_created = $ROW['date_created'];$date_updated = $ROW['date_updated'];
			$updated_by = $ROW['updated_by'];$posted = $ROW['posted'];$status = $ROW['status'];$from_shift = $ROW['from_shift'];$to_shift = $ROW['to_shift'];
			$to_shift_date = $ROW['to_shift_date'];
						
			$update = "
				branch='$branch',report_date='$report_date',shift='$shift',time_covered='$time_covered',employee_name='$employee_name',supervisor='$supervisor',category='$category',
				item_id='$item_id',item_name='$item_name',actual_count='$actual_count',date_created='$date_created',date_updated='$date_updated',updated_by='$updated_by',posted='$posted',
				status='$status',from_shift='$from_shift',to_shift='$to_shift',to_shift_date='$to_shift_date'
			";			
		}		
		$queryRemote ="SELECT * FROM $table WHERE branch='$storebranch' AND report_date='$transdate' AND shift='$shift' AND bid='$bid'";
		$remoteResult = mysqli_query($mainconn, $queryRemote);  
		if($remoteResult->num_rows > 0)
		{
			$queryDataUpdate = "UPDATE $table SET $update WHERE bid='$rowid' AND branch='$storebranch' AND report_date='$transdate' AND shift='$shift'";
			if ($mainconn->query($queryDataUpdate) === TRUE) { } else { echo "ERROR::: ".$mainconn->error; }
		} else {
			$queryInsert = "INSERT INTO $table (`bid`,`branch`,`report_date`,`shift`,`time_covered`,`employee_name`,`supervisor`,`category`,`item_id`,`item_name`,`actual_count`,`date_created`,`date_updated`,`updated_by`,`posted`,`status`,`from_shift`,`to_shift`,`to_shift_date`)";
			$queryInsert .= "VALUES('$bid','$branch','$report_date','$shift','$time_covered','$employee_name','$supervisor','$category','$item_id','$item_name','$actual_count','$date_created','$date_updated','$updated_by','$posted','$status','$from_shift','$to_shift','$to_shift_date')";
			if ($mainconn->query($queryInsert) === TRUE) { } else { echo "ERROR::: ".$mainconn->error; }
		}
	}
	/* ################################################## DISCOUNT ################################################### */
	public function SendDiscountToServer($storebranch,$transdate,$shift,$time_stamp,$table,$rowid,$db,$mainconn)
	{
		$query ="SELECT * FROM $table WHERE branch='$storebranch' AND report_date='$transdate' AND shift='$shift' AND id='$rowid'";
		$result = mysqli_query($db, $query);  
		while($ROW = mysqli_fetch_array($result))  
		{
			$bid = $ROW['id'];$branch = $ROW['branch'];$report_date = $ROW['report_date'];$shift = $ROW['shift'];$discount_type = $ROW['discount_type'];
			$category = $ROW['category'];$item_name = $ROW['item_name'];$supervisor = $ROW['supervisor'];$discount = $ROW['discount'];
			$date_created = $ROW['date_created'];$date_updated = $ROW['date_updated'];$updated_by = $ROW['updated_by'];$posted = $ROW['posted'];$status = $ROW['status'];
			
					
			$update = "
				branch='$branch',report_date='$report_date',shift='$shift',supervisor='$supervisor',discount='$discount',
				date_created='$date_created',date_updated='$date_updated',updated_by='$updated_by',posted='$posted',status='$status'
			";			
		}		
		$queryRemote ="SELECT * FROM $table WHERE branch='$storebranch' AND report_date='$transdate' AND shift='$shift' AND bid='$bid'";
		$remoteResult = mysqli_query($mainconn, $queryRemote);  
		if($remoteResult->num_rows > 0)
		{
			$queryDataUpdate = "UPDATE $table SET $update WHERE bid='$rowid' AND branch='$storebranch' AND report_date='$transdate' AND shift='$shift'";
			if ($mainconn->query($queryDataUpdate) === TRUE) { } else { echo "ERROR::: ".$mainconn->error; }
		} else {
			$queryInsert = "INSERT INTO $table (`bid`,`branch`,`report_date`,`shift`,`discount_type`,`category`,`item_name`,`supervisor`,`discount`,`date_created`,`date_updated`,`updated_by`,`posted`,`status`)";
			$queryInsert .= "VALUES('$bid','$branch','$report_date','$shift','$discount_type','$category','$item_name','$supervisor','$discount','$date_created','$date_updated','$updated_by','$posted','$status')";
			if ($mainconn->query($queryInsert) === TRUE) { } else { echo "ERROR::: ".$mainconn->error; }
		}
	}
	/* ################################################################################################################# */
	/* ################################################################################################################# */
	/* ################################################################################################################# */
	/* ################################################## PRODUCTION ################################################### */
	public function SendProductionToServer($storebranch,$monthnum,$table,$rowid,$db,$mainconn)
	{
		$query ="SELECT * FROM store_production_data WHERE branch='$storebranch' AND month='$monthnum' AND id='$rowid'";
		$result = mysqli_query($db, $query);  
		while($ROW = mysqli_fetch_array($result))  
		{
			$bid = $ROW['id'];$branch = $ROW['branch'];$year = $ROW['year'];$month = $ROW['month'];$item_id = $ROW['item_id'];$item_name = $ROW['item_name'];$a = $ROW['01'];
			$b = $ROW['02'];$c = $ROW['03'];$d = $ROW['04'];$e = $ROW['05'];$f = $ROW['06'];$g = $ROW['07'];$h = $ROW['08'];$i = $ROW['09'];$j = $ROW['10'];$k = $ROW['11'];$l = $ROW['12'];
			$m = $ROW['13'];$n = $ROW['14'];$o = $ROW['15'];$p = $ROW['16'];$q = $ROW['17'];$r = $ROW['18'];$s = $ROW['19'];$t = $ROW['20'];$u = $ROW['21'];$v = $ROW['22'];
			$w = $ROW['23'];$x = $ROW['24'];$y = $ROW['25'];$z = $ROW['26'];$aa = $ROW['27'];$bb = $ROW['28'];$cc = $ROW['29'];$dd = $ROW['30'];$ee = $ROW['31'];$total_count = $ROW['total_count'];

					
			$update = "
				branch='$branch',year='$year',month='$month',item_id='$item_id',item_name='$item_name',`01`='$a',
				`02`='$b',`03`='$c',`04`='$d',`05`='$e',`06`='$f',`07`='$g',`08`='$h',`09`='$i',`10`='$j',`11`='$k',`12`='$l',`13`='$m',
				`14`='$n',`15`='$o',`16`='$p',`17`='$q',`18`='$r',`19`='$s',`20`='$t',`21`='$u',`22`='$v',`23`='$w',`24`='$x',
				`25`='$y',`26`='$z',`27`='$aa',`28`='$bb',`29`='$cc',`30`='$dd',`31`='$ee',total_count='$total_count'
			";			
		}		
		$queryRemote ="SELECT * FROM $table WHERE branch='$storebranch' AND month='$monthnum' AND bid='$bid'";
		$remoteResult = mysqli_query($mainconn, $queryRemote);  
		if($remoteResult->num_rows > 0)
		{
			$queryDataUpdate = "UPDATE $table SET $update WHERE bid='$rowid'  AND month='$monthnum' AND bid='$bid'";
			if ($mainconn->query($queryDataUpdate) === TRUE) { } else { echo "ERROR::: ".$mainconn->error; }
		} else {
			$queryInsert = "INSERT INTO $table (`bid`,`branch`,`year`,`month`,`item_id`,`item_name`,`01`,`02`,`03`,`04`,`05`,`06`,`07`,`08`,`09`,`10`,`11`,`12`,`13`,`14`,`15`,`16`,`17`,`18`,`19`,`20`,`21`,`22`,`23`,`24`,`25`,`26`,`27`,`28`,`29`,`30`,`31`,`total_count`)";
			$queryInsert .= "VALUES('$bid','$branch','$year','$month','$item_id','$item_name','$a','$b','$c','$d','$e','$f','$g','$h','$i','$j','$k','$l','$m','$n','$o','$p','$q','$r','$s','$t','$u','$v','$w','$x','$y','$z','$aa','$bb','$cc','$dd','$ee','$total_count')";
			if ($mainconn->query($queryInsert) === TRUE) { } else { echo "ERROR::: ".$mainconn->error; }
		}
	}
	/* ################################################## SUMMARY ################################################### */
	public function SendSummaryToServer($storebranch,$transdate,$shift,$time_stamp,$table,$rowid,$db,$mainconn)
	{
		$query ="SELECT * FROM $table WHERE branch='$storebranch' AND report_date='$transdate' AND shift='$shift' AND id='$rowid'";
		$result = mysqli_query($db, $query);  
		while($ROW = mysqli_fetch_array($result))  
		{
			$bid = $ROW['id'];$item_id = $ROW['item_id'];$branch = $ROW['branch'];$report_date = $ROW['report_date'];$shift = $ROW['shift'];$time_covered = $ROW['time_covered'];	$slip_number = $ROW['slip_number'];
			$supervisor = $ROW['supervisor'];$inputtime = $ROW['inputtime'];$category = $ROW['category'];$item_name = $ROW['item_name'];$kilo_used = $ROW['kilo_used'];$standard_yield = $ROW['standard_yield'];
			$actual_yield = $ROW['actual_yield'];$beginning = $ROW['beginning'];$stock_in = $ROW['stock_in'];$t_in = $ROW['t_in'];	$frozendough = $ROW['frozendough'];$total = $ROW['total'];$t_out = $ROW['t_out'];
			$charges = $ROW['charges'];$snacks = $ROW['snacks'];$bo = $ROW['bo'];$damaged = $ROW['damaged'];$complimentary = $ROW['complimentary'];$should_be = $ROW['should_be'];	$actual_count = $ROW['actual_count'];
			$sold = $ROW['sold'];$unit_price = $ROW['unit_price'];$amount = $ROW['amount'];$date_created = $ROW['date_created'];$posted = $ROW['posted'];$status = $ROW['status'];	$form_no = $ROW['form_no'];
					
			$update = "
				bid='$bid',item_id='$item_id',branch='$branch',report_date='$report_date',shift='$shift',time_covered='$time_covered',slip_number='$slip_number',supervisor='$supervisor',inputtime='$inputtime',
				category='$category',item_name='$item_name',kilo_used='$kilo_used',standard_yield='$standard_yield',actual_yield='$actual_yield',beginning='$beginning',stock_in='$stock_in',t_in='$t_in',
				frozendough='$frozendough',total='$total',t_out='$t_out',charges='$charges',snacks='$snacks',bo='$bo',damaged='$damaged',	complimentary='$complimentary',should_be='$should_be',	actual_count='$actual_count',
				sold='$sold',unit_price='$unit_price',amount='$amount',date_created='$date_created',posted='$posted',status='$status',form_no='$form_no'
			";			
		}		
		$queryRemote ="SELECT * FROM $table WHERE branch='$storebranch' AND report_date='$transdate' AND shift='$shift' AND bid='$bid'";
		$remoteResult = mysqli_query($mainconn, $queryRemote);  
		if($remoteResult->num_rows > 0)
		{
			$queryDataUpdate = "UPDATE $table SET $update WHERE bid='$rowid' AND branch='$storebranch' AND report_date='$transdate' AND shift='$shift'";
			if ($mainconn->query($queryDataUpdate) === TRUE) { } else { echo "ERROR::: ".$mainconn->error; }
		} else {
			$queryInsert = "INSERT INTO $table (`bid`,`item_id`,`branch`,`report_date`,`shift`,`time_covered`,`slip_number`,`supervisor`,`inputtime`,`category`,`item_name`,`kilo_used`,`standard_yield`,`actual_yield`,`beginning`,`stock_in`,`t_in`,`frozendough`,`total`,`t_out`,`charges`,`snacks`,`bo`,`damaged`,`complimentary`,`should_be`,`actual_count`,`sold`,`unit_price`,`amount`,`date_created`,`posted`,`status`,`form_no`)";
			$queryInsert .= "VALUES('$bid','$item_id','$branch','$report_date','$shift','$time_covered','$slip_number','$supervisor','$inputtime','$category','$item_name','$kilo_used','$standard_yield','$actual_yield','$beginning','$stock_in','$t_in','$frozendough','$total','$t_out','$charges','$snacks','$bo','$damaged','$complimentary','$should_be','$actual_count','$sold','$unit_price','$amount','$date_created','$posted','$status','$form_no')";
			if ($mainconn->query($queryInsert) === TRUE) { } else { echo "ERROR::: ".$mainconn->error; }
		}
	}
	/* ######################################################################################################################## */
	/* ######################################################################################################################## */
	/* ######################################################################################################################## */
	/* ################################################## RAWMATS RECEIVING ################################################### */
	public function SendRmReceivingToServer($storebranch,$transdate,$shift,$time_stamp,$table,$rowid,$db,$mainconn)
	{
		$query ="SELECT * FROM $table WHERE branch='$storebranch' AND report_date='$transdate' AND shift='$shift' AND id='$rowid'";
		$result = mysqli_query($db, $query);  
		while($ROW = mysqli_fetch_array($result))  
		{
			$bid = $ROW['id'];$branch = $ROW['branch'];$report_date = $ROW['report_date'];$shift = $ROW['shift'];$time_covered = $ROW['time_covered'];$employee_name = $ROW['employee_name'];
			$supervisor = $ROW['supervisor'];$remarks = $ROW['remarks'];$category = $ROW['category'];$item_id = $ROW['item_id'];$item_name = $ROW['item_name'];$quantity = $ROW['quantity'];
			$units = $ROW['units'];$supp_prefix = $ROW['supp_prefix'];$supplier = $ROW['supplier'];$invdr_no = $ROW['invdr_no'];$date_created = $ROW['date_created'];
			$date_updated = $ROW['date_updated'];$updated_by = $ROW['updated_by'];$posted = $ROW['posted'];$status = $ROW['status'];$slip_no = $ROW['slip_no'];
			
			$update = "
				branch='$branch',report_date='$report_date',shift='$shift',time_covered='$time_covered',employee_name='$employee_name',supervisor='$supervisor',remarks='$remarks',
				category='$category',item_id='$item_id',item_name='$item_name',quantity='$quantity',units='$units',supp_prefix='$supp_prefix',supplier='$supplier',invdr_no='$invdr_no',
				date_created='$date_created',date_updated='$date_updated',updated_by='$updated_by',posted='$posted',status='$status',slip_no='$slip_no'
			";			
		}		
		$queryRemote ="SELECT * FROM $table WHERE branch='$storebranch' AND report_date='$transdate' AND shift='$shift' AND bid='$bid'";
		$remoteResult = mysqli_query($mainconn, $queryRemote);  
		if($remoteResult->num_rows > 0)
		{
			$queryDataUpdate = "UPDATE $table SET $update WHERE bid='$rowid' AND branch='$storebranch' AND report_date='$transdate' AND shift='$shift'";
			if ($mainconn->query($queryDataUpdate) === TRUE) { } else { echo "ERROR::: ".$mainconn->error; }
		} else {
			$queryInsert = "INSERT INTO $table (`bid`,`branch`,`report_date`,`shift`,`time_covered`,`employee_name`,`supervisor`,`remarks`,`category`,`item_id`,`item_name`,`quantity`,`units`,`supp_prefix`,`supplier`,`invdr_no`,`date_created`,`date_updated`,`updated_by`,`posted`,`status`,`slip_no`)";
			$queryInsert .= "VALUES('$bid','$branch','$report_date','$shift','$time_covered','$employee_name','$supervisor','$remarks','$category','$item_id','$item_name','$quantity','$units','$supp_prefix','$supplier','$invdr_no','$date_created','$date_updated','$updated_by','$posted','$status','$slip_no')";
			if ($mainconn->query($queryInsert) === TRUE) { } else { echo "ERROR::: ".$mainconn->error; }
		}
	}
	
	/* ################################################## RAWMATS TRANSFER ################################################### */
		public function SendRmTransferToServer($recipient,$transdate,$shift,$time_stamp,$table,$rowid,$db,$mainconn)
	{
		$query ="SELECT * FROM store_rm_transfer_data WHERE $recipient AND report_date='$transdate' AND shift='$shift' AND id='$rowid'";
		$result = mysqli_query($db, $query);  
		while($ROW = mysqli_fetch_array($result))  
		{
			$bid = $ROW['id'];$tid = $ROW['tid'];$branch = $ROW['branch'];$report_date = $ROW['report_date'];$shift = $ROW['shift'];$time_covered = $ROW['time_covered'];
			$employee_name = $ROW['employee_name'];$supervisor = $ROW['supervisor'];$category = $ROW['category'];$item_id = $ROW['item_id'];$item_name = $ROW['item_name'];
			$weight = $ROW['weight'];$units = $ROW['units'];$transfer_to = $ROW['transfer_to'];$date_created = $ROW['date_created'];$date_updated = $ROW['date_updated'];
			$updated_by = $ROW['updated_by'];$posted = $ROW['posted'];$status = $ROW['status'];
	
			$update = "			
				bid='$bid',branch='$branch',report_date='$report_date',shift='$shift',time_covered='$time_covered',employee_name='$employee_name',
				supervisor='$supervisor',category='$category',item_id='$item_id',item_name='$item_name',weight='$weight',units='$units',transfer_to='$transfer_to',
				date_created='$date_created',date_updated='$date_updated',updated_by='$updated_by',posted='$posted',status='$status'
			";			
		}		
		$queryRemote ="SELECT * FROM store_rm_transfer_data WHERE $recipient AND report_date='$transdate' AND shift='$shift' AND bid='$bid'";		
		$remoteResult = mysqli_query($mainconn, $queryRemote);  
		if($remoteResult->num_rows > 0)
		{
			$queryDataUpdate = "UPDATE store_rm_transfer_data SET $update WHERE $recipient AND report_date='$transdate' AND shift='$shift' AND bid='$bid'";			
			if ($mainconn->query($queryDataUpdate) === TRUE) { } else { echo "ERROR::: ".$mainconn->error; }
		} else {
			$queryInsert = "INSERT INTO store_rm_transfer_data (`bid`,`branch`,`report_date`,`shift`,`time_covered`,`employee_name`,`supervisor`,`category`,`item_id`,`item_name`,`weight`,`units`,`transfer_to`,`date_created`,`date_updated`,`updated_by`,`posted`,`status`)";
			$queryInsert .= "VALUES('$bid','$branch','$report_date','$shift','$time_covered','$employee_name','$supervisor','$category','$item_id','$item_name','$weight','$units','$transfer_to','$date_created','$date_updated','$updated_by','$posted','$status')";
			if ($mainconn->query($queryInsert) === TRUE) { } else { echo "ERRORS::: ".$mainconn->error; }
		}
	}

	/* ################################################## RAWMATS BAD ORDER ################################################### */
	public function SendRmBOToServer($storebranch,$transdate,$shift,$time_stamp,$table,$rowid,$db,$mainconn)
	{
		$query ="SELECT * FROM $table WHERE branch='$storebranch' AND report_date='$transdate' AND shift='$shift' AND id='$rowid'";
		$result = mysqli_query($db, $query);  
		while($ROW = mysqli_fetch_array($result))  
		{
			$bid = $ROW['id'];$branch = $ROW['branch'];$report_date = $ROW['report_date'];$shift = $ROW['shift'];$time_covered = $ROW['time_covered'];$employee_name = $ROW['employee_name'];
			$supervisor = $ROW['supervisor'];$slip_no = $ROW['slip_no'];$category = $ROW['category'];$item_id = $ROW['item_id'];$item_name = $ROW['item_name'];$actual_count = $ROW['actual_count'];
			$units = $ROW['units'];$total = $ROW['total'];$remarks = $ROW['remarks'];$date_created = $ROW['date_created'];$date_updated = $ROW['date_updated'];
			$updated_by = $ROW['updated_by'];$posted = $ROW['posted'];$status = $ROW['status'];
			
			$update = "
				branch='$branch',report_date='$report_date',shift='$shift',time_covered='$time_covered',employee_name='$employee_name',supervisor='$supervisor',
				slip_no='$slip_no',category='$category',item_id='$item_id',item_name='$item_name',actual_count='$actual_count',units='$units',total='$total',remarks='$remarks',
				date_created='$date_created',date_updated='$date_updated',updated_by='$updated_by',posted='$posted',status='$status'				
			";			
		}		
		$queryRemote ="SELECT * FROM $table WHERE branch='$storebranch' AND report_date='$transdate' AND shift='$shift' AND bid='$bid'";
		$remoteResult = mysqli_query($mainconn, $queryRemote);  
		if($remoteResult->num_rows > 0)
		{
			$queryDataUpdate = "UPDATE $table SET $update WHERE bid='$rowid' AND branch='$storebranch' AND report_date='$transdate' AND shift='$shift'";
			if ($mainconn->query($queryDataUpdate) === TRUE) { } else { echo "ERROR::: ".$mainconn->error; }
		} else {
			$queryInsert = "INSERT INTO $table (`bid`,`branch`,`report_date`,`shift`,`time_covered`,`employee_name`,`supervisor`,`slip_no`,`category`,`item_id`,`item_name`,`actual_count`,`units`,`total`,`remarks`,`date_created`,`date_updated`,`updated_by`,`posted`,`status`)";
			$queryInsert .= "VALUES('$bid','$branch','$report_date','$shift','$time_covered','$employee_name','$supervisor','$slip_no','$category','$item_id','$item_name','$actual_count','$units','$total','$remarks','$date_created','$date_updated','$updated_by','$posted','$status')";
			if ($mainconn->query($queryInsert) === TRUE) { } else { echo "ERROR::: ".$mainconn->error; }
		}
	}
	/* ################################################## RAWMATS PHYSICAL COUNT ################################################### */
	public function SendRmPCountToServer($storebranch,$transdate,$shift,$time_stamp,$table,$rowid,$db,$mainconn)
	{
		$query ="SELECT * FROM $table WHERE branch='$storebranch' AND report_date='$transdate' AND shift='$shift' AND id='$rowid'";
		$result = mysqli_query($db, $query);  
		while($ROW = mysqli_fetch_array($result))  
		{
			$bid = $ROW['id'];$branch = $ROW['branch'];$report_date = $ROW['report_date'];$shift = $ROW['shift'];$time_covered = $ROW['time_covered'];
			$employee_name = $ROW['employee_name'];$supervisor = $ROW['supervisor'];$category = $ROW['category'];$item_id = $ROW['item_id'];
			$item_name = $ROW['item_name'];$actual_count = $ROW['actual_count'];$units = $ROW['units'];$date_created = $ROW['date_created'];$date_updated = $ROW['date_updated'];
			$updated_by = $ROW['updated_by'];$posted = $ROW['posted'];$status = $ROW['status'];$from_shift = $ROW['from_shift'];$to_shift = $ROW['to_shift'];
			$to_shift_date = $ROW['to_shift_date'];
						
			$update = "
				branch='$branch',report_date='$report_date',shift='$shift',time_covered='$time_covered',employee_name='$employee_name',supervisor='$supervisor',category='$category',
				item_id='$item_id',item_name='$item_name',actual_count='$actual_count',units='$units',date_created='$date_created',date_updated='$date_updated',updated_by='$updated_by',posted='$posted',
				status='$status',from_shift='$from_shift',to_shift='$to_shift',to_shift_date='$to_shift_date'
			";			
		}		
		$queryRemote ="SELECT * FROM $table WHERE branch='$storebranch' AND report_date='$transdate' AND shift='$shift' AND bid='$bid'";
		$remoteResult = mysqli_query($mainconn, $queryRemote);  
		if($remoteResult->num_rows > 0)
		{
			$queryDataUpdate = "UPDATE $table SET $update WHERE bid='$rowid' AND branch='$storebranch' AND report_date='$transdate' AND shift='$shift'";
			if ($mainconn->query($queryDataUpdate) === TRUE) { } else { echo "ERROR::: ".$mainconn->error; }
		} else {
			$queryInsert = "INSERT INTO $table (`bid`,`branch`,`report_date`,`shift`,`time_covered`,`employee_name`,`supervisor`,`category`,`item_id`,`item_name`,`actual_count`,`units`,`date_created`,`date_updated`,`updated_by`,`posted`,`status`,`from_shift`,`to_shift`,`to_shift_date`)";
			$queryInsert .= "VALUES('$bid','$branch','$report_date','$shift','$time_covered','$employee_name','$supervisor','$category','$item_id','$item_name','$actual_count','$units','$date_created','$date_updated','$updated_by','$posted','$status','$from_shift','$to_shift','$to_shift_date')";
			if ($mainconn->query($queryInsert) === TRUE) { } else { echo "ERROR::: ".$mainconn->error; }
		}
	}	
	/* ################################################## DUM ################################################### */
	public function SendDumToServer($storebranch,$transdate,$shift,$time_stamp,$table,$rowid,$db,$mainconn)
	{
		$query ="SELECT * FROM $table WHERE branch='$storebranch' AND report_date='$transdate' AND shift='$shift' AND id='$rowid'";
		$result = mysqli_query($db, $query);  
		while($ROW = mysqli_fetch_array($result))  
		{
			$bid = $ROW['id'];$sid = $ROW['sid'];$branch = $ROW['branch'];$report_date = $ROW['report_date'];$shift = $ROW['shift'];$item_id = $ROW['item_id'];
			$item_name = $ROW['item_name'];$beginning = $ROW['beginning'];$delivery = $ROW['delivery'];$transfer_in = $ROW['transfer_in'];$transfer_out = $ROW['transfer_out'];
			$counter_out = $ROW['counter_out'];$sub_total = $ROW['sub_total'];$actual_usage = $ROW['actual_usage'];$net_total = $ROW['net_total'];$physical_count = $ROW['physical_count'];
			$variance = $ROW['variance'];$price_kg = $ROW['price_kg'];$variance_amount = $ROW['variance_amount'];$posted = $ROW['posted'];$status = $ROW['status'];
						
			$update = "
				branch='$branch',report_date='$report_date',shift='$shift',item_id='$item_id',item_name='$item_name',beginning='$beginning',delivery='$delivery',
				transfer_in='$transfer_in',transfer_out='$transfer_out',counter_out='$counter_out',sub_total='$sub_total',actual_usage='$actual_usage',net_total='$net_total',physical_count='$physical_count',variance='$variance',
				price_kg='$price_kg',variance_amount='$variance_amount',posted='$posted',status='$status'
			";			
		}		
		$queryRemote ="SELECT * FROM $table WHERE branch='$storebranch' AND report_date='$transdate' AND shift='$shift' AND bid='$bid'";
		$remoteResult = mysqli_query($mainconn, $queryRemote);  
		if($remoteResult->num_rows > 0)
		{
			$queryDataUpdate = "UPDATE $table SET $update WHERE bid='$rowid' AND branch='$storebranch' AND report_date='$transdate' AND shift='$shift'";
			if ($mainconn->query($queryDataUpdate) === TRUE) { } else { echo "ERROR::: ".$mainconn->error; }
		} else {
			$queryInsert = "INSERT INTO $table (`bid`,`sid`,`branch`,`report_date`,`shift`,`item_id`,`item_name`,`beginning`,`delivery`,`transfer_in`,`transfer_out`,`counter_out`,`sub_total`,`actual_usage`,`net_total`,`physical_count`,`variance`,`price_kg`,`variance_amount`,`posted`,`status`)";
			$queryInsert .= "VALUES('$bid','$sid','$branch','$report_date','$shift','$item_id','$item_name','$beginning','$delivery','$transfer_in','$transfer_out','$counter_out','$sub_total','$actual_usage','$net_total','$physical_count','$variance','$price_kg','$variance_amount','$posted','$status')";
			if ($mainconn->query($queryInsert) === TRUE) { } else { echo "ERROR::: ".$mainconn->error; }
		}
	}
	/* ################################################## RAWMATS SUMMARY ################################################### */
	public function SendRmSummaryToServer($storebranch,$transdate,$shift,$time_stamp,$table,$rowid,$db,$mainconn)
	{
		$query ="SELECT * FROM $table WHERE branch='$storebranch' AND report_date='$transdate' AND shift='$shift' AND id='$rowid'";
		$result = mysqli_query($db, $query);  
		while($ROW = mysqli_fetch_array($result))  
		{
			$bid = $ROW['id'];$branch = $ROW['branch'];$report_date = $ROW['report_date'];$shift = $ROW['shift'];$time_covered= $ROW['time_covered'];$category= $ROW['category'];
			$item_id = $ROW['item_id'];$item_name = $ROW['item_name'];$beginning = $ROW['beginning'];$stock_in = $ROW['stock_in'];$receiving_in = $ROW['receiving_in'];$transfer_in = $ROW['transfer_in'];
			$sub_total = $ROW['sub_total'];$transfer_out = $ROW['transfer_out'];$bo = $ROW['bo'];$total = $ROW['total'];$actual_count = $ROW['actual_count'];$difference = $ROW['difference'];
			$price_kg = $ROW['price_kg'];$amount = $ROW['amount'];$date_created = $ROW['date_created'];$posted = $ROW['posted'];$status = $ROW['status'];
					
			$update = "
				branch='$branch',report_date='$report_date',shift='$shift',time_covered='$time_covered',category='$category',
				item_id='$item_id',item_name='$item_name',beginning='$beginning',stock_in='$stock_in',receiving_in='$receiving_in',transfer_in='$transfer_in',
				sub_total='sub_total',transfer_out='$transfer_out',bo='$bo',total='$total',actual_count='$actual_count',difference='$difference',
				price_kg='$price_kg',amount='$amount',date_created='$date_created',posted='$posted',status='$status'
			";			
		}		
		$queryRemote ="SELECT * FROM $table WHERE branch='$storebranch' AND report_date='$transdate' AND shift='$shift' AND bid='$bid'";
		$remoteResult = mysqli_query($mainconn, $queryRemote);  
		if($remoteResult->num_rows > 0)
		{
			$queryDataUpdate = "UPDATE $table SET $update WHERE bid='$rowid' AND branch='$storebranch' AND report_date='$transdate' AND shift='$shift'";
			if ($mainconn->query($queryDataUpdate) === TRUE) { } else { echo "ERROR::: ".$mainconn->error; }
		} else {
			$queryInsert = "INSERT INTO $table (`bid`,`branch`,`report_date`,`shift`,`time_covered`,`category`,`item_id`,`item_name`,`beginning`,`stock_in`,`receiving_in`,`transfer_in`,`sub_total`,`transfer_out`,`bo`,`total`,`actual_count`,`difference`,`price_kg`,`amount`,`date_created`,`posted`,`status`)";
			$queryInsert .= "VALUES('$bid','$branch','$report_date','$shift','$time_covered','$category','$item_id','$item_name','$beginning','$stock_in','$receiving_in','$transfer_in','$sub_total','$transfer_out','$bo','$total','$actual_count','$difference','$price_kg','$amount','$date_created','$posted','$status')";
			if ($mainconn->query($queryInsert) === TRUE) { } else { echo "ERROR::: ".$mainconn->error; }
		}
	}	
}
<?php
class TheTables
{
	public function GetTables($tables,$db)
	{
		if($tables == 'fgts')
		{
			$fgts = array(
				"branch" => "branch varchar(80) NOT NULL AFTER id",
				"report_date" => "report_date DATE NULL AFTER branch",
				"shift" => "shift varchar(80) NOT NULL AFTER report_date",
				"time_covered" => "time_covered varchar(80) NOT NULL AFTER shift",
				"employee_name" => "employee_name varchar(80) NOT NULL AFTER time_covered",
				"supervisor" => "supervisor varchar(80) NOT NULL AFTER employee_name",
				"inputtime" => "inputtime varchar(20) NOT NULL AFTER supervisor",
				"category" => "category varchar(80) NOT NULL AFTER inputtime",
				"item_id" => "item_id int(11) AFTER category",
				"item_name" => "item_name varchar(80) NOT NULL AFTER item_id",
				"standard_yield" => "standard_yield double(11,2) DEFAULT '0' AFTER item_name",
				"kilo_used" => "kilo_used double(11,2) DEFAULT '0' AFTER standard_yield",
				"actual_yield" => "actual_yield double(11,2) DEFAULT '0' AFTER kilo_used",
				"unit_price" => "unit_price double(11,2) DEFAULT '0' AFTER actual_yield",
				"slip_no" => "slip_no varchar(80) NOT NULL AFTER unit_price",
				"date_created" => "date_created DATETIME NULL AFTER slip_no",
				"date_updated" => "date_updated DATETIME NULL AFTER date_created",
				"updated_by" => "updated_by varchar(80) NOT NULL AFTER date_updated",
				"posted" => "posted varchar(6) DEFAULT 'No' AFTER updated_by",
				"status" => "status varchar(6) DEFAULT 'Open' AFTER posted",
				"audit_mode" => "audit_mode int(1) DEFAULT '0' AFTER status"
			);				
			return $fgts;
		}
		if($tables == 'transfer')
		{
			$transfer = array(
				"branch" => "branch varchar(80) NOT NULL AFTER id",
				"report_date" => "report_date DATE NULL AFTER branch",
				"shift" => "shift varchar(80) NOT NULL AFTER report_date",
				"time_covered" => "time_covered varchar(80) NOT NULL AFTER shift",
				"employee_name" => "employee_name varchar(80) NOT NULL AFTER time_covered",
				"supervisor" => "supervisor varchar(80) NOT NULL AFTER employee_name",
				"category" => "category varchar(80) NOT NULL AFTER employee_name",
				"item_id" => "item_id int(11) AFTER category",
				"item_name" => "item_name varchar(80) NOT NULL AFTER item_id",
				"quantity" => "quantity double(11,2) DEFAULT '0' AFTER item_name",
				"unit_price" => "unit_price double(11,2) DEFAULT '0' AFTER quantity",
				"amount" => "amount double(11,2) DEFAULT '0' AFTER unit_price",
				"transfer_to" => "transfer_to varchar(80) NOT NULL AFTER amount",				
				"date_created" => "date_created DATETIME NULL AFTER transfer_to",
				"date_updated" => "date_updated DATETIME NULL AFTER date_created",
				"updated_by" => "updated_by varchar(80) NOT NULL AFTER date_updated",
				"posted" => "posted varchar(6) DEFAULT 'No' AFTER updated_by",
				"status" => "status varchar(6) DEFAULT 'Open' AFTER posted",
				"audit_mode" => "audit_mode int(1) DEFAULT '0' AFTER status"
			);				
			return $transfer;
		}
		if($tables == 'charges')
		{
			$charges = array(
				"branch" => "branch varchar(80) NOT NULL AFTER id",
				"report_date" => "report_date DATE NULL AFTER branch",
				"shift" => "shift varchar(80) NOT NULL AFTER report_date",
				"time_covered" => "time_covered varchar(80) NOT NULL AFTER shift",
				"employee_name" => "employee_name varchar(80) NOT NULL AFTER time_covered",
				"supervisor" => "supervisor varchar(80) NOT NULL AFTER employee_name",
				"category" => "category varchar(80) NOT NULL AFTER employee_name",
				"item_id" => "item_id int(11) AFTER category",
				"item_name" => "item_name varchar(80) NOT NULL AFTER item_id",
				"quantity" => "quantity double(11,2) DEFAULT '0' AFTER item_name",
				"unit_price" => "unit_price double(11,2) DEFAULT '0' AFTER quantity",
				"total" => "total double(11,2) DEFAULT '0' AFTER unit_price",
				"remarks" => "remarks varchar(80) NOT NULL AFTER total",				
				"date_created" => "date_created DATETIME NULL AFTER remarks",
				"date_updated" => "date_updated DATETIME NULL AFTER date_created",
				"updated_by" => "updated_by varchar(80) NOT NULL AFTER date_updated",
				"posted" => "posted varchar(6) DEFAULT 'No' AFTER updated_by",
				"status" => "status varchar(6) DEFAULT 'Open' AFTER posted",
				"audit_mode" => "audit_mode int(1) DEFAULT '0' AFTER status"
			);				
			return $charges;
		}
		if($tables == 'snacks')
		{
			$snacks = array(
				"branch" => "branch varchar(80) NOT NULL AFTER id",
				"report_date" => "report_date DATE NULL AFTER branch",
				"shift" => "shift varchar(80) NOT NULL AFTER report_date",
				"time_covered" => "time_covered varchar(80) NOT NULL AFTER shift",
				"employee_name" => "employee_name varchar(80) NOT NULL AFTER time_covered",
				"supervisor" => "supervisor varchar(80) NOT NULL AFTER employee_name",
				"category" => "category varchar(80) NOT NULL AFTER employee_name",
				"item_id" => "item_id int(11) AFTER category",
				"item_name" => "item_name varchar(80) NOT NULL AFTER item_id",
				"quantity" => "quantity double(11,2) DEFAULT '0' AFTER item_name",
				"unit_price" => "unit_price double(11,2) DEFAULT '0' AFTER quantity",
				"total" => "total double(11,2) DEFAULT '0' AFTER unit_price",
				"remarks" => "remarks varchar(80) NOT NULL AFTER total",				
				"date_created" => "date_created DATETIME NULL AFTER remarks",
				"date_updated" => "date_updated DATETIME NULL AFTER date_created",
				"updated_by" => "updated_by varchar(80) NOT NULL AFTER date_updated",
				"posted" => "posted varchar(6) DEFAULT 'No' AFTER updated_by",
				"status" => "status varchar(6) DEFAULT 'Open' AFTER posted",
				"audit_mode" => "audit_mode int(1) DEFAULT '0' AFTER status"
			);				
			return $snacks;
		}
		if($tables == 'badorder')
		{
			$badorder = array(
				"branch" => "branch varchar(80) NOT NULL AFTER id",
				"report_date" => "report_date DATE NULL AFTER branch",
				"shift" => "shift varchar(80) NOT NULL AFTER report_date",
				"time_covered" => "time_covered varchar(80) NOT NULL AFTER shift",
				"employee_name" => "employee_name varchar(80) NOT NULL AFTER time_covered",
				"supervisor" => "supervisor varchar(80) NOT NULL AFTER employee_name",
				"category" => "category varchar(80) NOT NULL AFTER employee_name",
				"item_id" => "item_id int(11) AFTER category",
				"item_name" => "item_name varchar(80) NOT NULL AFTER item_id",
				"quantity" => "quantity double(11,2) DEFAULT '0' AFTER item_name",
				"unit_price" => "unit_price double(11,2) DEFAULT '0' AFTER quantity",
				"total" => "total double(11,2) DEFAULT '0' AFTER unit_price",
				"remarks" => "remarks varchar(80) NOT NULL AFTER total",				
				"date_created" => "date_created DATETIME NULL AFTER remarks",
				"date_updated" => "date_updated DATETIME NULL AFTER date_created",
				"updated_by" => "updated_by varchar(80) NOT NULL AFTER date_updated",
				"posted" => "posted varchar(6) DEFAULT 'No' AFTER updated_by",
				"status" => "status varchar(6) DEFAULT 'Open' AFTER posted",
				"audit_mode" => "audit_mode int(1) DEFAULT '0' AFTER status"
			);				
			return $badorder;
		}
		if($tables == 'damage')
		{
			$damage = array(
				"branch" => "branch varchar(80) NOT NULL AFTER id",
				"report_date" => "report_date DATE NULL AFTER branch",
				"shift" => "shift varchar(80) NOT NULL AFTER report_date",
				"time_covered" => "time_covered varchar(80) NOT NULL AFTER shift",
				"employee_name" => "employee_name varchar(80) NOT NULL AFTER time_covered",
				"supervisor" => "supervisor varchar(80) NOT NULL AFTER employee_name",
				"category" => "category varchar(80) NOT NULL AFTER employee_name",
				"item_id" => "item_id int(11) AFTER category",
				"item_name" => "item_name varchar(80) NOT NULL AFTER item_id",
				"quantity" => "quantity double(11,2) DEFAULT '0' AFTER item_name",
				"unit_price" => "unit_price double(11,2) DEFAULT '0' AFTER quantity",
				"amount" => "amount double(11,2) DEFAULT '0' AFTER unit_price",
				"slip_no" => "slip_no varchar(20) NOT NULL AFTER amount",				
				"date_created" => "date_created DATETIME NULL AFTER slip_no",
				"date_updated" => "date_updated DATETIME NULL AFTER date_created",
				"updated_by" => "updated_by varchar(80) NOT NULL AFTER date_updated",
				"posted" => "posted varchar(6) DEFAULT 'No' AFTER updated_by",
				"status" => "status varchar(6) DEFAULT 'Open' AFTER posted",
				"audit_mode" => "audit_mode int(1) DEFAULT '0' AFTER status"
			);				
			return $damage;
		}
		if($tables == 'complimentary')
		{
			$complimentary = array(
				"branch" => "branch varchar(80) NOT NULL AFTER id",
				"report_date" => "report_date DATE NULL AFTER branch",
				"shift" => "shift varchar(80) NOT NULL AFTER report_date",
				"time_covered" => "time_covered varchar(80) NOT NULL AFTER shift",
				"employee_name" => "employee_name varchar(80) NOT NULL AFTER time_covered",
				"supervisor" => "supervisor varchar(80) NOT NULL AFTER employee_name",
				"category" => "category varchar(80) NOT NULL AFTER employee_name",
				"item_id" => "item_id int(11) AFTER category",
				"item_name" => "item_name varchar(80) NOT NULL AFTER item_id",
				"quantity" => "quantity double(11,2) DEFAULT '0' AFTER item_name",
				"unit_price" => "unit_price double(11,2) DEFAULT '0' AFTER quantity",
				"amount" => "amount double(11,2) DEFAULT '0' AFTER unit_price",
				"remarks" => "remarks varchar(80) NOT NULL AFTER amount",
				"slip_no" => "slip_no varchar(20) NOT NULL AFTER remarks",				
				"date_created" => "date_created DATETIME NULL AFTER slip_no",
				"date_updated" => "date_updated DATETIME NULL AFTER date_created",
				"updated_by" => "updated_by varchar(80) NOT NULL AFTER date_updated",
				"posted" => "posted varchar(6) DEFAULT 'No' AFTER updated_by",
				"status" => "status varchar(6) DEFAULT 'Open' AFTER posted",
				"audit_mode" => "audit_mode int(1) DEFAULT '0' AFTER status"
			);				
			return $complimentary;
		}
		if($tables == 'receiving')
		{
			$receiving = array(
				"branch" => "branch varchar(80) NOT NULL AFTER id",
				"report_date" => "report_date DATE NULL AFTER branch",
				"shift" => "shift varchar(80) NOT NULL AFTER report_date",
				"time_covered" => "time_covered varchar(80) NOT NULL AFTER shift",
				"employee_name" => "employee_name varchar(80) NOT NULL AFTER time_covered",
				"supervisor" => "supervisor varchar(80) NOT NULL AFTER employee_name",
				"category" => "category varchar(80) NOT NULL AFTER employee_name",
				"item_id" => "item_id int(11) AFTER category",
				"item_name" => "item_name varchar(80) NOT NULL AFTER item_id",
				"quantity" => "quantity double(11,2) DEFAULT '0' AFTER item_name",
				"units" => "units varchar(80) NOT NULL AFTER quantity",				
				"supp_prefix" => "supp_prefix varchar(80) NOT NULL AFTER units",
				"supplier" => "supplier varchar(80) NOT NULL AFTER supp_prefix",				
				"invdr_no" => "invdr_no varchar(80) NOT NULL AFTER supplier",
				"slip_no" => "slip_no varchar(20) NOT NULL AFTER invdr_no",
				"remarks" => "remarks varchar(80) NOT NULL AFTER slip_no",
				"date_created" => "date_created DATETIME NULL AFTER remarks",
				"date_updated" => "date_updated DATETIME NULL AFTER date_created",
				"updated_by" => "updated_by varchar(80) NOT NULL AFTER date_updated",
				"posted" => "posted varchar(6) DEFAULT 'No' AFTER updated_by",
				"status" => "status varchar(6) DEFAULT 'Open' AFTER posted",
				"audit_mode" => "audit_mode int(1) DEFAULT '0' AFTER status"
			);				
			return $receiving;
		}
		if($tables == 'cashcount')
		{
			$cashcount = array(
				"branch" => "branch varchar(80) NOT NULL AFTER id",
				"report_date" => "report_date DATE NULL AFTER branch",
				"shift" => "shift varchar(80) NOT NULL AFTER report_date",
				"time_covered" => "time_covered varchar(80) NOT NULL AFTER shift",
				"employee_name" => "employee_name varchar(80) NOT NULL AFTER time_covered",
				"supervisor" => "supervisor varchar(80) NOT NULL AFTER employee_name",
				"slip_no" => "slip_no varchar(20) NOT NULL AFTER supervisor",
				"denomination" => "denomination int(11) AFTER slip_no",
				"quantity" => "quantity double(11,2) DEFAULT '0' AFTER denomination",
				"total_amount" => "total_amount double(11,2) DEFAULT '0' AFTER quantity",				
				"date_created" => "date_created DATETIME NULL AFTER remarks",
				"date_updated" => "date_updated DATETIME NULL AFTER date_created",
				"updated_by" => "updated_by varchar(80) NOT NULL AFTER date_updated",
				"posted" => "posted varchar(6) DEFAULT 'No' AFTER updated_by",
				"status" => "status varchar(6) DEFAULT 'Open' AFTER posted",
				"audit_mode" => "audit_mode int(1) DEFAULT '0' AFTER status"
			);				
			return $cashcount;
		}
		if($tables == 'frozendough')
		{
			$frozendough = array(
				"branch" => "branch varchar(80) NOT NULL AFTER id",
				"report_date" => "report_date DATE NULL AFTER branch",
				"shift" => "shift varchar(80) NOT NULL AFTER report_date",
				"time_covered" => "time_covered varchar(80) NOT NULL AFTER shift",
				"employee_name" => "employee_name varchar(80) NOT NULL AFTER time_covered",
				"supervisor" => "supervisor varchar(80) NOT NULL AFTER employee_name",
				"inputtime" => "inputtime varchar(20) NOT NULL AFTER supervisor",
				"category" => "category varchar(80) NOT NULL AFTER inputtime",
				"item_id" => "item_id int(11) AFTER category",
				"item_name" => "item_name varchar(80) NOT NULL AFTER item_id",
				"standard_yield" => "standard_yield double(11,2) DEFAULT '0' AFTER item_name",
				"kilo_used" => "kilo_used double(11,2) DEFAULT '0' AFTER standard_yield",
				"actual_yield" => "actual_yield double(11,2) DEFAULT '0' AFTER kilo_used",
				"unit_price" => "unit_price double(11,2) DEFAULT '0' AFTER actual_yield",
				"slip_no" => "slip_no varchar(80) NOT NULL AFTER unit_price",
				"date_created" => "date_created DATETIME NULL AFTER slip_no",
				"date_updated" => "date_updated DATETIME NULL AFTER date_created",
				"updated_by" => "updated_by varchar(80) NOT NULL AFTER date_updated",
				"posted" => "posted varchar(6) DEFAULT 'No' AFTER updated_by",
				"status" => "status varchar(6) DEFAULT 'Open' AFTER posted",
				"audit_mode" => "audit_mode int(1) DEFAULT '0' AFTER status"
			);				
			return $frozendough;
		}
		if($tables == 'pcount')
		{
			$pcount = array(
				"branch" => "branch varchar(80) NOT NULL AFTER id",
				"report_date" => "report_date DATE NULL AFTER branch",
				"shift" => "shift varchar(80) NOT NULL AFTER report_date",
				"time_covered" => "time_covered varchar(80) NOT NULL AFTER shift",
				"employee_name" => "employee_name varchar(80) NOT NULL AFTER time_covered",
				"supervisor" => "supervisor varchar(80) NOT NULL AFTER employee_name",
				"category" => "category varchar(80) NOT NULL AFTER supervisor",
				"item_id" => "item_id int(11) AFTER category",
				"item_name" => "item_name varchar(80) NOT NULL AFTER item_id",
				"actual_count" => "actual_count double(11,2) DEFAULT '0' AFTER item_name",
				"date_created" => "date_created DATETIME NULL AFTER actual_count",
				"date_updated" => "date_updated DATETIME NULL AFTER date_created",
				"updated_by" => "updated_by varchar(80) NOT NULL AFTER date_updated",
				"posted" => "posted varchar(6) DEFAULT 'No' AFTER updated_by",
				"status" => "status varchar(6) DEFAULT 'Open' AFTER posted",
				"from_shift" => "from_shift varchar(30) NOT NULL AFTER status",
				"to_shift" => "to_shift varchar(30) NOT NULL AFTER from_shift",
				"to_shift_date" => "to_shift_date DATE NULL AFTER to_shift",				
				"audit_mode" => "audit_mode int(1) DEFAULT '0' AFTER to_shift_date"
			);				
			return $pcount;
		}
		if($tables == 'discount')
		{
		
			$discount = array(
				"branch" => "branch varchar(80) NOT NULL AFTER id",
				"report_date" => "report_date DATE NULL AFTER branch",
				"shift" => "shift varchar(80) NOT NULL AFTER report_date",
				"supervisor" => "supervisor varchar(80) NOT NULL AFTER shift",
				"discount" => "discount int(11) AFTER supervisor",
				"date_created" => "date_created DATETIME NULL AFTER discount",
				"date_updated" => "date_updated DATETIME NULL AFTER date_created",
				"updated_by" => "updated_by varchar(80) NOT NULL AFTER date_updated",
				"posted" => "posted varchar(6) DEFAULT 'No' AFTER updated_by",
				"status" => "status varchar(6) DEFAULT 'Open' AFTER posted",
				"audit_mode" => "audit_mode int(1) DEFAULT '0' AFTER status"
			);				
			return $discount;
		}
		if($tables == 'summary')
		{
			$summary = array(
				"pid" => "pid int(11) AFTER id",
				"branch" => "branch varchar(80) NOT NULL AFTER pid",
				"report_date" => "report_date DATE NULL AFTER branch",
				"shift" => "shift varchar(80) NOT NULL AFTER report_date",
				"time_covered" => "time_covered varchar(80) NOT NULL AFTER shift",
				"supervisor" => "supervisor varchar(80) NOT NULL AFTER time_covered",
				"inputtime" => "inputtime varchar(20) NOT NULL AFTER supervisor",
				"category" => "category varchar(80) NOT NULL AFTER inputtime",
				"item_id" => "item_id int(11) AFTER category",
				"item_name" => "item_name varchar(80) NOT NULL AFTER item_id",
				"kilo_used" => "kilo_used double(11,2) DEFAULT '0' AFTER item_name",
				"standard_yield" => "standard_yield double(11,2) DEFAULT '0' AFTER kilo_used",
				"actual_yield" => "actual_yield double(11,2) DEFAULT '0' AFTER standard_yield",
				"beginning" => "beginning double(11,2) DEFAULT '0' AFTER actual_yield",
				"stock_in" => "stock_in double(11,2) DEFAULT '0' AFTER beginning",
				"t_in" => "t_in double(11,2) DEFAULT '0' AFTER stock_in",
				"frozendough" => "frozendough double(11,2) DEFAULT '0' AFTER t_in",
				"total" => "total double(11,2) DEFAULT '0' AFTER frozendough",
				"t_out" => "t_out double(11,2) DEFAULT '0' AFTER total",
				"charges" => "charges double(11,2) DEFAULT '0' AFTER t_out",
				"snacks" => "snacks double(11,2) DEFAULT '0' AFTER charges",
				"bo" => "bo double(11,2) DEFAULT '0' AFTER snacks",
				"damaged" => "damaged double(11,2) DEFAULT '0' AFTER bo",				
				"complimentary" => "complimentary double(11,2) DEFAULT '0' AFTER damaged",				
				"should_be" => "should_be double(11,2) DEFAULT '0' AFTER complimentary",
				"actual_count" => "actual_count double(11,2) DEFAULT '0' AFTER should_be",
				"sold" => "sold double(11,2) DEFAULT '0' AFTER actual_count",
				"unit_price" => "unit_price double(11,2) DEFAULT '0' AFTER sold",				
				"amount" => "amount double(11,2) DEFAULT '0' AFTER unit_price",								
				"date_created" => "date_created DATETIME NULL AFTER actual_count",
				"date_updated" => "date_updated DATETIME NULL AFTER date_created",
				"updated_by" => "updated_by varchar(80) NOT NULL AFTER date_updated",
				"posted" => "posted varchar(6) DEFAULT 'No' AFTER updated_by",
				"status" => "status varchar(6) DEFAULT 'Open' AFTER posted",
				"form_no" => "form_no varchar(6) NOT NULL AFTER status"
			);				
			return $summary;
		}
		/* ----------------------- RAWMATS ---------------------*/
		if($tables == 'rm_receiving')
		{
			$rm_receiving = array(
				"branch" => "branch varchar(80) NOT NULL AFTER id",
				"report_date" => "report_date DATE NULL AFTER branch",
				"shift" => "shift varchar(80) NOT NULL AFTER report_date",
				"time_covered" => "time_covered varchar(80) NOT NULL AFTER shift",
				"employee_name" => "employee_name varchar(80) NOT NULL AFTER time_covered",
				"supervisor" => "supervisor varchar(80) NOT NULL AFTER employee_name",
				"category" => "category varchar(80) NOT NULL AFTER employee_name",
				"item_id" => "item_id int(11) AFTER category",
				"item_name" => "item_name varchar(80) NOT NULL AFTER item_id",
				"quantity" => "quantity double(11,2) DEFAULT '0' AFTER item_name",
				"units" => "units varchar(80) NOT NULL AFTER quantity",		
				"supplier" => "supplier varchar(80) NOT NULL AFTER units",				
				"invdr_no" => "invdr_no varchar(80) NOT NULL AFTER supplier",
				"date_created" => "date_created DATETIME NULL AFTER invdr_no",
				"date_updated" => "date_updated DATETIME NULL AFTER date_created",
				"updated_by" => "updated_by varchar(80) NOT NULL AFTER date_updated",
				"posted" => "posted varchar(6) DEFAULT 'No' AFTER updated_by",
				"status" => "status varchar(6) DEFAULT 'Open' AFTER posted",
				"audit_mode" => "audit_mode int(1) DEFAULT '0' AFTER status"
			);				
			return $rm_receiving;
		}
		if($tables == 'rm_transfer')
		{
			$rm_transfer = array(
				"branch" => "branch varchar(80) NOT NULL AFTER id",
				"report_date" => "report_date DATE NULL AFTER branch",
				"shift" => "shift varchar(80) NOT NULL AFTER report_date",
				"time_covered" => "time_covered varchar(80) NOT NULL AFTER shift",
				"employee_name" => "employee_name varchar(80) NOT NULL AFTER time_covered",
				"supervisor" => "supervisor varchar(80) NOT NULL AFTER employee_name",
				"category" => "category varchar(80) NOT NULL AFTER employee_name",
				"item_id" => "item_id int(11) AFTER category",
				"item_name" => "item_name varchar(80) NOT NULL AFTER item_id",
				"quantity" => "quantity double(11,2) DEFAULT '0' AFTER item_name",
				"units" => "units varchar(80) NOT NULL AFTER quantity",						
				"transfer_to" => "transfer_to varchar(80) NOT NULL AFTER units",				
				"date_created" => "date_created DATETIME NULL AFTER transfer_to",
				"date_updated" => "date_updated DATETIME NULL AFTER date_created",
				"updated_by" => "updated_by varchar(80) NOT NULL AFTER date_updated",
				"posted" => "posted varchar(6) DEFAULT 'No' AFTER updated_by",
				"status" => "status varchar(6) DEFAULT 'Open' AFTER posted",
				"audit_mode" => "audit_mode int(1) DEFAULT '0' AFTER status"
			);				
			return $rm_transfer;
		}
		if($tables == 'rm_badorder')
		{
			$rm_badorder = array(
				"branch" => "branch varchar(80) NOT NULL AFTER id",
				"report_date" => "report_date DATE NULL AFTER branch",
				"shift" => "shift varchar(80) NOT NULL AFTER report_date",
				"time_covered" => "time_covered varchar(80) NOT NULL AFTER shift",
				"employee_name" => "employee_name varchar(80) NOT NULL AFTER time_covered",
				"supervisor" => "supervisor varchar(80) NOT NULL AFTER employee_name",
				"category" => "category varchar(80) NOT NULL AFTER employee_name",
				"item_id" => "item_id int(11) AFTER category",
				"item_name" => "item_name varchar(80) NOT NULL AFTER item_id",
				"actual_count" => "actual_count double(11,2) DEFAULT '0' AFTER item_name",
				"units" => "units varchar(80) NOT NULL AFTER actual_count",						
				"total" => "total double(11,2) DEFAULT '0' AFTER units",
				"remarks" => "remarks varchar(80) NOT NULL AFTER total",				
				"date_created" => "date_created DATETIME NULL AFTER remarks",
				"date_updated" => "date_updated DATETIME NULL AFTER date_created",
				"updated_by" => "updated_by varchar(80) NOT NULL AFTER date_updated",
				"posted" => "posted varchar(6) DEFAULT 'No' AFTER updated_by",
				"status" => "status varchar(6) DEFAULT 'Open' AFTER posted",
				"audit_mode" => "audit_mode int(1) DEFAULT '0' AFTER status"
			);				
			return $rm_badorder;
		}
		if($tables == 'rm_pcount')
		{
			$rm_pcount = array(
				"branch" => "branch varchar(80) NOT NULL AFTER id",
				"report_date" => "report_date DATE NULL AFTER branch",
				"shift" => "shift varchar(80) NOT NULL AFTER report_date",
				"time_covered" => "time_covered varchar(80) NOT NULL AFTER shift",
				"employee_name" => "employee_name varchar(80) NOT NULL AFTER time_covered",
				"supervisor" => "supervisor varchar(80) NOT NULL AFTER employee_name",
				"category" => "category varchar(80) NOT NULL AFTER supervisor",
				"item_id" => "item_id int(11) AFTER category",
				"item_name" => "item_name varchar(80) NOT NULL AFTER item_id",
				"actual_count" => "actual_count double(11,2) DEFAULT '0' AFTER item_name",
				"units" => "units varchar(80) NOT NULL AFTER actual_count",						
				"date_created" => "date_created DATETIME NULL AFTER units",
				"date_updated" => "date_updated DATETIME NULL AFTER date_created",
				"updated_by" => "updated_by varchar(80) NOT NULL AFTER date_updated",
				"posted" => "posted varchar(6) DEFAULT 'No' AFTER updated_by",
				"status" => "status varchar(6) DEFAULT 'Open' AFTER posted",
				"from_shift" => "from_shift varchar(30) NOT NULL AFTER status",
				"to_shift" => "to_shift varchar(30) NOT NULL AFTER from_shift",
				"to_shift_date" => "to_shift_date DATE NULL AFTER to_shift",				
				"audit_mode" => "audit_mode int(1) DEFAULT '0' AFTER to_shift_date"
			);				
			return $rm_pcount;
		}
		if($tables == 'rm_summary')
		{
			$rm_summary = array(
				"pid" => "pid int(11) AFTER id",
				"branch" => "branch varchar(80) NOT NULL AFTER pid",
				"report_date" => "report_date DATE NULL AFTER branch",
				"shift" => "shift varchar(80) NOT NULL AFTER report_date",
				"category" => "category varchar(80) NOT NULL AFTER shift",
				"item_id" => "item_id int(11) AFTER category",
				"item_name" => "item_name varchar(80) NOT NULL AFTER item_id",
				"beginning" => "beginning double(11,2) DEFAULT '0' AFTER item_name",
				"stock_in" => "stock_in double(11,2) DEFAULT '0' AFTER beginning",
				"receiving_in" => "receiving_in double(11,2) DEFAULT '0' AFTER stock_in",
				"transfer_in" => "transfer_in double(11,2) DEFAULT '0' AFTER receiving_in",
				"sub_total" => "sub_total double(11,2) DEFAULT '0' AFTER transfer_in",
				"transfer_out" => "transfer_out double(11,2) DEFAULT '0' AFTER sub_total",
				"counter_out" => "counter_out double(11,2) DEFAULT '0' AFTER transfer_out",
				"bo" => "bo double(11,2) DEFAULT '0' AFTER counter_out",			
				"total" => "total double(11,2) DEFAULT '0' AFTER bo",
				"actual_count" => "actual_count double(11,2) DEFAULT '0' AFTER total",
				"difference" => "difference double(11,2) DEFAULT '0' AFTER actual_count",
				"date_created" => "date_created DATETIME NULL AFTER difference",
				"date_updated" => "date_updated DATETIME NULL AFTER date_created",
				"updated_by" => "updated_by varchar(80) NOT NULL AFTER date_updated",
				"posted" => "posted varchar(6) DEFAULT 'No' AFTER updated_by",
				"status" => "status varchar(6) DEFAULT 'Open' AFTER posted",
				"price_kg" => "price_kg double(11,2) DEFAULT '0' AFTER status",
				"amount" => "amount double(11,2) DEFAULT '0' AFTER price_kg",
				"audit_mode" => "audit_mode int(1) DEFAULT '0' AFTER amount"
			);				
			return $rm_summary;
		}
	}
/* ############################################################################################# */
	public function getItemInfo($columns,$db)
	{
		$query ="SELECT *  FROM store_items WHERE id='$itemid'"; 
		$result = mysqli_query($db, $query);  
		if($result->num_rows > 0)
		{
			while($ROW = mysqli_fetch_array($result))  
			{
				return $ROW['unit_price'];
			}
		} else {
			return 0;
		}
	}
/* ############################################################################################# */
/* ########################################### FGTS ################################################## */
	public function updateFGTS($tbl,$var_month,$db)
	{	
		$query =" SELECT * FROM $tbl WHERE MONTH(report_date)='$var_month'";
		$result = mysqli_query($db, $query);  
		$rowcount=mysqli_num_rows($result);
		while($ROW = mysqli_fetch_array($result)) 
		{
			$rowid = $ROW['id'];
			$item_id = $ROW['item_id'];
			$item_name = $ROW['item_name'];
			if($item_id == 0 || $item_id == '')
			{
				$columns = 'product_name';
				$kwiri = $item_name;				
				$itemid = getItemInfo($columns,'id',$kwiri,$db);
				$itemname = getItemInfo($columns,'product_name',$kwiri,$db);
				$standardyield = getItemInfo($columns,'yield_per_kilo',$kwiri,$db);
				$unitprice = getItemInfo($columns,'unit_price',$kwiri,$db);				
			}
			else if($item_id != 0 || $item_id != '')
			{
				$columns = 'id';
				$kwiri = $item_id;
				$itemid = getItemInfo($columns,'id',$kwiri,$db);
				$itemname = getItemInfo($columns,'product_name',$kwiri,$db);
				$standardyield = getItemInfo($columns,'yield_per_kilo',$kwiri,$db);
				$unitprice = getItemInfo($columns,'unit_price',$kwiri,$db);
			}
			
			updateThisFGTS($itemid,$itemname,$standardyield,$unitprice,$rowid,$db);
		}
	}
	/* ######################################### TRANSFER ################################################# */
	public function updateTRANSFER($tbl,$var_month,$db)
	{	
		$query =" SELECT * FROM $tbl WHERE MONTH(report_date)='$var_month'";
		$result = mysqli_query($db, $query);  
		$rowcount=mysqli_num_rows($result);
		while($ROW = mysqli_fetch_array($result)) 
		{
			$rowid = $ROW['id'];
			$item_id = $ROW['item_id'];
			$item_name = $ROW['item_name'];
			if($item_id == 0 || $item_id == '')
			{
				$columns = 'product_name';
				$kwiri = $item_name;
				$itemid = getItemInfo($columns,'id',$kwiri,$db);
				$itemname = getItemInfo($columns,'product_name',$kwiri,$db);
				$unitprice = getItemInfo($columns,'unit_price',$kwiri,$db);
			}
			else if($item_id != 0 || $item_id != '')
			{
				$columns = 'id';
				$kwiri = $item_id;
				$itemid = getItemInfo($columns,'id',$kwiri,$db);
				$itemname = getItemInfo($columns,'product_name',$kwiri,$db);
				$unitprice = getItemInfo($columns,'unit_price',$kwiri,$db);
			}
			
			updateThisTRANSFER($itemid,$itemname,$unitprice,$rowid,$db);
		}
	}	
	/* ######################################### CHARGES ################################################# */
	public function updateCHARGES($tbl,$var_month,$db)
	{	
		$query =" SELECT * FROM $tbl WHERE MONTH(report_date)='$var_month'";
		$result = mysqli_query($db, $query);  
		$rowcount=mysqli_num_rows($result);
		while($ROW = mysqli_fetch_array($result)) 
		{
			$rowid = $ROW['id'];
			$item_id = $ROW['item_id'];
			$item_name = $ROW['item_name'];
			if($item_id == 0 || $item_id == '')
			{
				$columns = 'product_name';
				$kwiri = $item_name;
				$itemid = getItemInfo($columns,'id',$kwiri,$db);
				$itemname = getItemInfo($columns,'product_name',$kwiri,$db);
				$unitprice = getItemInfo($columns,'unit_price',$kwiri,$db);
			}
			else if($item_id != 0 || $item_id != '')
			{
				$columns = 'id';
				$kwiri = $item_id;
				$itemid = getItemInfo($columns,'id',$kwiri,$db);
				$itemname = getItemInfo($columns,'product_name',$kwiri,$db);
				$unitprice = getItemInfo($columns,'unit_price',$kwiri,$db);
			}
			
			updateThisCHARGES($itemid,$itemname,$unitprice,$rowid,$db);
		}
	}	
	/* ######################################### SNACKS ################################################# */
	public function updateSNACKS($tbl,$var_month,$db)
	{	
		$query =" SELECT * FROM $tbl WHERE MONTH(report_date)='$var_month'";
		$result = mysqli_query($db, $query);  
		$rowcount=mysqli_num_rows($result);
		while($ROW = mysqli_fetch_array($result)) 
		{
			$rowid = $ROW['id'];
			$item_id = $ROW['item_id'];
			$item_name = $ROW['item_name'];
			if($item_id == 0 || $item_id == '')
			{
				$columns = 'product_name';
				$kwiri = $item_name;
				$itemid = getItemInfo($columns,'id',$kwiri,$db);
				$itemname = getItemInfo($columns,'product_name',$kwiri,$db);
				$unitprice = getItemInfo($columns,'unit_price',$kwiri,$db);
			}
			else if($item_id != 0 || $item_id != '')
			{
				$columns = 'id';
				$kwiri = $item_id;
				$itemid = getItemInfo($columns,'id',$kwiri,$db);
				$itemname = getItemInfo($columns,'product_name',$kwiri,$db);
				$unitprice = getItemInfo($columns,'unit_price',$kwiri,$db);
			}
			
			updateThisSNACKS($itemid,$itemname,$unitprice,$rowid,$db);
		}
	}
	/* ######################################### BAD ORDER ################################################# */
	public function updateBADORDER($tbl,$var_month,$db)
	{	
		$query =" SELECT * FROM $tbl WHERE MONTH(report_date)='$var_month'";
		$result = mysqli_query($db, $query);  
		$rowcount=mysqli_num_rows($result);
		while($ROW = mysqli_fetch_array($result)) 
		{
			$rowid = $ROW['id'];
			$item_id = $ROW['item_id'];
			$item_name = $ROW['item_name'];
			if($item_id == 0 || $item_id == '')
			{
				$columns = 'product_name';
				$kwiri = $item_name;
				$itemid = getItemInfo($columns,'id',$kwiri,$db);
				$itemname = getItemInfo($columns,'product_name',$kwiri,$db);
				$unitprice = getItemInfo($columns,'unit_price',$kwiri,$db);
			}
			else if($item_id != 0 || $item_id != '')
			{
				$columns = 'id';
				$kwiri = $item_id;
				$itemid = getItemInfo($columns,'id',$kwiri,$db);
				$itemname = getItemInfo($columns,'product_name',$kwiri,$db);
				$unitprice = getItemInfo($columns,'unit_price',$kwiri,$db);
			}
			updateThisBADORDER($itemid,$itemname,$unitprice,$rowid,$db);
		}
	}
	/* ######################################### DAMAGED ################################################# */
	public function updateDAMAGE($tbl,$var_month,$db)
	{	
		$query =" SELECT * FROM $tbl WHERE MONTH(report_date)='$var_month'";
		$result = mysqli_query($db, $query);  
		$rowcount=mysqli_num_rows($result);
		while($ROW = mysqli_fetch_array($result)) 
		{
			$rowid = $ROW['id'];
			$item_id = $ROW['item_id'];
			$item_name = $ROW['item_name'];
			if($item_id == 0 || $item_id == '')
			{
				$columns = 'product_name';
				$kwiri = $item_name;
				$itemid = getItemInfo($columns,'id',$kwiri,$db);
				$itemname = getItemInfo($columns,'product_name',$kwiri,$db);
				$unitprice = getItemInfo($columns,'unit_price',$kwiri,$db);
			}
			else if($item_id != 0 || $item_id != '')
			{
				$columns = 'id';
				$kwiri = $item_id;
				$itemid = getItemInfo($columns,'id',$kwiri,$db);
				$itemname = getItemInfo($columns,'product_name',$kwiri,$db);
				$unitprice = getItemInfo($columns,'unit_price',$kwiri,$db);
			}
			updateThisDAMAGED($itemid,$itemname,$unitprice,$rowid,$db);
		}
	}
	/* ######################################### COMPLIMENTARY ################################################# */
	public function updateCOMPLIMENTARY($tbl,$var_month,$db)
	{	
		$query =" SELECT * FROM $tbl WHERE MONTH(report_date)='$var_month'";
		$result = mysqli_query($db, $query);  
		$rowcount=mysqli_num_rows($result);
		while($ROW = mysqli_fetch_array($result)) 
		{
			$rowid = $ROW['id'];
			$item_id = $ROW['item_id'];
			$item_name = $ROW['item_name'];
			if($item_id == 0 || $item_id == '')
			{
				$columns = 'product_name';
				$kwiri = $item_name;
				$itemid = getItemInfo($columns,'id',$kwiri,$db);
				$itemname = getItemInfo($columns,'product_name',$kwiri,$db);
				$unitprice = getItemInfo($columns,'unit_price',$kwiri,$db);
			}
			else if($item_id != 0 || $item_id != '')
			{
				$columns = 'id';
				$kwiri = $item_id;
				$itemid = getItemInfo($columns,'id',$kwiri,$db);
				$itemname = getItemInfo($columns,'product_name',$kwiri,$db);
				$unitprice = getItemInfo($columns,'unit_price',$kwiri,$db);
			}
			updateThisCOMPLIMENTARY($itemid,$itemname,$unitprice,$rowid,$db);
		}
	}
	/* ######################################### RECEIVING ################################################# */
	public function updateRECEIVING($tbl,$var_month,$db)
	{	
		$query =" SELECT * FROM $tbl WHERE MONTH(report_date)='$var_month'";
		$result = mysqli_query($db, $query);  
		$rowcount=mysqli_num_rows($result);
		while($ROW = mysqli_fetch_array($result)) 
		{
			$rowid = $ROW['id'];
			$item_id = $ROW['item_id'];
			$item_name = $ROW['item_name'];
			if($item_id == 0 || $item_id == '')
			{
				$columns = 'product_name';
				$kwiri = $item_name;
				$itemid = getItemInfo($columns,'id',$kwiri,$db);
				$itemname = getItemInfo($columns,'product_name',$kwiri,$db);
			}
			else if($item_id != 0 || $item_id != '')
			{
				$columns = 'id';
				$kwiri = $item_id;
				$itemid = getItemInfo($columns,'id',$kwiri,$db);
				$itemname = getItemInfo($columns,'product_name',$kwiri,$db);
			}
			updateThisRECEIVING($itemid,$itemname,$rowid,$db);
		}
	}
	/* ########################################### FROZEN DOUGH ################################################## */
	public function updateFROZENDOUGH($tbl,$var_month,$db)
	{	
		$query =" SELECT * FROM $tbl WHERE MONTH(report_date)='$var_month'";
		$result = mysqli_query($db, $query);  
		$rowcount=mysqli_num_rows($result);
		while($ROW = mysqli_fetch_array($result)) 
		{
			$rowid = $ROW['id'];
			$item_id = $ROW['item_id'];
			$item_name = $ROW['item_name'];
			if($item_id == 0 || $item_id == '')
			{
				$columns = 'product_name';
				$kwiri = $item_name;				
				$itemid = getItemInfo($columns,'id',$kwiri,$db);
				$itemname = getItemInfo($columns,'product_name',$kwiri,$db);
				$standardyield = getItemInfo($columns,'yield_per_kilo',$kwiri,$db);
				$unitprice = getItemInfo($columns,'unit_price',$kwiri,$db);				
			}
			else if($item_id != 0 || $item_id != '')
			{
				$columns = 'id';
				$kwiri = $item_id;
				$itemid = getItemInfo($columns,'id',$kwiri,$db);
				$itemname = getItemInfo($columns,'product_name',$kwiri,$db);
				$standardyield = getItemInfo($columns,'yield_per_kilo',$kwiri,$db);
				$unitprice = getItemInfo($columns,'unit_price',$kwiri,$db);
			}
			
			updateThisFROZENDOUGH($itemid,$itemname,$standardyield,$unitprice,$rowid,$db);
		}
	}
	/* ########################################### PHYSICAL COUNT ################################################## */
	public function updatePCOUNT($tbl,$var_month,$db)
	{	
		$query =" SELECT * FROM $tbl WHERE MONTH(report_date)='$var_month'";
		$result = mysqli_query($db, $query);  
		$rowcount=mysqli_num_rows($result);
		while($ROW = mysqli_fetch_array($result)) 
		{
			$rowid = $ROW['id'];
			$item_id = $ROW['item_id'];
			$item_name = $ROW['item_name'];
			if($item_id == 0 || $item_id == '')
			{
				$columns = 'product_name';
				$kwiri = $item_name;				
				$itemid = getItemInfo($columns,'id',$kwiri,$db);
				$itemname = getItemInfo($columns,'product_name',$kwiri,$db);
			}
			else if($item_id != 0 || $item_id != '')
			{
				$columns = 'id';
				$kwiri = $item_id;
				$itemid = getItemInfo($columns,'id',$kwiri,$db);
				$itemname = getItemInfo($columns,'product_name',$kwiri,$db);
			}
			
			updateThisPCOUNT($itemid,$itemname,$rowid,$db);
		}
	}
	/* ########################################### SUMMARY ################################################## */
	public function updateSUMMARY($tbl,$var_month,$db)
	{	
		$query =" SELECT * FROM $tbl WHERE MONTH(report_date)='$var_month'";
		$result = mysqli_query($db, $query);  
		$rowcount=mysqli_num_rows($result);
		while($ROW = mysqli_fetch_array($result)) 
		{
			$rowid = $ROW['id'];
			$item_id = $ROW['item_id'];
			$item_name = $ROW['item_name'];
			
			if($item_id == 0 || $item_id == '')
			{
				$columns = 'product_name';
				$kwiri = $item_name;				
				$itemid = getItemInfo($columns,'id',$kwiri,$db);
				$itemname = getItemInfo($columns,'product_name',$kwiri,$db);
				$standardyield = getItemInfo($columns,'yield_per_kilo',$kwiri,$db);
				$unitprice = getItemInfo($columns,'unit_price',$kwiri,$db);				
			}
			else if($item_id != 0 || $item_id != '')
			{
				$columns = 'id';
				$kwiri = $item_id;
				$itemid = getItemInfo($columns,'id',$kwiri,$db);
				$itemname = getItemInfo($columns,'product_name',$kwiri,$db);
				$standardyield = getItemInfo($columns,'yield_per_kilo',$kwiri,$db);
				$unitprice = getItemInfo($columns,'unit_price',$kwiri,$db);
			}
			
			updateThisSUMMARY($itemid,$itemname,$standardyield,$unitprice,$rowid,$db);
		}
	}
	/* ------------------------------ RAWMATS ------------------------ */
	public function updateRMRECEIVING($tbl,$var_month,$db)
	{	
		$query =" SELECT * FROM $tbl WHERE MONTH(report_date)='$var_month'";
		$result = mysqli_query($db, $query);  
		$rowcount=mysqli_num_rows($result);
		while($ROW = mysqli_fetch_array($result)) 
		{
			$rowid = $ROW['id'];
			$item_id = $ROW['item_id'];
			$item_name = $ROW['item_name'];
			if($item_id == 0 || $item_id == '')
			{
				$columns = 'product_name';
				$kwiri = $item_name;
				$itemid = getItemInfo($columns,'id',$kwiri,$db);
				$itemname = getItemInfo($columns,'product_name',$kwiri,$db);
			}
			else if($item_id != 0 || $item_id != '')
			{
				$columns = 'id';
				$kwiri = $item_id;
				$itemid = getItemInfo($columns,'id',$kwiri,$db);
				$itemname = getItemInfo($columns,'product_name',$kwiri,$db);
			}
			updateThisRMRECEIVING($itemid,$itemname,$rowid,$db);
		}
	}
	public function updateRMTRANSFER($tbl,$var_month,$db)
	{	
		$query =" SELECT * FROM $tbl WHERE MONTH(report_date)='$var_month'";
		$result = mysqli_query($db, $query);  
		$rowcount=mysqli_num_rows($result);
		while($ROW = mysqli_fetch_array($result)) 
		{
			$rowid = $ROW['id'];
			$item_id = $ROW['item_id'];
			$item_name = $ROW['item_name'];
			if($item_id == 0 || $item_id == '')
			{
				$columns = 'product_name';
				$kwiri = $item_name;
				$itemid = getItemInfo($columns,'id',$kwiri,$db);
				$itemname = getItemInfo($columns,'product_name',$kwiri,$db);
			}
			else if($item_id != 0 || $item_id != '')
			{
				$columns = 'id';
				$kwiri = $item_id;
				$itemid = getItemInfo($columns,'id',$kwiri,$db);
				$itemname = getItemInfo($columns,'product_name',$kwiri,$db);
			}			
			updateThisRMTRANSFER($itemid,$itemname,$rowid,$db);
		}
	}
	public function updateRMBADORDER($tbl,$var_month,$db)
	{	
		$query =" SELECT * FROM $tbl WHERE MONTH(report_date)='$var_month'";
		$result = mysqli_query($db, $query);  
		$rowcount=mysqli_num_rows($result);
		while($ROW = mysqli_fetch_array($result)) 
		{
			$rowid = $ROW['id'];
			$item_id = $ROW['item_id'];
			$item_name = $ROW['item_name'];
			if($item_id == 0 || $item_id == '')
			{
				$columns = 'product_name';
				$kwiri = $item_name;
				$itemid = getItemInfo($columns,'id',$kwiri,$db);
				$itemname = getItemInfo($columns,'product_name',$kwiri,$db);
				$category = getItemInfo($columns,'category_name',$kwiri,$db);
			}
			else if($item_id != 0 || $item_id != '')
			{
				$columns = 'id';
				$kwiri = $item_id;
				$itemid = getItemInfo($columns,'id',$kwiri,$db);
				$itemname = getItemInfo($columns,'product_name',$kwiri,$db);
				$category = getItemInfo($columns,'category_name',$kwiri,$db);
			}
			updateThisRMBADORDER($itemid,$itemname,$category,$rowid,$db);
		}
	}
	/* ########################################### PHYSICAL COUNT ################################################## */
	public function updateRMPCOUNT($tbl,$var_month,$db)
	{	
		$query =" SELECT * FROM $tbl WHERE MONTH(report_date)='$var_month'";
		$result = mysqli_query($db, $query);  
		$rowcount=mysqli_num_rows($result);
		while($ROW = mysqli_fetch_array($result)) 
		{
			$rowid = $ROW['id'];
			$item_id = $ROW['item_id'];
			$item_name = $ROW['item_name'];
			if($item_id == 0 || $item_id == '')
			{
				$columns = 'product_name';
				$kwiri = $item_name;				
				$itemid = getItemInfo($columns,'id',$kwiri,$db);
				$itemname = getItemInfo($columns,'product_name',$kwiri,$db);
				$category = getItemInfo($columns,'category_name',$kwiri,$db);
			}
			else if($item_id != 0 || $item_id != '')
			{
				$columns = 'id';
				$kwiri = $item_id;
				$itemid = getItemInfo($columns,'id',$kwiri,$db);
				$itemname = getItemInfo($columns,'product_name',$kwiri,$db);
				$category = getItemInfo($columns,'category_name',$kwiri,$db);
			}
			updateThisRMPCOUNT($itemid,$itemname,$category,$rowid,$db);
		}
	}
	/* ########################################### SUMMARY ################################################## */
	public function updateRMSUMMARY($tbl,$var_month,$db)
	{	
		$query =" SELECT * FROM $tbl WHERE MONTH(report_date)='$var_month'";
		$result = mysqli_query($db, $query);  
		$rowcount=mysqli_num_rows($result);
		while($ROW = mysqli_fetch_array($result)) 
		{
			$rowid = $ROW['id'];
			$item_id = $ROW['item_id'];
			$item_name = $ROW['item_name'];
			
			if($item_id == 0 || $item_id == '')
			{
				$columns = 'product_name';
				$kwiri = $item_name;				
				$itemid = getItemInfo($columns,'id',$kwiri,$db);
				$itemname = getItemInfo($columns,'product_name',$kwiri,$db);
				$price_kg = getItemInfo($columns,'unit_price',$kwiri,$db);				
			}
			else if($item_id != 0 || $item_id != '')
			{
				$columns = 'id';
				$kwiri = $item_id;
				$itemid = getItemInfo($columns,'id',$kwiri,$db);
				$itemname = getItemInfo($columns,'product_name',$kwiri,$db);
				$price_kg = getItemInfo($columns,'unit_price',$kwiri,$db);
			}
			updateThisRMSUMMARY($itemid,$itemname,$price_kg,$rowid,$db);
		}
	}
}
function updateThisRMSUMMARY($itemid,$itemname,$price_kg,$rowid,$db)
{
	$queryDataUpdates = "UPDATE store_rm_summary_data SET item_id='$itemid',item_name='$itemname',price_kg='$price_kg' WHERE id='$rowid' ";
	if($db->query($queryDataUpdates) === TRUE) { echo "SUCCESS UPDATING WITH ITEM ID ::: ".$itemid." ::: ".$itemname."<br>"; }
	else
	{ echo "UPDATER ERROR::: ". $db->error; exit(); }
}
function updateThisRMPCOUNT($itemid,$itemname,$category,$rowid,$db)
{
	$queryDataUpdates = "UPDATE store_rm_pcount_data SET item_id='$itemid',item_name='$itemname',category='$category' WHERE id='$rowid' ";
	if($db->query($queryDataUpdates) === TRUE) { echo "SUCCESS UPDATING WITH ITEM ID ::: ".$itemid." ::: ".$itemname."<br>"; }
	else
	{ echo "UPDATER ERROR::: ". $db->error; exit(); }
}
function updateThisRMBADORDER($itemid,$itemname,$category,$rowid,$db)
{
	$queryDataUpdates = "UPDATE store_rm_badorder_data SET item_id='$itemid',item_name='$itemname',category='$category' WHERE id='$rowid' ";
	if($db->query($queryDataUpdates) === TRUE) { echo "SUCCESS UPDATING WITH ITEM ID ::: ".$itemid." ::: ".$itemname."<br>"; }
	else
	{ echo "UPDATER ERROR::: ". $db->error; exit(); }
}
function updateThisRMTRANSFER($itemid,$itemname,$rowid,$db)
{
	$queryDataUpdates = "UPDATE store_rm_transfer_data SET item_id='$itemid',item_name='$itemname' WHERE id='$rowid' ";
	if($db->query($queryDataUpdates) === TRUE) { echo "SUCCESS UPDATING WITH ITEM ID ::: ".$itemid." ::: ".$itemname."<br>"; }
	else
	{ echo "UPDATER ERROR::: ". $db->error; exit(); }
}
function updateThisRMRECEIVING($itemid,$itemname,$rowid,$db)
{
	$queryDataUpdates = "UPDATE store_rm_receiving_data SET item_id='$itemid',item_name='$itemname' WHERE id='$rowid' ";
	if($db->query($queryDataUpdates) === TRUE) { echo "SUCCESS UPDATING WITH ITEM ID ::: ".$itemid." ::: ".$itemname."<br>"; }
	else
	{ echo "UPDATER ERROR::: ". $db->error; exit(); }
}
/* ----------------------------- FINISH GOODS -------------------------------- */
function updateThisSUMMARY($itemid,$itemname,$standardyield,$unitprice,$rowid,$db)
{
	$queryDataUpdates = "UPDATE store_fgts_data SET item_id='$itemid',item_name='$itemname',standard_yield='$standardyield',unit_price='$unitprice' WHERE id='$rowid' ";
	if($db->query($queryDataUpdates) === TRUE) { echo "SUCCESS UPDATING WITH ITEM ID ::: ".$itemid." ::: ".$itemname."<br>"; }
	else
	{ echo "UPDATER ERROR::: ". $db->error; exit(); }
}
function updateThisPCOUNT($itemid,$itemname,$rowid,$db)
{
	$queryDataUpdates = "UPDATE store_pcount_data SET item_id='$itemid',item_name='$itemname' WHERE id='$rowid' ";
	if($db->query($queryDataUpdates) === TRUE) { echo "SUCCESS UPDATING WITH ITEM ID ::: ".$itemid." ::: ".$itemname."<br>"; }
	else
	{ echo "UPDATER ERROR::: ". $db->error; exit(); }
}
function updateThisFROZENDOUGH($itemid,$itemname,$standardyield,$unitprice,$rowid,$db)
{
	$queryDataUpdates = "UPDATE store_frozendough_data SET item_id='$itemid',item_name='$itemname',standard_yield='$standardyield',unit_price='$unitprice' WHERE id='$rowid' ";
	if($db->query($queryDataUpdates) === TRUE) { echo "SUCCESS UPDATING WITH ITEM ID ::: ".$itemid." ::: ".$itemname."<br>"; }
	else
	{ echo "UPDATER ERROR::: ". $db->error; exit(); }
}

function updateThisRECEIVING($itemid,$itemname,$rowid,$db)
{
	$queryDataUpdates = "UPDATE store_receiving_data SET item_id='$itemid',item_name='$itemname' WHERE id='$rowid' ";
	if($db->query($queryDataUpdates) === TRUE) { echo "SUCCESS UPDATING WITH ITEM ID ::: ".$itemid." ::: ".$itemname."<br>"; }
	else
	{ echo "UPDATER ERROR::: ". $db->error; exit(); }
}
function updateThisCOMPLIMENTARY($itemid,$itemname,$unitprice,$rowid,$db)
{
	$queryDataUpdates = "UPDATE store_complimentary_data SET item_id='$itemid',item_name='$itemname',unit_price='$unitprice' WHERE id='$rowid' ";
	if($db->query($queryDataUpdates) === TRUE) { echo "SUCCESS UPDATING WITH ITEM ID ::: ".$itemid." ::: ".$itemname."<br>"; }
	else
	{ echo "UPDATER ERROR::: ". $db->error; exit(); }
}
function updateThisDAMAGED($itemid,$itemname,$unitprice,$rowid,$db)
{
	$queryDataUpdates = "UPDATE store_damage_data SET item_id='$itemid',item_name='$itemname',unit_price='$unitprice' WHERE id='$rowid' ";
	if($db->query($queryDataUpdates) === TRUE) { echo "SUCCESS UPDATING WITH ITEM ID ::: ".$itemid." ::: ".$itemname."<br>"; }
	else
	{ echo "UPDATER ERROR::: ". $db->error; exit(); }
}
function updateThisBADORDER($itemid,$itemname,$unitprice,$rowid,$db)
{
	$queryDataUpdates = "UPDATE store_badorder_data SET item_id='$itemid',item_name='$itemname',unit_price='$unitprice' WHERE id='$rowid' ";
	if($db->query($queryDataUpdates) === TRUE) { echo "SUCCESS UPDATING WITH ITEM ID ::: ".$itemid." ::: ".$itemname."<br>"; }
	else
	{ echo "UPDATER ERROR::: ". $db->error; exit(); }
}
function updateThisSNACKS($itemid,$itemname,$unitprice,$rowid,$db)
{
	$queryDataUpdates = "UPDATE store_snacks_data SET item_id='$itemid',item_name='$itemname',unit_price='$unitprice' WHERE id='$rowid' ";
	if($db->query($queryDataUpdates) === TRUE) { echo "SUCCESS UPDATING WITH ITEM ID ::: ".$itemid." ::: ".$itemname."<br>"; }
	else
	{ echo "UPDATER ERROR::: ". $db->error; exit(); }
}
function updateThisCHARGES($itemid,$itemname,$unitprice,$rowid,$db)
{
	$queryDataUpdates = "UPDATE store_charges_data SET item_id='$itemid',item_name='$itemname',unit_price='$unitprice' WHERE id='$rowid' ";
	if($db->query($queryDataUpdates) === TRUE) { echo "SUCCESS UPDATING WITH ITEM ID ::: ".$itemid." ::: ".$itemname."<br>"; }
	else
	{ echo "UPDATER ERROR::: ". $db->error; exit(); }
}
function updateThisTRANSFER($itemid,$itemname,$unitprice,$rowid,$db)
{
	$queryDataUpdates = "UPDATE store_transfer_data SET item_id='$itemid',item_name='$itemname',unit_price='$unitprice' WHERE id='$rowid' ";
	if($db->query($queryDataUpdates) === TRUE) { echo "SUCCESS UPDATING WITH ITEM ID ::: ".$itemid." ::: ".$itemname."<br>"; }
	else
	{ echo "UPDATER ERROR::: ". $db->error; exit(); }
}
function updateThisFGTS($itemid,$itemname,$standardyield,$unitprice,$rowid,$db)
{
	$queryDataUpdates = "UPDATE store_fgts_data SET item_id='$itemid',item_name='$itemname',standard_yield='$standardyield',unit_price='$unitprice' WHERE id='$rowid' ";
	if($db->query($queryDataUpdates) === TRUE) { echo "SUCCESS UPDATING WITH ITEM ID ::: ".$itemid." ::: ".$itemname."<br>"; }
	else
	{ echo "UPDATER ERROR::: ". $db->error; exit(); }
}

function getItemInfo($columns,$target,$kwiri,$db)
{
	$query ="SELECT *  FROM store_items WHERE $columns='$kwiri'"; 
	$result = mysqli_query($db, $query);  
	if($result->num_rows > 0)
	{
		while($ROW = mysqli_fetch_array($result))  
		{
			return $ROW[$target];
		}
	} else {
		return 0;
	}
}



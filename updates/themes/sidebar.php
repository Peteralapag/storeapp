<?php
require '../init.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$function = new TheFunctions;
$dropdown = new DropDowns;
$branch = $function->getSession('branch');
if(isset($_SESSION['session_sidebar']))
{
	if($_SESSION['session_sidebar'] == 'fgts')
	{
		$q = "category_id='100'";
		$qb = "category_id='1100'";
	} 
	elseif($_SESSION['session_sidebar'] == 'rawmats')
	{
		$q = "category_id='102'";
		$qb = "category_id='103'";
	} else {
		$q = "category_id='100'";
		$qb = "category_id='103'";
	}
} else {
	$q = "category_id='100'";
}
?>
<div class="sidebar">
    <div class="sidebar-brand">                       
		<h3><span class="logo"><img src="images/company_logo.png" width="53px" height="37px"></span> <span class="brand">Rose Bakeshop</span></h3>
	</div>
	<div class="menubranch">			
		<div>
			<select id="shiftval" class="form-control" onchange="set_session(this.value,'session_shift')">
				<?php echo $dropdown->GetShift($_SESSION['session_shift'],$_SESSION['appstore_shifting']); ?>
			</select>
		</div>
		<div>
			<input id="dateselect" type="text" class="form-control" onchange="set_session(this.value,'session_date')" value="<?php if(!isset($_SESSION['session_date'])) { echo $function->get_date(); } else { echo $_SESSION['session_date']; }  ?>">
		</div>
	</div>
	<div class="navigation">
<ul>
<?php
$queryNav = "SELECT * FROM store_navigation WHERE active=1 AND $q ORDER BY sorting ASC";
$navResult = mysqli_query($db, $queryNav);    
if ( $navResult->num_rows > 0 ) { 
	$n=0;
    while($SBROW = mysqli_fetch_array($navResult))  
	{
		$n++;
		$page_name = $SBROW['page_name'];
		$menu_name =  $SBROW['menu_name'];
?>
				<li  style="position:relative">
					<div id="nav_div<?php echo $n; ?>" onclick="set_function('<?php echo $menu_name; ?>','<?php echo $page_name; ?>','')" data-nav="nav_div<?php echo $n; ?>">	
						<table style="width: 100%;border-collapse:collapse;white-space:nowrap" cellpadding="0" cellspacing="0">
							<tr>
								<td class="menubtnicon"><i class="<?php echo $SBROW['display_icon']." ".$SBROW['icon_color']; ?>" aria-hidden="true"></i></td>
								<td class="menubtntext"><span><?php echo $menu_name; ?></span></td>
							</tr>
						</table>
					</div>
				</li>
<?php
	}
} else {
	echo "No Button";
}
?>
			</ul>
<hr>
<ul>
<?php
$queryNavB = "SELECT * FROM store_navigation WHERE active=1 AND $qb ORDER BY sorting ASC";
$navResultB = mysqli_query($db, $queryNavB);    
if ( $navResultB->num_rows > 0 ) { 
	$m=0;
    while($SBROW = mysqli_fetch_array($navResultB))  
	{
		$m++;
		$page_name = $SBROW['page_name'];
		$menu_name =  $SBROW['menu_name'];
?>
				<li  style="position:relative">
					<div id="rmnav_div<?php echo $m; ?>" onclick="set_function('<?php echo $menu_name; ?>','<?php echo $page_name; ?>','')" data-nav="rmnav_div<?php echo $m; ?>">	
						<table style="width: 100%;border-collapse:collapse;white-space:nowrap" cellpadding="0" cellspacing="0">
							<tr>
								<td class="menubtnicon"><i class="<?php echo $SBROW['display_icon']." ".$SBROW['icon_color']; ?>" aria-hidden="true"></i></td>
								<td class="menubtntext"><span><?php echo $menu_name; ?></span></td>
							</tr>
						</table>
					</div>
				</li>
<?php
	}
} else {
	echo "No Button";
}
?>

</ul>			
<style>
.online-status {
	color: #fff;
	padding:15px;
	text-align:center;
}
#svronline {
	display:none;
}
</style>			
	</div>
	<div class="online-status" id="online">
		<span id="svronline"><i class="fa-solid fa-circle icon-color-lime"></i>&nbsp;&nbsp;&nbsp;<strong>SERVER is ONLINE</strong></span>
		<span id="svroffline"><i class="fa-solid fa-circle icon-color-red"></i>&nbsp;&nbsp;&nbsp;SERVER is OFFLINE</span>
	</div>	
	<div id="checkstats"></div>
</div>	
<script>
function set_session(value,params)
{
	$.post("./actions/set_session_process.php", { params: params, value: value },
	function(data) {
		$("#"+sessionStorage.navcount).click();
	});
}
function load_status()
{
	$.post("../actions/online_session.php", { },		
	function(status) {
		if(status == 1)
		{
			$('#svronline').show();
			$('#svroffline').hide();
		} else {
			$('#svronline').hide();
			$('#svroffline').show();
		}
	});
}
$(function()	
{	
//	load_status();
	setInterval(function(){
//	   load_status(); 
	}, 10000);
	var paget = '<?php echo $_SESSION["session_sidebar"]; ?>';
	if(paget == 'fgts')
	{
		$('#moduletitle').html("FINISH GOODS");
	}
	if(paget == 'rawmats')
	{
		$('#moduletitle').html("RAWMATS");
		set_session('2','appstore_shifting');
	}
	if(paget == 'supplies')
	{
		$('#moduletitle').html("SUPPLIES");
	}
	if(sessionStorage.getItem('navcount') !== null)
	{
		$("#"+sessionStorage.navcount).addClass('active');
		$("#"+sessionStorage.navcount).click();
	} 
	else
	{
		if(sessionStorage.subcount == null || sessionStorage.subcount == '')
		{
			$('#nav_div1').trigger('click');
			$("#nav_div1").addClass('active');
			sessionStorage.setItem("navcount","nav_div1");			
		}
	}
	$('.navigation li div').click(function()
	{
		sessionStorage.removeItem('subcount');
		var tab_id = $(this).attr('data-nav');
		sessionStorage.setItem("navcount",tab_id);
		$('.navigation li div').removeClass('active');
		$(this).addClass('active');
		$("#"+tab_id).addClass('active');	
	});
	$('#dateselect').datepicker({ dateFormat: 'yy-mm-dd' });	
	$('#shiftval').change(function()
	{
		$('#shift').val($('#shiftval').val());
	});
	$('#dateselect').change(function()
	{
		$('#date').val($('#dateselect').val());
	});
});
</script>
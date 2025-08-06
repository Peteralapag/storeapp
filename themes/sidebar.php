<?php
require '../init.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$function = new TheFunctions;
$dropdown = new DropDowns;
$branch = $function->getSession('branch');
$server = $function->GetOnlineServer('server_ip');

$conserver = $function->GetOnlineServer('server_name');
$conserver = '';

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
	}
	elseif($_SESSION['session_sidebar'] == 'supplies')
	{
		$q = "category_id='104'";
		$qb = "category_id='105'";
	}
	elseif($_SESSION['session_sidebar'] == 'scrapinventory')
	{
		$q = "category_id='108'";
		$qb = "category_id='109'";
	}
	elseif($_SESSION['session_sidebar'] == 'boinventory')
	{
		$q = "category_id='110'";
		$qb = "category_id='111'";
	}
	elseif($_SESSION['session_sidebar'] == 'documents')
	{
		$q = "category_id='106'";
		$qb = "category_id='107'";
	}
	elseif($_SESSION['session_sidebar'] == 'brrr')
	{
		$q = "category_id='100000'";
		$qb = "category_id='110000'";
	}
	else {
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
		if($menu_name != 'Submit To Server')
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
	
	<!--div class="online-status" id="online">
		<span id="svronline"><i class="fa-solid fa-circle icon-color-lime"></i>&nbsp;&nbsp;&nbsp;<strong><span style="color:lime"><?php echo $conserver?></span> is ONLINE</strong></span>
		<span id="svroffline"><i class="fa-solid fa-circle icon-color-red"></i>&nbsp;&nbsp;&nbsp;
			<?php if($_SESSION["OFFLINE_MODE"] == 1){?>
			OFFLINE MODE is ON
			<?php }else{?>
			<span style="color:red"><?php echo $conserver?> is OFFLINE</span>
			<?php }?>
		</span>
	</div-->	
	
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
	if(sessionStorage.getItem("IS_ONLINE") == 1 && sessionStorage.getItem("OFFLINE_MODE") == 0)
	{
		$('#svronline').show();
		$('#svroffline').hide();
	} 
	else if(sessionStorage.getItem("IS_ONLINE") == 0 && sessionStorage.getItem("OFFLINE_MODE") == 1)
	{
		$('#svronline').hide();
		$('#svroffline').show();
	}
	else if(sessionStorage.getItem("IS_ONLINE") == 1 && sessionStorage.getItem("OFFLINE_MODE") == 1)
	{
		$('#svronline').hide();
		$('#svroffline').show();
	}
	else if(sessionStorage.getItem("IS_ONLINE") == 0 && sessionStorage.getItem("OFFLINE_MODE") == 0)
	{
		$('#svronline').hide();
		$('#svroffline').show();
	}

}
$(function()	
{	
//	load_status();
	setInterval(function(){
//	   load_status(); 
	}, 5000);
	
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
	if(paget == 'scrapinventory')
	{
		$('#moduletitle').html("SCRAP INVENTORY");
	}
	if(paget == 'boinventory')
	{
		$('#moduletitle').html("B.O INVENTORY");
	}
	if(paget == 'documents')
	{
		$('#moduletitle').html("DOCUMETS");
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
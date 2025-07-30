<?php 
$functions = new TheFunctions;
require './includes/config.php'; 
	$local_update_no = 0;
	$array = file_get_contents("./updates/data/update.json", "r");
	$data = json_decode($array, true);
	$aray = $data;
	for ($t = 0; $t < count($aray); $t++) {	
		$local_update_no = ($aray[$t]['updateno']);
	}


?>
<div class="left-wrapper" id="leftwrapper">
</div>
<div class="main-content">
	 <header id="header">
		<ul>
			<li class="profile-hover" style="width:60px;text-align:center;position:relative" onclick="showMenu()">
				<i class="fa fa-bars"></i>
				<div class="header-sub-downdownmenu-wrapper" id="submenu"></div>
			</li>
			<li style="border:0;cursor:default">&nbsp;&nbsp;<strong><span id="pagetitle">---</span></strong></li>
			<li class="profile module-title" id="moduletitle">FGTS</li>
			<li class="profile spacer">&nbsp;</li>
			<li class="profile settings" style="position:relative" onclick="openChats()">
				<i class="fa-solid fa-comment icon-color-dodger chat-hover"></i>
				<div class="header-downdownmenu-wrapper" id="chatsdd"></div>
			</li>
			<li class="profile settings profile-hover" style="position:relative" onclick="openSettings()">
				<i class="fa-solid fa-gear"></i>
				<div class="header-downdownmenu-wrapper" style="width:320px !important" id="settingsdd"></div>
			</li>
			<li class="profile settings profile-hover" style="position:relative" onclick="profileSettings()">
				<i class="fa-solid fa-user-gear"></i>
				<div class="header-downdownmenu-wrapper" id="profiledd"></div>
			</li>
			<li class="profile user"><span><i class="fa-solid fa-user text-primary"></i></span><span class="ename">&nbsp;&nbsp;&nbsp;<strong><?php echo $functions->GetSession('encoder'); ?></strong></span></li>
		</ul>	 	
    </header>    
	<main id="main" style="position:relative">
        <div class="main-wrapper" id="mainwrapper" style="position:relative;height:100%">
        	<div class="contentform" id="contentform">Loading Please Wait...<i class="fa fa-spinner fa-spin"></i></div>
        	<div class="content-data" id="contentdata">Loading Please Wait...<i class="fa fa-spinner fa-spin"></i></div>        	
        </div>
    </main> 
	<div class="footer-text"><?php echo VERSION; ?> || Patch No. <?php echo $local_update_no; ?> || <?PHP echo $functions->GetSession('branch'); ?></div>
<!-- div id="frame" style="display:nones">asdad</div -->
</div>
<script>
$(function()
{

	window.onload = function() {
	  inactivityTime(); 
	}
	var inactivityTime = function () {
	    var time;
	    window.onload = resetTimer;
	    // DOM Events
	    document.onmousemove = resetTimer;
	    document.onkeypress = resetTimer;
	
	    function logout() {
	     //app_alert("Session Time out","You have been idle for 20 minutes. You`re about to be signed out","warning","Ok","","sessionend");
	     window.location.href='log_awt.php';
	      
	    }	
	    function resetTimer() {
	        clearTimeout(time);
	        time = setTimeout(logout, 1200000); // 20 minutes
	    }
	}	
/*	var d1 = new Date (),
	d2 = new Date ( d1 );
	d2.setMinutes ( d1.getMinutes() + 30 );
	swal("AAAAAA ", d2,"warnung");
*/
	$('#leftwrapper').load('./themes/sidebar.php');
});
function showMenu()
{
	$.post("./includes/nav_menu.php", { },
	function(data) {
		$("#submenu").html(data);
		if($("#submenu").is(":visible"))
		{
			$('#submenu').slideUp();
			$('#submenu').empty();
		} else {
			$('#submenu').slideDown();
		}
	});
}
function openChats()
{
	$.post("./chat/chat.php", { },
	function(data) {
		$("#chatsdd").html(data);
		if($("#chatsdd").is(":visible"))
		{
			$('#chatsdd').slideUp();
			$('#chatsdd').empty();
		} else {
			$('#chatsdd').slideDown();
		}
	});
}
function profileSettings()
{
	$.post("./includes/profile_submenu.php", { },
	function(data) {
		$("#profiledd").html(data);
		if($("#profiledd").is(":visible"))
		{
			$('#profiledd').slideUp();
			$('#profiledd').empty();
		} else {
			$('#profiledd').slideDown();
		}
	});
}
function profileSettings()
{
	$.post("./includes/profile_submenu.php", { },
	function(data) {
		$("#profiledd").html(data);
		if($("#profiledd").is(":visible"))
		{
			$('#profiledd').slideUp();
			$('#profiledd').empty();
		} else {
			$('#profiledd').slideDown();
		}
	});
}
function openSettings()
{
	$.post("./includes/settings_submenu.php", { },
	function(data) {
		$("#settingsdd").html(data);
		if($("#settingsdd").is(":visible"))
		{
			$('#settingsdd').slideUp();
			$('#settingsdd').empty();
		} else {
			$('#settingsdd').slideDown();
		}
	});
}
</script>
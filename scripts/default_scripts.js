function updateApps()
{
//	rms_reloaderOn('Checking Updates...');
	psaSpinnerOn();
	$.post("./updates/update_application.php", { },
	function(data) {
		$('#updateapp_page').html(data);
		$('#updateapp').show();
		psaSpinnerOff();
	});
}
function UpdateUsers()
{
	var mode = 'checkusers';
	psaSpinnerOn();
	setTimeout(function()
	{
		$.post("../updates/update_login_users.php", { mode: mode },
		function(data) {
			$('.login').html(data);						
			psaSpinnerOff();
		});
	},1000);
}
function reload_data(pagename)
{
	$.post("../includes/" + pagename + "_data.php", { pagename: pagename },
	function(data) {
		$('#contentdata').html(data);
	});
}
function set_function(menuname,pagename)
{
	psaSpinnerOn();
	
	if(pagename == 'rm_inventory_record')
	{
		$.post("../pages/rm_inventory_record_header.php", { pagename: pagename },
		function(data) {
			$('#contentform').html(data);
		});
		$('#pagetitle').html(menuname);
		$.post("../includes/rm_inventory_record_data.php", { pagename: pagename },
		function(data) {
			$('#pagetitle').html(menuname);
			$('#contentdata').html(data);
			psaSpinnerOff();
		});
	}
	
	else if(pagename == 'rm_transfer')
	{
		$.post("../pages/rm_transfer_form.php", { pagename: pagename },
		function(data) {
			$('#contentform').html(data);
		});
		$('#pagetitle').html(menuname);
		$.post("../includes/rm_transfer_data.php", { pagename: pagename },
		function(data) {
			$('#contentdata').html(data);
			psaSpinnerOff();
		});
	}
	
	else if(pagename == 'rm_receiving')
	{
		$.post("../pages/rm_content_form.php", { pagename: pagename },
		function(data) {
			$('#contentform').html(data);
		});
		$('#pagetitle').html(menuname);
		$.post("../includes/rm_receiving_data.php", { pagename: pagename },
		function(data) {
			$('#contentdata').html(data);
			psaSpinnerOff();
		});
	}
	
	else if(pagename == 'rm_pcount')
	{
		$.post("../pages/rm_content_form.php", { pagename: pagename },
		function(data) {
			$('#contentform').html(data);
		});
		$('#pagetitle').html(menuname);
		$.post("../includes/rm_pcount_data.php", { pagename: pagename },
		function(data) {
			$('#contentdata').html(data);
			psaSpinnerOff();
		});
	}

	else if(pagename == 'rm_badorder')
	{
		$.post("../pages/rm_content_form.php", { pagename: pagename },
		function(data) {
			$('#contentform').html(data);
		});
		$('#pagetitle').html(menuname);
		$.post("../includes/rm_badorder_data.php", { pagename: pagename },
		function(data) {
			$('#contentdata').html(data);
			psaSpinnerOff();
		});
	}
	

	else if(pagename == 'brrr_submitserver')
	{
		$.post("../pages/brrr_submitserver_header.php", { pagename: pagename },
		function(data) {
			$('#contentform').html(data);
		});
		$('#pagetitle').html(menuname);
		$.post("../includes/brrr_submitserver.php", { pagename: pagename },
		function(data) {
			$('#contentdata').html(data);
			psaSpinnerOff();
		});
	}

	
	else if(pagename == 'brrr_summary_ho')
	{
		$.post("../pages/brrr_summary_ho_header.php", { pagename: pagename },
		function(data) {
			$('#contentform').html(data);
		});
		$('#pagetitle').html(menuname);
		$.post("../includes/brrr_summary_ho_data.php", { pagename: pagename },
		function(data) {
			$('#pagetitle').html(menuname);
			$('#contentdata').html(data);
			psaSpinnerOff();
		});
	}
	
	else if(pagename == 'brrr_summary')
	{
		$.post("../pages/brrr_summary_header.php", { pagename: pagename },
		function(data) {
			$('#contentform').html(data);
		});
		$('#pagetitle').html(menuname);
		$.post("../includes/brrr_summary_data.php", { pagename: pagename },
		function(data) {
			$('#pagetitle').html(menuname);
			$('#contentdata').html(data);
			psaSpinnerOff();
		});
	}

	
	else if(pagename == 'expenses_input')
	{
		$.post("../pages/expenses_input_header.php", { pagename: pagename },
		function(data) {
			$('#contentform').html(data);
		});
		$('#pagetitle').html(menuname);
		$.post("../includes/expenses_input_data.php", { pagename: pagename },
		function(data) {
			$('#pagetitle').html(menuname);
			$('#contentdata').html(data);
			psaSpinnerOff();
		});
	}

	
	else if(pagename == 'overheadcount')
	{
		$.post("../pages/overheadcount_header.php", { pagename: pagename },
		function(data) {
			$('#contentform').html(data);
		});
		$('#pagetitle').html(menuname);
		$.post("../includes/overheadcount_data.php", { pagename: pagename },
		function(data) {
			$('#pagetitle').html(menuname);
			$('#contentdata').html(data);
			psaSpinnerOff();
		});
	}
	
	else if(pagename == 'brrr')
	{
		$.post("../pages/brrr_summary_header.php", { pagename: pagename },
		function(data) {
			$('#contentform').html(data);
		});
		$('#pagetitle').html(menuname);
		$.post("../includes/brrr_summary_data.php", { pagename: pagename },
		function(data) {
			$('#pagetitle').html(menuname);
			$('#contentdata').html(data);
			psaSpinnerOff();
		});
	}






	else if(pagename == 'inventory_record')
	{
		$.post("../pages/inventory_record_header.php", { pagename: pagename },
		function(data) {
			$('#contentform').html(data);
		});
		$('#pagetitle').html(menuname);
		$.post("../includes/inventory_record_data.php", { pagename: pagename },
		function(data) {
			$('#pagetitle').html(menuname);
			$('#contentdata').html(data);
			psaSpinnerOff();
		});
	}

	else if(pagename == 'cashcount')
	{

		$.post("../pages/cashcount_header.php", { pagename: pagename },
		function(data) {
			$('#contentform').html(data);
		});
		$('#pagetitle').html(menuname);
		$.post("../includes/cashcount_data.php", { pagename: pagename },
		function(data) {
			$('#pagetitle').html(menuname);
			$('#contentdata').html(data);
			psaSpinnerOff();
		});
	}
	else if(pagename == 'salesinv_cashcount')
	{
		$.post("../pages/salesinv_cashcount_header.php", { pagename: pagename },
		function(data) {
			$('#contentform').html(data);
		});
		$('#pagetitle').html(menuname);
		$.post("../includes/salesinv_cashcount_data.php", { pagename: pagename },
		function(data) {
			$('#pagetitle').html(menuname);
			$('#contentdata').html(data);
			psaSpinnerOff();
		});
	}

	else if(pagename == 'boinventory_submitserver')
	{
		$.post("../pages/boinventory_submitserver_header.php", { pagename: pagename },
		function(data) {
			$('#contentform').html(data);
		});
		$('#pagetitle').html(menuname);
		$.post("../includes/boinventory_submitserver.php", { pagename: pagename },
		function(data) {
			$('#pagetitle').html(menuname);
			$('#contentdata').html(data);
			psaSpinnerOff();
		});
	}
	else if(pagename == 'boinventory_inventory')
	{
		$.post("../pages/boinventory_inventory_header.php", { pagename: pagename },
		function(data) {
			$('#contentform').html(data);
		});
		$('#pagetitle').html(menuname);
		$.post("../includes/boinventory_inventory_data.php", { pagename: pagename },
		function(data) {
			$('#contentdata').html(data);
			psaSpinnerOff();
		});
	}
    else if(pagename == 'boinventory_summary')
	{
		$.post("../pages/boinventory_summary_header.php", { pagename: pagename },
		function(data) {
			$('#contentform').html(data);
		});
		$('#pagetitle').html(menuname);
		$.post("../includes/boinventory_summary_data.php", { pagename: pagename },
		function(data) {
			$('#contentdata').html(data);
			psaSpinnerOff();
		});
	}
	else if(pagename == 'scrapinventory_submitserver')
	{
		$.post("../pages/scrapinventory_submitserver_header.php", { pagename: pagename },
		function(data) {
			$('#contentform').html(data);
		});
		$('#pagetitle').html(menuname);
		$.post("../includes/scrapinventory_submitserver.php", { pagename: pagename },
		function(data) {
			$('#pagetitle').html(menuname);
			$('#contentdata').html(data);
			psaSpinnerOff();
		});
	}
	else if(pagename == 'scrapinventory_inventory')
	{
		$.post("../pages/scrapinventory_inventory_header.php", { pagename: pagename },
		function(data) {
			$('#contentform').html(data);
		});
		$('#pagetitle').html(menuname);
		$.post("../includes/scrapinventory_inventory_data.php", { pagename: pagename },
		function(data) {
			$('#contentdata').html(data);
			psaSpinnerOff();
		});
	}
    else if(pagename == 'scrapinventory_summary')
	{
		$.post("../pages/scrapinventory_summary_header.php", { pagename: pagename },
		function(data) {
			$('#contentform').html(data);
		});
		$('#pagetitle').html(menuname);
		$.post("../includes/scrapinventory_summary_data.php", { pagename: pagename },
		function(data) {
			$('#contentdata').html(data);
			psaSpinnerOff();
		});
	}

	else if(pagename == 'supplies_summary')
	{
		$.post("../pages/supplies_summary_header.php", { pagename: pagename },
		function(data) {
			$('#contentform').html(data);
		});
		$('#pagetitle').html(menuname);
		$.post("../includes/supplies_summary_data.php", { pagename: pagename },
		function(data) {
			$('#contentdata').html(data);
			psaSpinnerOff();
		});
	}

	else if(pagename == 'supplies_inventory')
	{
		$.post("../pages/supplies_inventory_header.php", { pagename: pagename },
		function(data) {
			$('#contentform').html(data);
		});
		$('#pagetitle').html(menuname);
		$.post("../includes/supplies_inventory_data.php", { pagename: pagename },
		function(data) {
			$('#contentdata').html(data);
			psaSpinnerOff();
		});
	}
	else if(pagename == 'supplies_submitserver')
	{
		$.post("../pages/supplies_submitserver_header.php", { pagename: pagename },
		function(data) {
			$('#contentform').html(data);
		});
		$('#pagetitle').html(menuname);
		$.post("../includes/supplies_submitserver.php", { pagename: pagename },
		function(data) {
			$('#pagetitle').html(menuname);
			$('#contentdata').html(data);
			psaSpinnerOff();
		});
	}
	else if(pagename == 'documents')
	{
		$('#pagetitle').html(menuname);
		$.post("../pages/documents_form.php", { pagename: pagename },
		function(data) {
			$('#contentform').html(data);
		});
		$.post("../includes/documents_data.php", { pagename: pagename },		
		function(data) {
			$('#contentdata').html(data);
			psaSpinnerOff();
		});
	}
	else if(pagename == 'sales_breakdown')
	{
		$('#pagetitle').html(menuname);
		$.post("../pages/sales_breakdown_header.php", { pagename: pagename },
		function(data) {
			$('#contentform').html(data);
		});
		$.post("../includes/sales_breakdown_data.php", { pagename: pagename },
		function(data) {
			$('#contentdata').html(data);
			psaSpinnerOff();
		});
	}
	else if(pagename == 'sales_breakdown2')
	{
		$('#pagetitle').html(menuname);
		$.post("../pages/sales_breakdown2_header.php", { pagename: pagename },
		function(data) {
			$('#contentform').html(data);
		});
		$.post("../includes/sales_breakdown2_data.php", { pagename: pagename },
		function(data) {
			$('#contentdata').html(data);
			psaSpinnerOff();
		});
	}
	else if(pagename == 'bo_breakdown')
	{
		$('#pagetitle').html(menuname);
		$.post("../pages/bo_breakdown_header.php", { pagename: pagename },
		function(data) {
			$('#contentform').html(data);
		});
		$.post("../includes/bo_breakdown_data.php", { pagename: pagename },
		function(data) {
			$('#contentdata').html(data);
			psaSpinnerOff();
		});
	}

	else if(pagename == 'dashboard')
	{
		$('#pagetitle').html(menuname);
		$.post("../pages/dashboard_header.php", { pagename: pagename },
		function(data) {
			$('#contentform').html(data);
		});
		$.post("../pages/dashboard.php", { pagename: pagename },		
		function(data) {
			$('#contentdata').html(data);
			psaSpinnerOff();
		});
	} 
	else if(pagename == 'production')
	{
		$('#pagetitle').html(menuname);
		$.post("../pages/production_header.php", { pagename: pagename },
		function(data) {
			$('#contentform').html(data);
		});
		$.post("../includes/production_data.php", { pagename: pagename },
		function(data) {
			$('#contentdata').html(data);
			psaSpinnerOff();
		});
	}
	else if(pagename == 'summary')
	{
		$.post("../pages/summary_header.php", { pagename: pagename },
		function(data) {
			$('#contentform').html(data);
		});
		$('#pagetitle').html(menuname);
		$.post("../includes/summary_data.php", { pagename: pagename },
		function(data) {
			$('#contentdata').html(data);
			psaSpinnerOff();
		});
	}
	else if(pagename == 'inventory')
	{
		$.post("../pages/inventory_header.php", { pagename: pagename },
		function(data) {
			$('#contentform').html(data);
		});
		$('#pagetitle').html(menuname);
		$.post("../includes/inventory_data.php", { pagename: pagename },
		function(data) {
			$('#contentdata').html(data);
			psaSpinnerOff();
		});
	}
	else if(pagename == 'rm_summary')
	{
		$.post("../pages/rm_summary_header.php", { pagename: pagename },
		function(data) {
			$('#contentform').html(data);
		});
		$('#pagetitle').html(menuname);
		$.post("../includes/rm_summary_data.php", { pagename: pagename },
		function(data) {
			$('#contentdata').html(data);
			psaSpinnerOff();
		});
	}
	else if(pagename == 'rm_inventory')
	{
		$.post("../pages/rm_inventory_header.php", { pagename: pagename },
		function(data) {
			$('#contentform').html(data);
		});
		$('#pagetitle').html(menuname);
		$.post("../includes/rm_inventory_data.php", { pagename: pagename },
		function(data) {
			$('#contentdata').html(data);
			psaSpinnerOff();
		});
	}
	else if(pagename == 'dum')
	{
		$.post("../pages/dum_header.php", { pagename: pagename },
		function(data) {
			$('#contentform').html(data);
		});
		$('#pagetitle').html(menuname);
		$.post("../includes/dum_data.php", { pagename: pagename },
		function(data) {
			$('#contentdata').html(data);
			psaSpinnerOff();
		});
	} 
	else if(pagename == 'submitserver')
	{
		$.post("../pages/submitserver_header.php", { pagename: pagename },
		function(data) {
			$('#contentform').html(data);
		});
		$('#pagetitle').html(menuname);
		$.post("../includes/submitserver.php", { pagename: pagename },
		function(data) {
			$('#contentdata').html(data);
			psaSpinnerOff();
		});
	}
	else if(pagename == 'rm_submitserver')
	{
		$.post("../pages/rm_submitserver_header.php", { pagename: pagename },
		function(data) {
			$('#contentform').html(data);
		});
		$('#pagetitle').html(menuname);
		$.post("../includes/rm_submitserver.php", { pagename: pagename },
		function(data) {
			$('#pagetitle').html(menuname);
			$('#contentdata').html(data);
			psaSpinnerOff();
		});
	}
	else if(pagename == 'build_assembly')
	{
		$.post("../pages/build_assembly_header.php", { pagename: pagename },
		function(data) {
			$('#contentform').html(data);
		});
		$('#pagetitle').html(menuname);
		$.post("../includes/build_assembly_data.php", { pagename: pagename },
		function(data) {
			$('#pagetitle').html(menuname);
			$('#contentdata').html(data);
			psaSpinnerOff();
		});
	}
	else if(pagename == 'bakers_guide')
	{
		$.post("../build_assembly/bakers_guide_header.php", { pagename: pagename },
		function(data) {
			$('#contentform').html(data);
		});
		$('#pagetitle').html(menuname);
		$.post("../build_assembly/bakers_guide.php", { pagename: pagename },
		function(data) {
			$('#pagetitle').html(menuname);
			$('#contentdata').html(data);
			psaSpinnerOff();
		});
	} else {
		$('#pagetitle').html(menuname);
		
		if(pagename == 'fgts' || pagename == 'transfer' || pagename == 'receiving'){
			$.post("../pages/content_form2.php", { pagename: pagename },
			function(data) {
				$('#contentform').html(data);
				psaSpinnerOff();
			});

		} else {
		
			$.post("../pages/content_form.php", { pagename: pagename },
			function(data) {
				$('#contentform').html(data);
				psaSpinnerOff();
			});
		}
		
		
		
			
		$.post("../includes/" + pagename + "_data.php", { pagename: pagename },
		function(data) {
			$('#contentdata').html(data);
			psaSpinnerOff();
		});
	}
			
}
function location_reload()
{
	location.reload();
}
function app_confirm(dialogtitle,dialogmsg,dialogicon,command,params,btncolor)
{
	if(btncolor == null || btncolor == '') 
	{
		var btncolor = '';
	} else {
		var btncolor = btncolor;
	}
	swal({
		title: dialogtitle,
		text: dialogmsg,
		icon: dialogicon,
		buttons: [
		'No',
		'Yes'
		],
		dangerMode: btncolor,
	}).then(function(isConfirm) {
		if (isConfirm) {
			if(command == 'signingout')
			{					
				window.location.href = 'log_awt.php';
			}
			if(command == 'signingout')
			{					
				window.location.href = 'log_awt.php';
			}
			if(command == 'fgts' || command == 'transfer' || command == 'charges' || command == 'snacks' || command == 'badorder' || command == 'damage' || command == 'receiving' ||
			 command == 'cashcount' || command == 'frozendough'|| command == 'pcount' || command == 'discount' || command == 'complimentary' || command == 'gcash' || 
			 command == 'grab' || command == 'foodpanda' || command == 'pakati' || command == 'rm_receiving' || command == 'rm_transfer' || command == 'rm_badorder' || command == 'rm_pcount' ||
			 command == 'rm_summary' || command == 'supplies_receiving' || command == 'supplies_transfer' || command == 'supplies_badorder' || command == 'supplies_pcount' ||
			 command == 'scrapinventory_receiving' || command == 'scrapinventory_transfer' || command == 'scrapinventory_badorder' || command == 'scrapinventory_pcount' ||
			 command == 'boinventory_receiving' || command == 'boinventory_transfer' || command == 'boinventory_badorder' || command == 'boinventory_pcount' || command == 'inventory_record')
			{					
				deleteItemYes(params,command);
			}
			if(command == 'deletesumitemyes')
			{					
				deleteSumItemYes(params);
			}
			if(command == 'deletedumreport')
			{					
				reGenerateDUMYes(params);
			}
			if(command == 'delete_sumari')
			{					
				delete_sumariYes();
			}
			if(command == 'delete_rmsumari')
			{					
				delete_rmsumariYes();
			}
			if(command == 'deleteServerDataYes')
			{					
				deleteServerDataYes(params);
			}
			if(command == 'deleteDumItemIndividual')
			{					
				deleteDumItemIndividual(params);
			}
			if(command=='postItemModule'){
				postToSummary(params)
			}
			if(command == 'yesmanualinputname'){
				yesmanualinputname();
			}

		} else {
		// swal("Cancelled", "Your imaginary file is safe :)", "error");
		}
	})
}
function app_alert(p_title,p_text,p_icon,p_button_text,aydi,command)
{
	swal({
		title: p_title,
		text: p_text + "!",
		icon: p_icon,
		button: p_button_text,
	}).then(function()	{
		if(command == 'focus')
		{
			if(aydi != '')
			{				
				document.getElementById(aydi).style.background = '#fdeaf8';
				document.getElementById(aydi).focus();
				// $('#'+aydi).trigger('click');
			}
		}
		else if(command == 'relogin')
		{
			relogin();
		}
		else if(command == 'yes')
		{
			$('.page-loader-bd').show();
			closeMsg();
		}
		else if(command == 'settheme')
		{
			location.reload(true);
		}
		else if(command == 'errorItemName')
		{
			document.getElementById(aydi).focus();
			
		}

	});		
}
function signOut()
{
	app_confirm("Signing Out","Are you sure to sign-out?","warning","signingout","","true")
	return false;
}
function logout() {
    app_alert("Session Time out","You have been idle for 20 minutes. You`re about to be signed out","warning","Ok","","sessionend");
}	
function resetTimer() {
    clearTimeout(time);
    time = setTimeout(logout, 1200000); // 10 minutes
}

function closeMsg()
{
	setTimeout(function(){
		window.location.reload(400);
	}, 2000);
}
function relogin()
{
	window.location.href = 'log_awt.php';
}
function showError()
{
	swal("Item Error","Possible Item ID is not present to the data. To fix this please unlock and delete the item and Add again");
}
$(function()
{
	$(window).resize(function()
	{
		 $("#downdownmenuwrapper,#settingsdd,#profiledd").slideUp();
	});
});
$(document).mouseup(function (e) {
     var popup = $("#downdownmenuwrapper,#settingsdd,#profiledd");
     if (!$('#downdownmenuwrapper,#settingsdd,#profiledd').is(e.target) && !popup.is(e.target) && popup.has(e.target).length == 0) {
         $("#downdownmenuwrapper,#settingsdd,#profiledd").slideUp();
     }
});
function ronanReload()
{
	$('#' + sessionStorage.navcount).trigger('click');
}

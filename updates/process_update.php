<?php 
	include '../includes/config.php';

	$fileko = $_POST['fileko'];
	$localko = $_POST['localko'];
	$dateko = date('Y-m-d');
	
	if($localko < $fileko)
	{
		for($n = $localko; $n < $fileko + 1; $n++)
		{
			$download_url = $download_site."/".$n.".zip";
			$handle = @fopen($download_url, 'r');
			if($handle)
			{				
				$file = "../temp/".$n.".zip";
				$script = basename($_SERVER['PHP_SELF']);
				file_put_contents($file, fopen($download_url, 'r'));
			}			
			if($fileko == $n)
			{
				print_r('
					<script>
						var localko = '.$localko.';
						var fileko = '.$fileko.';
						var dateko = '.$dateko.';
						$("#downloading").empty();
						$("#downloading").html("<p><i class=\"fa fa-check\" style=\"font-size:14px;margin-right:10px;color:green\"></i> Downloaded </p>");
						$("#installing").html("<p><i class=\"fa fa-spinner fa-spin\" style=\"font-size:14px;margin-right:10px;color:green\"></i> Installing updates... </p>");
						installUpdates(fileko,localko,dateko);
					</script>
				');				
			}
		}
	} else {
		echo "Not Updating";
	}
?>

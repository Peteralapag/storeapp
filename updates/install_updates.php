<?php
include '../includes/config.php';
$remote_no_ = $_POST['fileko'];
$local_no_ = $_POST['localko'];
$dateko = date('Y-m-d');
$z=0;
$remote_no = 2;

$path = "../";
$files = glob("../temp/*.zip");
$z=0;
$data = array();
foreach ($files as $updates)
{
	$z++;
	$zip = new ZipArchive;
	$res = $zip->open($updates);
	if ($res === TRUE)
	{
		$zip->extractTo($path);
		$zip->close();
	} else {
		echo "Something went wrong!";
	}	
}
array_push($data, [
	'filename'=>$remote_no_,
	'updateno'=>$remote_no_,
	'lastdate'=>$dateko
]);		
$array = json_encode($data);
saveJson($array,$remote_no_,$local_no_,$delete_file);
function saveJson($array,$remote_no_,$local_no_,$delete_file)
{	
	delete_zip($delete_file);
	$myfile = fopen("../updates/data/update.json", "w") or die("Unable to open file!");
	$txt = $array;
	fwrite($myfile, $txt);
	fclose($myfile);	
	print_r('
		<script>
			$("#installing").html("<p><i class=\"fa fa-check\" style=\"font-size:14px;margin-right:10px;color:green\"></i> Update Finished!</p>");
			$("#finishedbtn").show();
		</script>
	');	
}
function delete_zip($delete_file)
{
	if($delete_file == 'yes')
	{
		$files = glob('../temp/*.zip');
		foreach($files as $filez)
		{
			if(is_file($filez))
			{
				unlink($filez);
			}
		}

	}
}
?>




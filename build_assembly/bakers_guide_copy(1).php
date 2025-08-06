<?php
include '../init.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$classba = new BuildAssembly;
$trans_date = $functions->GetSession('branchdate');
$store_branch = $functions->AppBranch();
$file_name = $_POST['pagename'];
$title = strtoupper($file_name);

if(isset($_POST['search']))
{
	$item_name = $_POST['search'];
	$shift = $_SESSION['session_shift'];
	$q = "WHERE report_date='$trans_date' AND shift='$shift' AND branch='$store_branch' AND item_name LIKE '%$item_name%'";
} 
else
{
	if(isset($_SESSION['session_shift'])) 
	{
		$shift = $_SESSION['session_shift'];
		$q = "WHERE report_date='$trans_date' AND shift='$shift' AND branch='$store_branch'";
	} else {
		$shift = '';
		$q = "WHERE report_date='$trans_date' AND branch='$store_branch'";
	}
}
?>
<style>
.table-ba th, .table-ba td {
	padding: 5px 5px 5px 5px;
	border:1px solid #cecece
}
.table-ba th {
	background: dodgerblue;
	color: #fff;
}
.selected {
	color: #fff;
	background: #7ed1fc;
	font-weight:bold;
}
.table-data th {
	background: #d1d1d1;
	color: #232323;
}
.table-data td {
	background: #f1f1f1;
}
.table-data {
	margin-bottom:0 !important;
}
</style>
<table style="width: 100%; border-collapse: collapse !important" class="table-ba">
    <thead>
        <tr>
            <th style="text-align: center; width: 50px">#</th>
            <th>PRODUCT NAME</th>
            <th style="width: 123px;">ITEM CODE</th>
            <th style="width: 123px">YIELDING</th>
            <th style="width: 123px">UNIT PRICE</th>
            <th style="width:50px"></th>
        </tr>
    </thead>
    <tbody>
<?php
$query = "SELECT * FROM store_fgts_data $q ORDER BY id DESC";
$result = mysqli_query($db, $query);
if ($result->num_rows > 0) {
    $x = 0;
    while ($ROWS = mysqli_fetch_array($result))
    {
        $x++;
        $rowid = $ROWS['id'];
        $item_name = $ROWS['item_name'];
        $item_id = $ROWS['item_id'];
        $unit_price = $ROWS['unit_price'];
        $item_code = $classba->GetItemCode($item_id, $db);
        $yielding = $classba->GetYielding($item_id, $db);
        ?>
                <tr class="mats-items" data-target=".mats-data-<?php echo $x ?>">
                    <td style="text-align: center; font-weight: 600"><?php echo $x ?></td>
                    <td><?php echo $item_name ?></td>
                    <td style="text-align: center"><?php echo $item_code ?></td>
                    <td style="text-align: center"><?php echo $yielding ?></td>
                    <td style="text-align: right; padding-right: 10px;"><?php echo $unit_price ?></td>
                    <td style="text-align:center"><i class="fa-solid fa-caret-down"></i></td>
                </tr>
                <tr class="mats-data mats-data-<?php echo $x ?>" style="display: none;">
                    <td style="border-bottom: 0 !important; border-left: 0 !important"></td>
                    <td colspan="4" style="background:#a3dffe;padding:10px">  
<!-- ############################################################################################## -->                                      	
	                    <table style="width: 100%" class="table table-bordered table-data">
							<tr>
								<th style="width:50px;text-align:center">#</th>
								<th>RAWMATS</th>
								<th>DOUGH</th>
								<th>FILLINGS</th>
								<th>TOPPINGS</th>
								<th>COATING</th>
								<th>TOTAL</th>
							</tr>
					<?php
						$queryMats = "SELECT * FROM store_bakers_guide WHERE itemcode='$item_code'";
						$Matsresult = mysqli_query($db, $queryMats);
						
						$rows = [];
						while ($DATAROWS = mysqli_fetch_array($Matsresult))
						{
						    $rows[] = $DATAROWS;
						}								
						
						if (empty($rows))
						{
					?>
							<tr>
								<td colspan="7" style="text-align:center"><i class="fa fa-bell"></i>&nbsp;&nbsp;No Records</td>
							</tr>
					<?php } else {
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
					?>
							<tr>
								<td style="text-align:center;font-weight:600"><?php echo $alphabetIndex?></td>
								<td><?php echo $rawmats_name?></td>
								<td style="text-align:right; padding-right:10px;"><?php echo $dough?></td>
								<td style="text-align:right; padding-right:10px;"><?php echo $fillings?></td>
								<td style="text-align:right; padding-right:10px;"><?php echo $toppings?></td>
								<td style="text-align:right; padding-right:10px;"><?php echo $coating?></td>
								<td style="text-align:right; padding-right:10px;"><?php echo $total_mats?></td>
							</tr>
					<?php } } ?>
						</table>                    	
<!-- ############################################################################################## -->                                      							
                    </td>
                </tr>
<?php } } else { ?>
            <tr>
                <td colspan="4" style="text-align: center">
                    <i class="fa fa-bell color-orange"></i>&nbsp;&nbsp;No Records
                </td>
            </tr>
<?php } ?>
    </tbody>
</table>
<script>
    $(document).ready(function() {
        $('.mats-items').dblclick(function() {
            var target = $(this).data('target');
            var caretIcon = $(this).find('.fa-caret-down');
            $('.mats-items').not($(this)).find('.fa-caret-up').removeClass('fa-caret-up').addClass('fa-caret-down');
            $('.mats-items').not($(this)).removeClass('selected');
            $(this).toggleClass('selected');
            $('.mats-data').not(target).hide();
            $(target).slideToggle(function() {
                if ($(this).is(':visible')) {
                    caretIcon.removeClass('fa-caret-down').addClass('fa-caret-up');
                } else {
                    caretIcon.removeClass('fa-caret-up').addClass('fa-caret-down');
                }
            });
        });
    });
</script>
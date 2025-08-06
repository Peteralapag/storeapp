<?php
include '../init.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);	
$functions = new TheFunctions;
$branch = $functions->AppBranch();
if(isset($_SESSION['appstore_month'])) { $months = $_SESSION['appstore_month']; $year = $_SESSION['appstore_year']; } else { $months = date("F");$year = date("y"); }
$monthnum = $functions->GetMonthNumber($months);
?>
<style>
.total-wrapper td {
	border-top:3px solid #aeaeae;
	font-weight:bold;
	text-align:center;
}
.no-data {
	text-align:center;
	font-weight:bold;
}
.tbody tr:nth-child(odd) td {
	background:#fef1eb;
	text-align:center;
	font-size:12px;
}
.tbody tr:nth-child(even) td {
  	background:#ffe3d5;
	text-align:center;
	font-size:12px;
}
</style>
<div class="tableFixHeadPROD">
<table style="width: 100%" class="table table-hover table-striped table-bordered">
	<thead>
		<tr>
			<th style="width:50px;text-align:center">#</th>
			<th style="width:250px;">ITEM NAME</th>
			<th>01</th>
			<th>02</th>
			<th>03</th>
			<th>04</th>
			<th>05</th>
			<th>06</th>
			<th>07</th>
			<th>08</th>
			<th>09</th>
			<th>10</th>
			<th>11</th>
			<th>12</th>
			<th>13</th>
			<th>14</th>
			<th>15</th>
			<th>16</th>
			<th>17</th>
			<th>18</th>
			<th>19</th>
			<th>20</th>
			<th>21</th>
			<th>22</th>
			<th>23</th>
			<th>24</th>
			<th>25</th>
			<th>26</th>
			<th>27</th>
			<th>28</th>
			<th>29</th>
			<th>30</th>
			<th>31</th>
			<th>TOTAL</th>
		</tr>
	</thead>
<?php
	$a=0;$b=0;$c=0;$d=0;$e=0;$f=0;$g=0;$h=0;$i=0;$j=0;$k=0;
	$l=0;$m=0;$n=0;$o=0;$p=0;$q=0;$r=0;$s=0;$t=0;$u=0;$v=0;$norec=0;
	$w=0;$x=0;$y=0;$z=0;$aa=0;$bb=0;$cc=0;$dd=0;$ee=0;$total_v=0;
	$queryMonth ="SELECT * FROM store_production_data WHERE branch='$branch' AND month='$monthnum' AND year='$year'";
	$monthResult = mysqli_query($db, $queryMonth);  
	if($monthResult->num_rows > 0)
	{
		$cnt = 1;
		$i=0;
		while($ROWS = mysqli_fetch_array($monthResult))  
		{
			$a+=$ROWS['01'];$b+=$ROWS['02'];$c+=$ROWS['03'];$d+=$ROWS['04'];$e+=$ROWS['05'];$f+=$ROWS['06'];$g+=$ROWS['07'];$h+=$ROWS['08'];$i+=$ROWS['09'];$j+=$ROWS['10'];$k+=$ROWS['11'];
			$l+=$ROWS['12'];$m+=$ROWS['13'];$n+=$ROWS['14'];$o+=$ROWS['15'];$p+=$ROWS['16'];$q+=$ROWS['17'];$r+=$ROWS['18'];$s+=$ROWS['19'];$t+=$ROWS['20'];$u+=$ROWS['21'];$v+=$ROWS['22'];
			$w+=$ROWS['23'];$x+=$ROWS['24'];$y+=$ROWS['25'];$z+=$ROWS['26'];$aa+=$ROWS['27'];$bb+=$ROWS['28'];$cc+=$ROWS['29'];$dd+=$ROWS['30'];$ee+=$ROWS['31'];
			$total_v += $ROWS['total_count'];
?>
	<tbody>
	<tr class="alternate-color">
		<td><?php echo $cnt; ?></td>
		<td style="text-align:left;width:250px"><?php echo $ROWS['item_name']; ?></td>
		<td><?php if($ROWS['01'] == 0.00) { echo '--'; } else { echo $ROWS['01']; } ?></td>
		<td><?php if($ROWS['02'] == 0.00) { echo '--'; } else { echo $ROWS['02']; } ?></td>
		<td><?php if($ROWS['03'] == 0.00) { echo '--'; } else { echo $ROWS['03']; } ?></td>
		<td><?php if($ROWS['04'] == 0.00) { echo '--'; } else { echo $ROWS['04']; } ?></td>
		<td><?php if($ROWS['05'] == 0.00) { echo '--'; } else { echo $ROWS['05']; } ?></td>
		<td><?php if($ROWS['06'] == 0.00) { echo '--'; } else { echo $ROWS['06']; } ?></td>
		<td><?php if($ROWS['07'] == 0.00) { echo '--'; } else { echo $ROWS['07']; } ?></td>
		<td><?php if($ROWS['08'] == 0.00) { echo '--'; } else { echo $ROWS['08']; } ?></td>
		<td><?php if($ROWS['09'] == 0.00) { echo '--'; } else { echo $ROWS['09']; } ?></td>
		<td><?php if($ROWS['10'] == 0.00) { echo '--'; } else { echo $ROWS['10']; } ?></td>
		<td><?php if($ROWS['11'] == 0.00) { echo '--'; } else { echo $ROWS['11']; } ?></td>
		<td><?php if($ROWS['12'] == 0.00) { echo '--'; } else { echo $ROWS['12']; } ?></td>
		<td><?php if($ROWS['13'] == 0.00) { echo '--'; } else { echo $ROWS['13']; } ?></td>
		<td><?php if($ROWS['14'] == 0.00) { echo '--'; } else { echo $ROWS['14']; } ?></td>
		<td><?php if($ROWS['15'] == 0.00) { echo '--'; } else { echo $ROWS['15']; } ?></td>
		<td><?php if($ROWS['16'] == 0.00) { echo '--'; } else { echo $ROWS['16']; } ?></td>
		<td><?php if($ROWS['17'] == 0.00) { echo '--'; } else { echo $ROWS['17']; } ?></td>
		<td><?php if($ROWS['18'] == 0.00) { echo '--'; } else { echo $ROWS['18']; } ?></td>
		<td><?php if($ROWS['19'] == 0.00) { echo '--'; } else { echo $ROWS['19']; } ?></td>
		<td><?php if($ROWS['20'] == 0.00) { echo '--'; } else { echo $ROWS['20']; } ?></td>
		<td><?php if($ROWS['21'] == 0.00) { echo '--'; } else { echo $ROWS['21']; } ?></td>
		<td><?php if($ROWS['22'] == 0.00) { echo '--'; } else { echo $ROWS['22']; } ?></td>
		<td><?php if($ROWS['23'] == 0.00) { echo '--'; } else { echo $ROWS['23']; } ?></td>
		<td><?php if($ROWS['24'] == 0.00) { echo '--'; } else { echo $ROWS['24']; } ?></td>
		<td><?php if($ROWS['25'] == 0.00) { echo '--'; } else { echo $ROWS['25']; } ?></td>
		<td><?php if($ROWS['26'] == 0.00) { echo '--'; } else { echo $ROWS['26']; } ?></td>
		<td><?php if($ROWS['27'] == 0.00) { echo '--'; } else { echo $ROWS['27']; } ?></td>
		<td><?php if($ROWS['28'] == 0.00) { echo '--'; } else { echo $ROWS['28']; } ?></td>
		<td><?php if($ROWS['29'] == 0.00) { echo '--'; } else { echo $ROWS['29']; } ?></td>
		<td><?php if($ROWS['30'] == 0.00) { echo '--'; } else { echo $ROWS['30']; } ?></td>
		<td><?php if($ROWS['31'] == 0.00) { echo '--'; } else { echo $ROWS['31']; } ?></td>
		<td style="text-align:right;font-weight:bold"><?php echo $ROWS['total_count']; ?></td>
	</tr>
	</tbody>
<?php $cnt++; } } else { $norec = 1; ?>
	<tbody>
	<tr>
		<td>&nbsp;</td>
		<td style="text-align:left;width:250px">
			<div class="no-data">No Records <i class="fa fa-bell"></i></div>
		</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	</tbody>	
<?php	}  ?>
	<tfoot>	
	<tr class="total-wrappers">
		<td style="text-align:center">*</td>
		<td style="text-align:left;width:250px">
			<div class="no-data">TOTAL </div>
		</td>
		<td><?php echo $a; ?></td>
		<td><?php echo $b; ?></td>
		<td><?php echo $c; ?></td>
		<td><?php echo $d; ?></td>
		<td><?php echo $e; ?></td>
		<td><?php echo $f; ?></td>
		<td><?php echo $g; ?></td>
		<td><?php echo $h; ?></td>
		<td><?php echo $i; ?></td>
		<td><?php echo $j; ?></td>
		<td><?php echo $k; ?></td>
		<td><?php echo $l; ?></td>
		<td><?php echo $m; ?></td>
		<td><?php echo $n; ?></td>
		<td><?php echo $o; ?></td>
		<td><?php echo $p; ?></td>
		<td><?php echo $q; ?></td>
		<td><?php echo $r; ?></td>
		<td><?php echo $s; ?></td>
		<td><?php echo $t; ?></td>
		<td><?php echo $u; ?></td>
		<td><?php echo $v; ?></td>
		<td><?php echo $w; ?></td>
		<td><?php echo $x; ?></td>
		<td><?php echo $y; ?></td>
		<td><?php echo $z; ?></td>
		<td><?php echo $aa; ?></td>
		<td><?php echo $bb; ?></td>
		<td><?php echo $cc; ?></td>
		<td><?php echo $dd; ?></td>
		<td><?php echo $ee; ?></td>
		<td style="text-align:right"><?php echo number_format($total_v,2); ?></td>
	</tr>
	</tfoot>
</table>
</div>
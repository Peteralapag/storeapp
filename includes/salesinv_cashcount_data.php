<?php
include '../init.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);	
$functions = new TheFunctions;
$branch = $functions->AppBranch();
if(isset($_SESSION['appstore_month'])) { $months = $_SESSION['appstore_month']; } else { $months = date("F"); }
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
			<th style="width:5px;text-align:center">DATE</th>
			<th>INVENTORY SALES</th>
			<th>CASH COUNT(DEPOSITED)</th>
			<th>DIFF.</th>
			<th>TOTAL SHORT AMOUNT</th>
			<th>TOTAL OVER AMOUNT</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<th>01</th>
			<td><?php echo $functions->getInvSalesOfVs(,$db);?></td>
			<td><?php echo ''?></td>
			<td><?php echo ''?></td>
			<td><?php echo ''?></td>
			<td><?php echo ''?></td>
		</tr>
		<tr>
			<th>02</th>
		</tr>
		<tr>
			<th>03</th>
		</tr>
		<tr>
			<th>04</th>
		</tr>
		<tr>
			<th>05</th>
		</tr>
		<tr>
			<th>06</th>
		</tr>
		<tr>
			<th>07</th>
		</tr>
		<tr>
			<th>08</th>
		</tr>
		<tr>
			<th>09</th>
		</tr>
		<tr>
			<th>10</th>
		</tr>
		<tr>
			<th>11</th>
		</tr>
		<tr>
			<th>12</th>
		</tr>
		<tr>
			<th>13</th>
		</tr>
		<tr>
			<th>14</th>
		</tr>
		<tr>
			<th>15</th>
		</tr>
		<tr>
			<th>16</th>
		</tr>
		<tr>
			<th>17</th>
		</tr>
		<tr>
			<th>18</th>
		</tr>
		<tr>
			<th>19</th>
		</tr>
		<tr>
			<th>20</th>
		</tr>
		<tr>
			<th>21</th>
		</tr>
		<tr>
			<th>22</th>
		</tr>
		<tr>
			<th>23</th>
		</tr>
		<tr>
			<th>24</th>
		</tr>
		<tr>
			<th>25</th>
		</tr>
		<tr>
			<th>26</th>
		</tr>
		<tr>
			<th>27</th>
		</tr>
		<tr>
			<th>28</th>
		</tr>
		<tr>
			<th>29</th>
		</tr>
		<tr>
			<th>30</th>
		</tr>
		<tr>
			<th>31</th>
		</tr>
		<tr>
			<th>TOTAL</th>
		</tr>
	</tbody>
</table>
</div>
<?php
include '../init.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$functions = new TheFunctions;
$dropdown = new DropDowns;
$branch = $functions->AppBranch();
$report_date = $functions->GetSession('branchdate');
$shift = $functions->GetSession('shift');
?>

<table style="width: 100%; top:0" class="tables" cellpadding="4" cellspacing="5">
	<tr>
		<td><input id="branch" type="text" class="form-control form-input" value="<?php echo $branch; ?>" disabled></td>
		<td><input id="date" type="text" class="form-control form-input" value="<?php echo $report_date; ?>" disabled></td>
		<td><input id="shift" type="text" class="form-control form-input" value="<?php echo $shift; ?>" disabled></td>
	</tr>
	<tr>
		<td>
			<input id="itemname" list="itemList" class="form-control" oninput="showinitemlist()" autocomplete="off">
			<datalist id="itemList">
				<?php echo $dropdown->selectItems($db); ?>
			</datalist>
		</td>
		<td>
			<input type="text" id="mycategory" class="form-control form-input" placeholder="CATEGORY" disabled>
		</td>
		<td>
			<input type="text" id="myitemid" class="form-control form-input" placeholder="ITEM ID" disabled>
		</td>
	</tr>
</table>
<div style="float:right">
	<button class="btn btn-success btnnew" onclick="additemtoinventory()"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Add this item</button>
</div>

<div id="results"></div>
<script>
function myFunction(params) {
    getValidItems(params);
}

function showinitemlist() {
    var mode = 'showinitemlist';
    var itemname = $('#itemname').val();
    
    $.post("./actions/actions.php", { mode: mode, itemname: itemname },
    function(data) {
        $("#itemList").html(data);
    });
}

function getValidItems(params) {
    var mode = 'getValItemRequest';
    var itemname = params;
    
    $.post("./actions/actions.php", { mode: mode, itemname: itemname },
    function(data) {
        $("#itemList").html(data);
        var response = JSON.parse(data);
        $("#mycategory").val(response.category);
        $("#myitemid").val(response.itemid);
    });
}

function additemtoinventory() {
    var mode = 'additemtoinventory';
    var itemname = $('#itemname').val();
    var category = $('#mycategory').val();
    var itemid = $('#myitemid').val();
    
    $.post("./actions/actions.php", { mode: mode, itemname: itemname, category: category, itemid: itemid },
    function(data) {
        $("#results").html(data);
    });
}

$(document).ready(function() {
    $('#itemname').on('change', function() {
        var selectedItem = $(this).val();
        myFunction(selectedItem);
    });
});
</script>

<?php
include '../init.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$functions = new TheFunctions;

$branch = $functions->AppBranch();
$report_date = $functions->GetSession('branchdate');
$shift = $functions->GetSession('shift');
$params = $_POST['params'] ?? '';
$itemname = $_POST['itemname'] ?? '';
$itemid = $_POST['itemid'] ?? '';

$charges = [
    'inventory_charges' => 'Inventory',
    'personal_charges' => 'Personal',
    'raw_material_charges' => 'Rawmats',
    'infraction_charges' => 'Infraction',
    'other_charges' => 'Others'
];



$existing = [];

$sql = "SELECT employee_name, idcode, charges_type, slip_no, quantity, remarks 
        FROM store_charges_data
        WHERE branch = ?
          AND report_date = ?
          AND shift = ?
          AND item_id = ?
          AND item_name = ?";

$stmt = $db->prepare($sql);
$stmt->bind_param("sssis", $branch, $report_date, $shift, $itemid, $itemname);
$stmt->execute();
$res = $stmt->get_result();

while($row = $res->fetch_assoc()){
    $existing[] = $row;
}

?>

<style>
.form-section { background: #f9f9f9; padding: 20px; border-radius: 8px; border: 1px solid #ddd; margin-bottom: 20px; }
.form-section h5 { margin-bottom: 15px; font-weight: bold; color: #333; border-bottom: 1px solid #ccc; padding-bottom: 5px; }
.form-label { font-weight: 600; margin-bottom: 5px; }
.table-sm td { vertical-align: middle; }
.employee_results { position: absolute; z-index: 999999; background: white; border: 1px solid #ccc; display: none; max-height: 180px; overflow-y: auto; width: 100%; }
</style>

<!-- ITEM INFO -->
<div class="form-section">
    <h5>Item Info</h5>
    <div class="row">
        <div class="col-md-4"><label class="form-label">Branch</label><input class="form-control" value="<?= $branch ?>" disabled></div>
        <div class="col-md-4"><label class="form-label">Report Date</label><input class="form-control" value="<?= $report_date ?>" disabled></div>
        <div class="col-md-4"><label class="form-label">Shift</label><input class="form-control" value="<?= $shift ?>" disabled></div>
    </div>
    <div class="row mt-2">
        <div class="col-md-8"><label class="form-label">Item Name</label><input class="form-control" value="<?= $itemname ?>" disabled></div>
        <div class="col-md-4"><label class="form-label">Item ID</label><input class="form-control" value="<?= $itemid ?>" disabled></div>
    </div>
</div>

<!-- ADD CHARGE FORM -->
<div class="form-section" style="background:#f7e9d5">
    <h5>Add Charge Entry</h5>
    
    	<table class="table-sm">
    	<tr>
    		<td style="text-align:center">Employee</td>
    		<td style="text-align:center">IDCODE</td>
    		<td style="text-align:center">Charge Type</td>
    		<td style="text-align:center">Charge Slip No.</td>
    		<td style="text-align:center">Quantity</td>
    		<td style="text-align:center">Remarks</td>
    	</tr>
    	<tr>
    		<td>
    			<div class="position-relative">
			        <input type="text" id="search_employee" class="form-control emp-name" placeholder="Search employee..." autocomplete="off">
			        <div class="employee_results"></div>
			    </div>
    		</td>
    		<td>
    			<input type="text" id="idcodeauto" class="form-control" style="width:100px" readonly>
    		</td>
    		<td>
    			<select class="form-control" id="charge_type">
	                <option value="">--Select--</option>
	                <?php foreach($charges as $k=>$v): ?>
	                    <option value="<?= $k ?>"><?= $v ?></option>
	                <?php endforeach; ?>
	            </select>
    		</td>
    		<td>
    			<input type="text" id="charge_slip" class="form-control" style="width:200px" autocomplete="off">
    		</td>
    		<td>
    			<input type="number" id="charge_qty" class="form-control" style="width:100px; text-align:center">
    		</td>
    		<td>
    			<input type="text" id="charge_remarks" class="form-control" autocomplete="off">
    		</td>
    	</tr>
    	
    	</table>
    
    <div style="float:right">
    	<button class="btn btn-primary btn-sm mb-2" id="addChargeBtn" style="margin:5px 5px"><i class="fa fa-plus-circle"></i> Add to List</button>
    </div>

    <table class="table table-bordered table-sm" id="chargesTable">
		<thead class="table-light">
		    <tr>
		        <th>Employee</th>
		        <th>ID Code</th>
		        <th>Charge Type</th>
		        <th>Slip No.</th>
		        <th>Qty</th>
		        <th>Remarks</th>
		        <th class="text-center">Action</th>
		    </tr>
		</thead>
		
		<tbody>
			<?php foreach($existing as $e): ?>
			    <tr>
			        <td><?= $e['employee_name'] ?>
			            <input type="hidden" name="emp_name[]" value="<?= $e['employee_name'] ?>">
			        </td>
			
			        <td><?= $e['idcode'] ?>
			            <input type="hidden" name="emp_id[]" value="<?= $e['idcode'] ?>">
			        </td>
			
			        <td><?= $e['charges_type'] ?>
			            <input type="hidden" name="charge_type[]" value="<?= $e['charges_type'] ?>">
			        </td>
			
			        <td><?= $e['slip_no'] ?>
			            <input type="hidden" name="charge_slip[]" value="<?= $e['slip_no'] ?>">
			        </td>
			
			        <td><?= $e['quantity'] ?>
			            <input type="hidden" name="charge_qty[]" value="<?= $e['quantity'] ?>">
			        </td>
			
			        <td>
			            <?php 
			                $disp = strlen($e['remarks']) > 30 
			                        ? substr($e['remarks'], 0, 30) . "..." 
			                        : $e['remarks'];
			                echo $disp;
			            ?>
			            <input type="hidden" name="charge_remarks[]" value="<?= $e['remarks'] ?>">
			        </td>
			
			        <td class="text-center">
			            <button class="btn btn-danger btn-sm remove-row"><i class="fa fa-trash" aria-hidden="true"></i></button>
			        </td>
			    </tr>
			<?php endforeach; ?>
		</tbody>
		
		<tfoot>
		    <tr>
		        <th colspan="4" class="text-end">TOTAL:</th>
		        <th id="totalQty" style="text-align:center">0</th>
		        <th colspan="2"></th>
		    </tr>
		</tfoot>
	</table>

    <div style="float:right">
        <button class="btn btn-success" id="saveAllCharges"><i class="fa fa-floppy-o" aria-hidden="true"></i> Save Charges</button>
    </div>
</div>


<div id="result_msg"></div>

<script>
$(document).ready(function(){
	
	updateTotalQty();
	
    $(document).on('keyup', '.emp-name', function(){
        const keyword = $(this).val();
        const resultBox = $(this).siblings('.employee_results');
        if(keyword.length >= 2){
            $.post("./actions/search_employee.php", {keyword}, function(data){
                resultBox.html(data).show();
            });
        } else resultBox.hide();
    });

    $(document).on('click', '.result-item', function(){
        const name = $(this).data('name');
        const id = $(this).data('idcode');
        const code = $(this).data('ecode');
        const parent = $(this).closest('.position-relative');
        parent.find('.emp-name').val(name);
        $('#idcodeauto').val(id);
        parent.find('.employee_results').hide();
    });


    $('#addChargeBtn').on('click', function(){

        const empName = $('#search_employee').val().trim();
        const empId = $('#idcodeauto').val().trim();
        const type = $('#charge_type').val();
        const slip = $('#charge_slip').val().trim();
        const qty = $('#charge_qty').val().trim();
        const remarks = $('#charge_remarks').val().trim();

        if(empName == '' || empId == '' || type == '' || slip == '' || qty == '' || remarks == ''){
            app_alert("System Message","Please fill all fields!","info");
            return;
        }

        
        
        let remarksDisplay = remarks.length > 30 ? remarks.substring(0, 30) + "..." : remarks;
        
        const row = `<tr>
		    <td>${empName}<input type="hidden" name="emp_name[]" value="${empName}"></td>
		
		    <td>${empId}<input type="hidden" name="emp_id[]" value="${empId}"></td>
		
		    <td>${type}<input type="hidden" name="charge_type[]" value="${type}"></td>
		
		    <td>${slip}<input type="hidden" name="charge_slip[]" value="${slip}"></td>
		
		    <td style="text-align:center">${qty}<input type="hidden" name="charge_qty[]" value="${qty}"></td>
		
		    <td title="${remarks}">${remarksDisplay}<input type="hidden" name="charge_remarks[]" value="${remarks}"></td>
		
		    <td class="text-center">
		        <button class="btn btn-danger btn-sm remove-row">
		            <i class="fa fa-trash" aria-hidden="true"></i>
		        </button>
		    </td>
		</tr>`;

        
        
        $('#chargesTable tbody').append(row);

        // Reset inputs
        $('#search_employee,#idcodeauto, #charge_type, #charge_slip, #charge_qty, #charge_remarks').val('');
        
        updateTotalQty();
    });
    
    
    $('#saveAllCharges').on('click', function() {
		
	    let dataArr = [];

		$('#chargesTable tbody tr').each(function(){
		    dataArr.push({
		        emp_name: $(this).find('input[name="emp_name[]"]').val(),
		        emp_id: $(this).find('input[name="emp_id[]"]').val(),
		        charge_type: $(this).find('input[name="charge_type[]"]').val(),
		        slip_no: $(this).find('input[name="charge_slip[]"]').val(),
		        qty: $(this).find('input[name="charge_qty[]"]').val(),
		        remarks: $(this).find('input[name="charge_remarks[]"]').val()
		    });
		});
		
		const params = '<?= $params?>';
		const branch = '<?= $branch?>';
		const reportdate = '<?= $report_date?>';
		const shift = '<?= $shift?>';
		const itemname = '<?= $itemname?>';
		const itemid = '<?= $itemid?>';
		
		$.post("./actions/actions.php", {
		    mode: "save_multiple_charges",
		    params: params,
		    branch: branch,
		    reportdate: reportdate,
		    shift: shift,
		    itemname: itemname,
		    itemid: itemid,
		    charges: JSON.stringify(dataArr)
		}, function(response){
		    $('#result_msg').html(response);
		    $('#chargesTable tbody').html('');
		    set_function('Inventory Record','inventory_record');
		    $('#additemcharges').fadeOut();

		});
    
	});

});


$(document).on('click', '.remove-row', function() {
    $(this).closest('tr').remove();
    updateTotalQty();
});


function updateTotalQty() {
    let total = 0;

    $('#chargesTable tbody tr').each(function() {
        const qty = parseFloat($(this).find('input[name="charge_qty[]"]').val()) || 0;
        total += qty;
    });

    $('#totalQty').text(total);
}


</script>

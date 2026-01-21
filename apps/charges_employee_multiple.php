
<style>
    .form-section {
        background: #f9f9f9;
        padding: 20px;
        border-radius: 8px;
        border: 1px solid #ddd;
        margin-bottom: 20px;
    }

    .form-section h5 {
        margin-bottom: 15px;
        font-weight: bold;
        color: #333;
        border-bottom: 1px solid #ccc;
        padding-bottom: 5px;
    }

    .form-label {
        font-weight: 600;
        margin-bottom: 5px;
    }

    .table-sm td {
        vertical-align: middle;
    }

    #employee_results {
        position: absolute;
        z-index: 999999;
        background: white;
        border: 1px solid #ccc;
        display: none;
        max-height: 180px;
        overflow-y: auto;
        width: 100%;
    }
</style>

<?php
include '../init.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$functions = new TheFunctions;

$branch = $functions->AppBranch();
$report_date = $functions->GetSession('branchdate');
$shift = $functions->GetSession('shift');
$params = $_POST['params'];
$itemname = $_POST['itemname'];
$itemid = $_POST['itemid']; 

$charges = [
    'inventory_charges' => 'Inventory',
    'personal_charges' => 'Personal',
    'raw_material_charges' => 'Rawmats',
    'infraction_charges' => 'Infraction',
    'other_charges' => 'Others'
];
?>

<div class="form-section">
    <h5>Report Info</h5>
    <div class="row">
        <div class="col-md-4">
            <label class="form-label">Branch</label>
            <input id="branch" type="text" class="form-control" value="<?= $branch ?>" disabled>
        </div>
        <div class="col-md-4">
            <label class="form-label">Report Date</label>
            <input id="date" type="text" class="form-control" value="<?= $report_date ?>" disabled>
        </div>
        <div class="col-md-4">
            <label class="form-label">Shift</label>
            <input id="shift" type="text" class="form-control" value="<?= $shift ?>" disabled>
        </div>
    </div>
</div>

<div class="form-section">
    <h5>Item Details</h5>
    <div class="row">
        <div class="col-md-8">
            <label class="form-label">Item Name</label>
            <input id="itemname" type="text" class="form-control" value="<?= $itemname ?>" disabled>
        </div>
        <div class="col-md-4">
            <label class="form-label">Item ID</label>
            <input id="itemid" type="text" class="form-control" value="<?= $itemid ?>" disabled>
        </div>
    </div>
</div>

<div class="form-section" style="background:#f7e9d5">
    <h5>Assign Multiple Employees</h5>

    <div class="row mb-3">
        <div class="col-md-4">
            <label class="form-label">Charge Type</label>
            <select class="form-control" id="multi_charge_type">
                <option value="">-- select charge type --</option>
                <?php foreach ($charges as $key => $label): ?>
                    <option value="<?= $key ?>"><?= $label ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Charge Slip No.</label>
            <input type="text" id="multi_charge_slip" class="form-control" placeholder="Enter charge slip no.">
        </div>
        <div class="col-md-4">
            <label class="form-label">Remarks</label>
            <input type="text" id="multi_remarks" class="form-control" placeholder="Enter remarks">
        </div>
    </div>

    <table class="table table-bordered table-sm align-middle" id="multiEmployeeTable">
        <thead class="table-light">
            <tr>
                <th style="width:50%">Employee Name</th>
                <th style="width:35%">ID Code</th>
                <th style="width:15%" class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <div class="position-relative">
                        <input type="text" class="form-control emp-name" placeholder="Search employee..." autocomplete="off">
                        <div class="employee_results"></div>
                    </div>
                </td>
                <td><input type="text" class="form-control emp-id" readonly></td>
                <td class="text-center">
                    <button class="btn btn-danger btn-sm remove-row"><i class="bi bi-x"></i></button>
                </td>
            </tr>
        </tbody>
    </table>

    <button class="btn btn-outline-primary btn-sm" id="addRowBtn"><i class="bi bi-plus-circle"></i> Add Row</button>
</div>

<div class="d-flex justify-content-end mt-3" style="float:right">
    <button class="btn btn-success" onclick="saveMultipleChargesEmployees()"><i class="bi bi-save"></i> Save All</button>
</div>

<div id="results"></div>

<script>

function addChargesEmployee(params,itemname,itemid){
	
	$.post("./apps/charges_employee_form.php", { params: params, itemname: itemname, itemid: itemid },
	function(data) {
		$('#additem_title').html('ADD CHARGES EMPLOYEE');
		$("#additem_page").html(data);
	});
	$('#additem').fadeIn();

}

$(document).ready(function() {

    // ➕ Add Row
    $('#addRowBtn').on('click', function() {
        const newRow = `
        <tr>
            <td>
                <div class="position-relative">
                    <input type="text" class="form-control emp-name" placeholder="Search employee..." autocomplete="off">
                    <div class="employee_results"></div>
                </div>
            </td>
            <td><input type="text" class="form-control emp-id" readonly></td>
            <td class="text-center"><button class="btn btn-danger btn-sm remove-row"><i class="bi bi-x"></i></button></td>
        </tr>`;
        $('#multiEmployeeTable tbody').append(newRow);
    });

    // ❌ Remove Row
    $(document).on('click', '.remove-row', function() {
        $(this).closest('tr').remove();
    });

    // 🔍 Autocomplete Employee Search per Row
    $(document).on('keyup', '.emp-name', function() {
        const input = $(this);
        const keyword = input.val();
        const resultBox = input.closest('td').find('.employee_results');
        if (keyword.length >= 2) {
            $.post("./actions/search_employee.php", { keyword: keyword }, function(data) {
                resultBox.html(data).show();
            });
        } else {
            resultBox.hide();
        }
    });

    // 🧍 Select Employee from results
    $(document).on('click', '.result-item', function() {
        const empName = $(this).data('name');
        const idCode = $(this).data('idcode');
        const row = $(this).closest('td').closest('tr');
        row.find('.emp-name').val(empName);
        row.find('.emp-id').val(idCode);
        $(this).closest('.employee_results').hide();
    });
});


// 💾 Save All Employees
function saveMultipleChargesEmployees() {
    const branch = $('#branch').val();
    const reportdate = $('#date').val();
    const shift = $('#shift').val();
    const itemname = $('#itemname').val();
    const itemid = $('#itemid').val();
    const chargetype = $('#multi_charge_type').val();
    const chargeSlip = $('#multi_charge_slip').val().trim();
    const remarks = $('#multi_remarks').val().trim();
    const params = '<?= $params ?>';
    const mode = 'save_multiple_charges';

    if (chargetype === "" || chargeSlip === "" || remarks === "") {
        app_alert("System Message", "Please fill in all charge details.", "info");
        return;
    }

    let employees = [];
    $('#multiEmployeeTable tbody tr').each(function() {
        const name = $(this).find('.emp-name').val().trim();
        const id = $(this).find('.emp-id').val().trim();
        if (name && id) employees.push({ name, id });
    });

    if (employees.length === 0) {
        app_alert("System Message", "Please add at least one employee.", "info");
        return;
    }

    $.post("./actions/actions.php", {
        mode: mode,
        params: params,
        branch: branch,
        reportdate: reportdate,
        shift: shift,
        itemname: itemname,
        itemid: itemid,
        chargetype: chargetype,
        charge_slip_no: chargeSlip,
        remarks: remarks,
        employees: JSON.stringify(employees)
    }, function(data) {
        $("#results").html(data);
    });
}
</script>

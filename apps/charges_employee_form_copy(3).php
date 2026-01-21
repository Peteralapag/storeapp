<style>
.spanpointer {
	cursor: pointer;
}
</style>

<?php
include '../init.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$functions = new TheFunctions;
$dropdown = new DropDowns;

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

$employees = $functions->getTableValue('charges', 'employee_name', $itemname, $branch, $report_date, $shift, $db);
$employee = $employees == '0' ? '' : $employees; 

$idcodes = $functions->getTableValue('charges', 'idcode', $itemname, $branch, $report_date, $shift, $db);
$idcode = $idcodes == 0 ? '' : $idcodes;

$selected_charge_type = $functions->getTableValue('charges', 'charges_type', $itemname, $branch, $report_date, $shift, $db);
?>

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

    .form-group {
        margin-bottom: 15px;
    }

    #employee_results {
        max-height: 200px;
        overflow-y: auto;
        border: 1px solid #ccc;
        background: white;
        position: absolute;
        width: 100%;
        display: none;
        z-index: 999999999;
    }

    .form-label {
        font-weight: 600;
        margin-bottom: 5px;
    }

    .btnnew {
        min-width: 150px;
    }
</style>

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
    <h5>Assign Employee</h5>
    <div class="row">
        <div class="col-md-8">
            <label class="form-label">Search Employee</label>
            <input type="text" class="form-control" id="search_employee" value="<?= htmlspecialchars($employee) ?>" placeholder="Search employee by name or ID..." autocomplete="off">
            <div id="employee_results"></div>
        </div>
        <div class="col-md-4">
            <label class="form-label">ID Code</label>
            <input id="idcodeauto" type="text" class="form-control" value="<?= htmlspecialchars($idcode) ?>" readonly>
        </div>

        <div class="col-md-4 mt-3">
            <label class="form-label">Charge Type</label>
            <select class="form-control" id="charge_type">
                <option value="">-- select charge type --</option>
                <?php foreach ($charges as $key => $label): ?>
                    <option value="<?= $key ?>" <?= ($selected_charge_type === $key) ? 'selected' : '' ?>>
                        <?= $label ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-4 mt-3">
            <label class="form-label">Charge Slip No.</label>
            <input type="text" id="charge_slip_no" class="form-control" placeholder="Enter charge slip no.">
        </div>

        <div class="col-md-4 mt-3">
            <label class="form-label">Remarks</label>
            <input type="text" id="remarks" class="form-control" placeholder="Enter remarks">
        </div>
    </div>
</div>



<div class="d-flex justify-content-end mt-3" style="float:right">
    <button type="button" class="btn btn-outline-secondary me-2" onclick="openMultipleEmployeeModal()">
        <i class="bi bi-people-fill"></i> Add Multiple Employees
    </button>
    <button type="button" class="btn btn-success" onclick="savechargesemployeeauto()">
        <i class="bi bi-person-plus-fill"></i> Add Employee
    </button>
</div>

<div id="results"></div>


<script>

function openMultipleEmployeeModal() {
    
    $.post("./apps/charges_employee_form.php", { params: params, itemname: itemname, itemid: itemid },
	function(data) {
		$('#additem_title').html('ADD CHARGES EMPLOYEE');
		$("#additem_page").html(data);
	});
	$('#additem').fadeIn();

}


let lastValidName = '';
let lastValidIdCode = '';




$(document).ready(function () {

    $('#search_employee').on('input', function () {
        const currentVal = $(this).val().toLowerCase().trim();
        if (currentVal !== lastValidName) {
            $('#idcodeauto').val('');
        } else {
            $('#idcodeauto').val(lastValidIdCode);
        }
    });

    $('#search_employee').keyup(function () {
        var keyword = $(this).val();
        if (keyword.length >= 2) {
            $.post("./actions/search_employee.php", { keyword: keyword }, function (data) {
                $('#employee_results').html(data).show();
            });
        } else {
            $('#employee_results').hide();
        }
    });

    $(document).on('click', '.result-item', function () {
        var empName = $(this).data('name');
        var idCode = $(this).data('idcode');
        $('#search_employee').val(empName);
        $('#idcodeauto').val(idCode);
        lastValidName = empName.toLowerCase();
        lastValidIdCode = idCode;
        $('#employee_results').hide();
    });
});


function savechargesemployeeauto() { 
    var mode = 'savechargesemployeeauto_new';
    var itemname = $('#itemname').val();
    var itemid = $('#itemid').val();
    var branch = $('#branch').val();
    var reportdate = $('#date').val();
    var shift = $('#shift').val();
    var idcode = $('#idcodeauto').val();	
    var chargetype = $('#charge_type').val();
    var employee = $('#search_employee').val();
    var chargeSlip = $('#charge_slip_no').val().trim();
    var remarks = $('#remarks').val().trim();
    var params = '<?= $params ?>';

    // Validation
    if (employee.trim() === "" || idcode.trim() === "") {
        app_alert("System Message", "Please select a valid employee from the list.", "info");
        $('#search_employee').focus();
        return;
    }

    if (chargetype === "") {
        app_alert("System Message", "Please select a Charge Type.", "info");
        $('#charge_type').focus();
        return;
    }

    if (chargeSlip === "") {
        app_alert("System Message", "Please enter a Charge Slip No.", "info");
        $('#charge_slip_no').focus();
        return;
    }

    if (remarks === "") {
        app_alert("System Message", "Please enter remarks.", "info");
        $('#remarks').focus();
        return;
    }

    // Proceed if all fields are valid
    $.post("./actions/actions.php", {
        mode: mode,
        params: params,
        itemname: itemname,
        employee: employee,
        itemid: itemid,
        idcode: idcode,
        chargetype: chargetype,
        branch: branch,
        reportdate: reportdate,
        shift: shift,
        charge_slip_no: chargeSlip,
        remarks: remarks
    }, function (data) {
        $("#results").html(data);
    });
}
</script>

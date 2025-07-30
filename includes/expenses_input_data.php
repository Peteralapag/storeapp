<?php
require '../init.php';
require '../class/brrr.class.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$brrr = new brrr;

$branch = $brrr->GetSession('branch');
$transdate = $brrr->GetSession('branchdate');

$overheadstatus = $brrr->overheadstatus($branch, $transdate, $db);
if ($overheadstatus != 1) {
    echo '
    <div style="padding: 20px; background-color: #fdd; color: #900; border: 1px solid #c00;">
        <strong>Access Denied:</strong> You cannot access the Expenses Input form until the Overhead Module is posted.
    </div>';
    exit;
}



$query = "SELECT * FROM store_brrr_category WHERE is_active = 1 AND type = 2 ORDER BY id ASC";


$result = $db->query($query);

$expense_categories = [];
while ($row = $result->fetch_assoc()) {
    $expense_categories[] = ['name' => $row['category_name']];
}


$saved_data = [];
$stmt = $db->prepare("SELECT category, actual_amount, remarks FROM store_brrr_expense_data WHERE branch = ? AND report_date = ?");
$stmt->bind_param("ss", $branch, $transdate);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $saved_data[$row['category']] = [
        'amount' => $row['actual_amount'],
        'remarks' => $row['remarks']
    ];
}



$savebtnstyle = $brrr->expenseinputstatus($branch, $transdate, $db) == 1? 'none': 'block';

?>

<form id="expenseForm" method="post">
    <table class="table table-bordered table-striped" style="margin-top: 20px; width: 100%;">
        <thead class="thead-dark">
            <tr>
                <th style="width: 40%;">Category</th>
                <th style="width: 15%;">Actual Amount</th>
                <th style="width: 45%;">Remarks</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($expense_categories as $i => $cat):
                $category_name = $cat['name'];
                $actual_amount = isset($saved_data[$category_name]) ? $saved_data[$category_name]['amount'] : '';
                $remarks = isset($saved_data[$category_name]) ? $saved_data[$category_name]['remarks'] : '';
            ?>
                <tr>
                    <td>
                        <?= htmlspecialchars($category_name) ?>
                        <input type="hidden" name="category[<?= $i ?>]" value="<?= htmlspecialchars($category_name) ?>">
                    </td>
                    <td>
                        <input type="number" class="form-control" name="actual_amount[<?= $i ?>]" step="0.01" autocomplete="off" value="<?= htmlspecialchars($actual_amount) ?>">
                    </td>
                    <td>
                        <input type="text" class="form-control" name="remarks[<?= $i ?>]" placeholder="Optional notes" autocomplete="off" value="<?= htmlspecialchars($remarks) ?>">
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
	
	
	
    <div style="text-align: right; margin-top: 20px; float:right">
        <button type="button" class="btn btn-success" style="display:<?= $savebtnstyle?>" onclick="saveExpense()">
            <i class="fa fa-save"></i> Save Expenses
        </button>
    </div>
</form>

<script>
function saveExpense() {
    psaSpinnerOn();

    var mode = 'expenseinputsave';
    const entries = [];
    const categories = document.querySelectorAll('input[name^="category"]');
    const amounts = document.querySelectorAll('input[name^="actual_amount"]');
    const remarks = document.querySelectorAll('input[name^="remarks"]');

    categories.forEach((cat, index) => {
        if (amounts[index].value.trim() !== '') {
            entries.push({
                category: cat.value,
                actual_amount: amounts[index].value || 0,
                remarks: (remarks[index].value || '').trim()
            });
        }
    });

    const saveBtn = document.querySelector('.btn-success');
    saveBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Saving...';
    saveBtn.disabled = true;

    $.post("../actions/brrr_actions.php", {
        mode: mode,
        entries: JSON.stringify(entries),
        branch: "<?= $branch ?>",
        transdate: "<?= $transdate ?>"
    }, function(data) {
        psaSpinnerOff();
        saveBtn.innerHTML = '<i class="fa fa-save"></i> Save Expenses';
        saveBtn.disabled = false;

        if(data.success) {
            app_alert('System Message', 'Expenses saved successfully', 'success');
        } else {
            app_alert('System Message', data.message || 'Failed to save expenses', 'error');
        }
        
        set_function('Expense Input','expenses_input');
        
    }, 'json').fail(function(xhr, status, error) {
        console.error("XHR Error Response:", xhr.responseText);
        psaSpinnerOff();
        saveBtn.innerHTML = '<i class="fa fa-save"></i> Save Expenses';
        saveBtn.disabled = false;
        app_alert('System Message', 'Errorss: ' + error, 'error');
    });
}
</script>

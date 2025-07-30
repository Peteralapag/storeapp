<?php
include '../init.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$functions = new TheFunctions;
$branch = $functions->GetSession('branch');

$reportDate = $_SESSION['session_date'];
$reportdate = date("F j, Y", strtotime($reportDate));


$query = "SELECT idcode, acctname, position FROM tbl_employees_ho WHERE branch='$branch' AND status='Active'";
$result = $db->query($query);

$employees = [];
while ($row = $result->fetch_assoc()) {
    $employees[] = $row;
}


usort($employees, function($a, $b) {
    return strcmp($a['acctname'], $b['acctname']);
});


$employeeNotes = [];
$stmt = $db->prepare("SELECT acctname, position, idcode FROM store_brrr_overhead_data WHERE branch = ? AND report_date = ?");
$stmt->bind_param("ss", $branch, $reportDate);
$stmt->execute();
$result_notes = $stmt->get_result();

while ($row = $result_notes->fetch_assoc()) {
    $employeeNotes[] = "{$row['acctname']} - {$row['position']} || {$row['idcode']}";
}

$existingNotes = implode("\n", array_filter(array_map('trim', $employeeNotes)));
?>

<div style="padding: 20px; background-color: #ffffff; border-radius: 10px; font-family: 'Segoe UI', sans-serif; font-size: 14px; color: #343a40; width: 100%;">
    <div style="margin-bottom: 12px;">
        <div style="font-size: 16px; font-weight: 600;">
            Branch: <span style="color: #495057;"><?= htmlspecialchars($branch) ?></span>
        </div>
        <div style="font-size: 13px; color: #6c757d;">
            As of: <?= htmlspecialchars($reportdate) ?>
        </div>
    </div>

    <hr style="border-top: 1px solid #dee2e6; margin: 12px 0;">

    <div style="margin-bottom: 16px;">
        <label for="employeeSelect" style="display: block; margin-bottom: 6px; font-weight: 500;">Select Branch Employee</label>
        <select id="employeeSelect" class="form-select" style="width: 100%; padding: 10px 12px; border-radius: 6px; border: 1px solid #ced4da; background-color: #f8f9fa; font-size: 14px;">
            <option value="">-- Select an employee --</option>
            <?php foreach ($employees as $emp): ?>
                <option value="<?= htmlspecialchars($emp['acctname'] . '||' . $emp['idcode'] . '||' . $emp['position']) ?>">
                    <?= htmlspecialchars($emp['acctname'] . ' - ' . $emp['position']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 10px;">
            <div style="display: flex; gap: 8px;">
                <button type="button" id="addEmployeeBtn" style="padding: 6px 12px; border: none; background-color: #198754; color: white; border-radius: 4px; cursor: pointer;">ADD Employee</button>
                <button type="button" id="clearEmployeeBtn" style="padding: 6px 12px; border: none; background-color: #dc3545; color: white; border-radius: 4px; cursor: pointer;">CLEAR</button>
            </div>
            
            
            <div style="font-size: 14px; color: #212529;">
			    Overhead Count: <span id="overheadCount"><?= count($employeeNotes) ?></span><br>
			    Bakers: <span id="bakerCount">0</span> |
			    Selling: <span id="sellingCount">0</span>
			</div>
            
            
        </div>
    </div>

    <textarea id="notes" rows="5" placeholder="Selected employees will appear here..." style="width: 100%;padding: 10px 12px;border-radius: 6px;border: 1px solid #ced4da;font-size: 14px;background-color: #fdfdfe;resize: vertical;" readonly></textarea>

    <div style="display: flex; justify-content: flex-end;">
        <button type="button" id="saveEmployeeBtn" style="padding: 6px 16px; border: none; background-color: #0d6efd; color: white; border-radius: 4px; cursor: pointer; font-weight: 500;">SAVE</button>
    </div>
</div>

<script>
const textarea = document.getElementById('notes');
const rawNotes = `<?= addslashes(trim($existingNotes)) ?>`;



function renumberNotes(text) {
    const lines = text
        .split("\n")
        .map(line => line.trim())
        .filter(line => line && !/^\d+\.\s*$/.test(line));
    return lines.map((line, index) => `${index + 1}. ${line}`).join("\n");
}



function countRoles(lines) {
    let baker = 0;
    let selling = 0;

    lines.forEach(line => {
        const match = line.match(/^(.+?) - (.+?) \|\| (.+)$/);
        if (match) {
            const position = match[2].trim().toUpperCase();
            if (position === "BAKER") {
                baker++;
            } else {
                selling++;
            }
        }
    });

    document.getElementById("bakerCount").textContent = baker;
    document.getElementById("sellingCount").textContent = selling;
}




textarea.value = renumberNotes(rawNotes);




document.getElementById("clearEmployeeBtn").addEventListener("click", function () {
    textarea.value = "";
    document.getElementById("overheadCount").textContent = "0";
    document.getElementById("bakerCount").textContent = "0";
    document.getElementById("sellingCount").textContent = "0";

    // Enable back all disabled options in the dropdown
    const options = document.querySelectorAll('#employeeSelect option');
    options.forEach(opt => opt.disabled = false);

    // Reset dropdown selection
    document.getElementById("employeeSelect").value = "";
});



document.getElementById("addEmployeeBtn").addEventListener("click", function () {
    const employeeSelect = document.getElementById("employeeSelect");
    const value = employeeSelect.value;

    if (!value) {
        app_alert('System Message','Please select an employee.','warning');
        return;
    }

    const [name, idcode, position] = value.split("||");

    let lines = textarea.value
        .split("\n")
        .map(line => line.trim().replace(/^\d+\.\s/, ''))
        .filter(line => line !== "");

    const alreadyExists = lines.some(line => line.includes(`|| ${idcode}`));
    if (alreadyExists) {
        app_alert('System Message','Employee already added.','warning');
        return;
    }

    lines.push(`${name} - ${position} || ${idcode}`);
    textarea.value = renumberNotes(lines.join("\n"));
    document.getElementById("overheadCount").textContent = lines.length;
    countRoles(lines);

    // Disable the selected option
    employeeSelect.querySelector(`option[value="${value}"]`).disabled = true;

    // Reset selection
    employeeSelect.value = "";
});



document.getElementById("saveEmployeeBtn").addEventListener("click", function () {
    const notes = textarea.value.trim();
    const mode = "overheadcountsave";   
    
    
    psaSpinnerOn();

    if (notes === "") {
        app_alert('System Message','No employees to save','warning');
        psaSpinnerOff();
        return;
    }

    const lines = notes.split("\n").map(line => line.replace(/^\d+\.\s/, '').trim()).filter(Boolean);
    const entries = lines.map(line => {
        const match = line.match(/^(.+?) - (.+?) \|\| (.+)$/);
        if (match) {
            return {
                acctname: match[1].trim(),
                position: match[2].trim(),
                idcode: match[3].trim()
            };
        }
        return null;
    }).filter(Boolean);

    $.post("../actions/brrr_actions.php", {
        mode: mode,
        notes: notes,
        entries: JSON.stringify(entries),
        branch: "<?= $branch ?>",
        report_date: "<?= $reportDate ?>"
    }, function(data) {
        app_alert('System Message','Employees saved successfully','success');
        set_function('Overhead','overheadcount');
        psaSpinnerOff();
    });
});
</script>

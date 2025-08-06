<?php
include '../init.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$keyword = $_POST['keyword'] ?? '';

if (strlen($keyword) < 2) {
    exit;
}

$sql = "SELECT idcode, CONCAT(lastname, ', ', firstname, ' ', middlename) AS fullname
        FROM tbl_employees_ho 
        WHERE (lastname LIKE ? OR firstname LIKE ? OR idcode LIKE ?) 
        AND status = 'Active' 
        LIMIT 10";

$stmt = $db->prepare($sql);
$search = "%" . $keyword . "%";
$stmt->bind_param("sss", $search, $search, $search);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div class='result-item' style='padding:5px; cursor:pointer; border-bottom:1px solid #ddd' 
                 data-idcode='{$row['idcode']}' 
                 data-name=\"" . htmlspecialchars($row['fullname'], ENT_QUOTES) . "\">
                 {$row['fullname']} ({$row['idcode']})
              </div>";
    }
} else {
    echo "<div style='padding:5px;'>No results found.</div>";
}

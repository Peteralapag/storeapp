<?php
require '../init.php';
require '../class/brrr.class.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$brrr = new brrr;

$branch = $brrr->GetSession('branch');
$reportdate = $brrr->GetSession('branchdate');


$query = "SELECT branch, report_date, idcode, acctname, position, created_by
          FROM store_brrr_overhead_data WHERE report_date='$reportdate' AND branch='$branch'
          ORDER BY report_date DESC, branch ASC";

$result = $db->query($query);
?>


<table id="upper" class="table table-striped table-bordered table-hover">
    <thead class="table-light">
        <tr>
            <th style="width:50px;text-align:center">#</th>
            <th>Branch</th>
            <th>Report Date</th>
            <th>IDcode</th>
            <th>Employee Name</th>
            <th>Position</th>
            <th>Encoded By</th>

        </tr>
    </thead>
    <tbody>
        <?php
        $i = 1;
        while ($row = $result->fetch_assoc()):
        ?>
            <tr>
                <td style="text-align:center"><?= $i++ ?></td>
                <td><?= htmlspecialchars($row['branch']) ?></td>
                <td><?= date("F j, Y", strtotime($row['report_date'])) ?></td>
                <td><?= htmlspecialchars($row['idcode']) ?></td>
                <td><?= htmlspecialchars($row['acctname']) ?></td>
                <td><?= htmlspecialchars($row['position']) ?></td>
                <td><?= htmlspecialchars($row['created_by']) ?></td>

            </tr>
        <?php endwhile; ?>
    </tbody>
</table>


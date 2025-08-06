<?php
include '../init.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
?>

<style>
  .form-inline .form-group {
    margin-right: 10px;
  }
  #dateInputs, #proceedBtnWrapper {
    display: none;
  }
</style>

<div class="form-inline" style="margin-bottom: 15px;">

	<div class="form-group">
	<label for="reportType">Report Type:</label>
	<select id="reportType" class="form-control" onchange="toggleDateInputs()">
		<option value="daily" selected>Daily</option>
		<option value="weekly">Weekly</option>
		<option value="semi-monthly">Semi-Monthly</option>
		<option value="monthly">Monthly</option>
	</select>
	</div>
	
	<div id="dateInputs" class="form-inline">
	<div class="form-group">
		<label for="dateFrom">From:</label>
		<input type="date" id="dateFrom" class="form-control" onchange="autoSetDateTo()">
	</div>
	
	<div class="form-group">
		<label for="dateTo">To:</label>
		<input type="date" id="dateTo" class="form-control" readonly>
	</div>
	</div>
	
	<div id="proceedBtnWrapper" class="form-group">
		<button id="proceedBtn" class="btn btn-primary" onclick="proceedReport()">Proceed</button>
	</div>	
	
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  toggleDateInputs();
});

function toggleDateInputs() {
  const type = document.getElementById("reportType").value;
  const dateInputs = document.getElementById("dateInputs");
  const proceedBtnWrapper = document.getElementById("proceedBtnWrapper");

  if (type === "daily") {
    dateInputs.style.display = "none";
    proceedBtnWrapper.style.display = "none";
  } else {
    dateInputs.style.display = "inline-block";
    proceedBtnWrapper.style.display = "inline-block";
  }

  document.getElementById("dateFrom").value = "";
  document.getElementById("dateTo").value = "";
}

function autoSetDateTo() {
  const type = document.getElementById("reportType").value;
  const fromInput = document.getElementById("dateFrom").value;
  if (!fromInput) return;

  const fromDate = new Date(fromInput);
  let toDate;

  switch (type) {
    case "weekly":
      toDate = new Date(fromDate);
      toDate.setDate(fromDate.getDate() + 6);
      break;

    case "semi-monthly":
      const day = fromDate.getDate();
      const year = fromDate.getFullYear();
      const month = fromDate.getMonth();
      if (day <= 15) {
        toDate = new Date(year, month, 15);
      } else {
        toDate = new Date(year, month + 1, 0); // end of month
      }
      break;

    case "monthly":
      const y = fromDate.getFullYear();
      const m = fromDate.getMonth();
      toDate = new Date(y, m + 1, 0); // end of month
      break;
  }

  document.getElementById("dateTo").value = formatDate(toDate);
}

function formatDate(date) {
  const y = date.getFullYear();
  const m = String(date.getMonth() + 1).padStart(2, '0');
  const d = String(date.getDate()).padStart(2, '0');
  return `${y}-${m}-${d}`;
}













function proceedReport() {
	
	psaSpinnerOn();
	
	const reportType = document.getElementById("reportType").value;
	const from = document.getElementById("dateFrom").value;
	const to = document.getElementById("dateTo").value;

	if (reportType !== "daily" && (!from || !to)) {
		app_alert("System Message","Please select valid dates.","warning");
		psaSpinnerOff();
		return;
	}

	const fileMap = {
		"daily": "summary",
		"weekly": "summary_viadate",
		"semi-monthly": "summary_viadate",
		"monthly": "summary_viadate"
	};

	const targetFile = fileMap[reportType];

	$.post("../includes/brrr_" + targetFile + "_data.php", {
		from: from,
		to: to,
		type: reportType
	}, function(response) {
		$("#contentdata").html(response);
		psaSpinnerOff();
	}).fail(function() {
		$("#contentdata").html("<div class='alert alert-danger'>Failed to load report.</div>");
		psaSpinnerOff();
	});
}












</script>

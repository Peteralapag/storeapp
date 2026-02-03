<?php
include '../init.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
?>

<div style="margin-bottom:15px; display:flex; align-items:center; gap:10px;">
    <label for="basedate" style="font-weight:600;">Select Base Date:</label>
    <input type="date" id="basedate" name="basedate" 
           style="padding:4px 8px; border-radius:4px; border:1px solid #ccc;">
    <button type="button" 
            style="padding:6px 12px; background-color:#28a745; color:#fff; border:none; border-radius:4px; cursor:pointer;"
            onclick="loadForecastingData($('#basedate').val())">
        Load Forecasting Data
</div>

<div class="table-wrapper Results" style="max-height:76vh; overflow:auto; border:1px solid #ccc; border-radius:6px;">
    <!-- Table will load here -->
</div>

<script>

function loadForecastingData(basedate) {
    $.post("./includes/breads_forecasting_data.php", { basedate: basedate },
    function(data) {
        $("#contentdata").html(data); 
    });
}


$("#basedate").on("change", function() {
    const selectedDate = $(this).val();
    if (!selectedDate) return;
    loadForecastingData(selectedDate);
});
</script>


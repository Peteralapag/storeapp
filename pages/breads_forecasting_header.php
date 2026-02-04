<?php
include '../init.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
?>

<div style="margin-bottom:15px; display:flex; align-items:center; gap:10px;">
    <label for="basedate" style="font-weight:600;">Select Base Date:</label>
    <input type="date" id="basedate" name="basedate" class="form-control" style="padding:4px 8px; border-radius:4px; border:1px solid #ccc; width:270px;">
</div>



<script>

function loadForecastingData(basedate) {
    
    psaSpinnerOn()
    $.post("./includes/breads_forecasting_data.php", { basedate: basedate },
    function(data) {
        $("#contentdata").html(data); 
        psaSpinnerOff()
    });
}


$("#basedate").on("change", function() {
    const selectedDate = $(this).val();
    if (!selectedDate) return;
    loadForecastingData(selectedDate);
});
</script>


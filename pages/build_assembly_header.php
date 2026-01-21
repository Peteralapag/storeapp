<div style="display:flex; align-items:center; gap:10px; margin-bottom:10px;">
    <input type="date" id="datefrom" class="form-control form-control-sm" style="width:140px;">
    <input type="date" id="dateto" class="form-control form-control-sm" style="width:140px;">
    <button class="btn btn-primary btn-sm" id="btn_proceed" disabled>Build Assembly</button>
</div>

<script>
function validateDates() {
    const dateFromVal = $("#datefrom").val();
    const dateToVal = $("#dateto").val();
    const btn = $("#btn_proceed");

    if (!dateFromVal || !dateToVal) {
        btn.prop("disabled", true);
        return;
    }

    const dateFrom = new Date(dateFromVal);
    const dateTo = new Date(dateToVal);

    if (dateTo < dateFrom) {
        app_alert("System Message", "‘Date To’ cannot be earlier than ‘Date From’.", "warning");
        btn.prop("disabled", true);
        return;
    }

    const diffTime = Math.abs(dateTo - dateFrom);
    const diffDays = diffTime / (1000 * 60 * 60 * 24);

    if (diffDays > 31) {
        app_alert("System Message", "Date range must not exceed 31 days.", "warning");
        btn.prop("disabled", true);
        return;
    }

    btn.prop("disabled", false);
}

$("#datefrom, #dateto").on("change", validateDates);

$("#btn_proceed").click(function () {
    psaSpinnerOn();
    $.post("./includes/build_assembly_data.php", {
        datefrom: $("#datefrom").val(),
        dateto: $("#dateto").val()
    }, function (res) {
        $("#contentdata").html(res);
        psaSpinnerOff();
    });
});
</script>

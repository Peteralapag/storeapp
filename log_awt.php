<?PHP
session_start();
session_destroy();
?>
<script>
sessionStorage.clear();
window.location.href='/';
</script>

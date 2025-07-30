<?PHP
$lines = file('.file/version.rms');
foreach($lines as $line) {
    define("VERSION_NUMBER", $line);
}
define("VERSION", VERSION_TEXT."".COMPANY." ".VERSION_NUMBER);
echo $functions->GetThemes('header');
echo $functions->GetThemes('body');
echo $functions->GetThemes('footer');
?>
<iframe id="checkonline" style="display:none"></iframe>
<script>
$(function()
{
	reload_syncher();
	setInterval(function()
	{
		reload_syncher();
	},10000);
});
function reload_syncher()
{
	if(sessionStorage.getItem("OFFLINE_MODE") == 0 || sessionStorage.getItem("OFFLINE_MODE") == null)
	{
		$("#checkonline").attr("src", "class/class_synching.php");
	}
}

var timeoutID;

/****************** PSA CODE*************************/
function startTimer() {
  // Reset the timer whenever there is user activity
  document.addEventListener("mousemove", resetTimer);
  document.addEventListener("keydown", resetTimer);
  document.addEventListener("wheel", resetTimer);
  document.addEventListener("touchmove", resetTimer);
  // Start the timer
  timeoutID = window.setTimeout(logout, 20 * 60 * 1000); // 20 minutes
}

function resetTimer() {
  // Clear the timer and start it again
  window.clearTimeout(timeoutID);
  startTimer();
}

function logout() {
  window.location.href = "log_awt.php";
}
startTimer();
/****************** PSA CODE*************************/
</script>
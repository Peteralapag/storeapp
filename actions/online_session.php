<?php
session_start();
require '../init.php';
echo $functions->OnlineStatus(is_connected,ip_hosts);
?>
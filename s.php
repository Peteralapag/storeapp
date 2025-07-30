<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled 2</title>
</head>

<body>
<?php
session_start();
    echo "<h3> PHP List All Session Variables</h3>";
    foreach ($_SESSION as $key=>$val)
    echo $key." ".$val."<br/>";
// echo $_SESSION['cashierid']   ;
 ?>
</body>

</html>

<?php
session_start();
session_destroy();
?>
<?php
$_SESSION["sidx"]="";
session_unset();
header('Location: studentlogin.php');
?>
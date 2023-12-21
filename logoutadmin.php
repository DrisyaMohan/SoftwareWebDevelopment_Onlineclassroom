<?php
session_start();
session_destroy();
?>

<?php
$_SESSION["umail"] = "";
session_unset(); // removes all session variables

header('Location: index.php');
?>

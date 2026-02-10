<?php
session_start();
session_unset(); // Sob session variable muche felbe
session_destroy(); // Session purapuri dhongso korbe

// Logout korar por login page ba index page-e niye jabe
header("Location: /payroll2/index.php");
exit;
?>

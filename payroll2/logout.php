<?php
session_start();
session_unset();
session_destroy();

// Logout korar por login page-e niye jabe
header("Location: /payroll2/index.php");
exit;
?>
<?php
session_start();
session_destroy();
header("Location: login.php");
$_SESSION['msg'] = "Logged out successfully";
exit();
?>
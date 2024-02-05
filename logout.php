<?php
require_once "connect.php";
// Start session
session_start();
// Destroy the session.
unset($_SESSION['Username']);

unset($_SESSION['userid']);

// Redirect to login page
header("Location: index.html");
exit;
?>

<?php

session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== true) {
    header("Location: login.php");
    exit;
}

// Display the staff administration area
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Administration Area</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<!-- Rest of your code for the staff administration area -->
</body>
</html>
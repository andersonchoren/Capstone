<?php

$servername = "localhost"; // XAMPP default
$dbUsername = "root"; // XAMPP default username
$dbPassword = "12345678"; // XAMPP default password is blank
$dbname = "ExcelDrivingSchool"; // Your database name

// Create connection
$conn = new mysqli($servername, $dbUsername, $dbPassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
//echo "Connected successfully";


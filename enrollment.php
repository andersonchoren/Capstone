<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enroll - Excel Driving School</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
<?php

require_once "connect.php";
session_start(); // Start the session at the beginning

// Check if the user is logged in, if not then redirect to login page
if (!isset($_SESSION['user_id']) && !isset($_SESSION['Username'])) {
    header("Location: student_login.php"); // Redirect to the login page
    exit;
}

// Database credentials
$servername = "localhost"; // XAMPP default
$dbUsername = "root"; // XAMPP default username
$dbPassword = ""; // XAMPP default password is blank
$dbname = "ExcelDrivingSchool"; // Your database name

// Create connection
$conn = new mysqli($servername, $dbUsername, $dbPassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<header>
    <div class="logo">
        <img src="image/logo.JPEG" alt="Excel Driving School Logo">
        <h1>Excel Driving School</h1>
    </div>
    <p>Your Journey Begins Here</p>
</header>

<nav>
    <ul>
        <li>Make Your Enrollment and Pay Here</li>
    </ul>
</nav>

<section class="enrollment-section">
    <h2>Course Enrollment</h2>
    <form action="payment.php" method="post">

        <!-- Branch Selection -->
        <label for="branch">Branch:</label>
        <select id="branch" name="branch">
            <?php
            $branchSql = "SELECT BranchName FROM Branches";
            $branchResult = $conn->query($branchSql);
            if ($branchResult && $branchResult->num_rows > 0) {
                while ($row = $branchResult->fetch_assoc()) {
                    echo "<option value='" . $row["BranchID"] . "'>" . htmlspecialchars($row["BranchName"]) . "</option>";
                }
            } else {
                echo "<option value=''>No branches found.</option>";
            }
            ?>
        </select>

        <!-- Vehicle Selection -->
        <label for="vehicle">Select Vehicle:</label>
        <select id="vehicle" name="vehicle">
            <?php
            $vehicleSql = "SELECT VehicleID, VehicleType , Model FROM Fleet";
            $vehicleResult = $conn->query($vehicleSql);
            if ($vehicleResult && $vehicleResult->num_rows > 0) {
                while ($row = $vehicleResult->fetch_assoc()) {
                    echo "<option value='" . $row["VehicleID"] . "'>" . htmlspecialchars($row["Model"]) ."-" .htmlspecialchars($row["VehicleType"])."</option>";
                }
            } else {
                echo "<option value=''>No vehicles available</option>";
            }
            ?>
        </select>

        <!-- Course Selection -->
        <label for="course">Course:</label>
        <select id="course" name="course">
            <?php
            $courseSql = "SELECT CourseID, CourseName, Price, NumberOfClasses FROM Courses";
            $courseResult = $conn->query($courseSql);
            if ($courseResult && $courseResult->num_rows > 0) {
                while ($row = $courseResult->fetch_assoc()) {
                    echo "<option value='" . $row["CourseID"] . "'>" . htmlspecialchars($row["CourseName"]) . " - Price: " . $row["Price"] . " - Number of Classes: " . $row["NumberOfClasses"] . "</option>";
                }
            } else {
                echo "<option value=''>No courses found.</option>";
            }
            ?>
        </select>

        <!-- Schedule Picker -->
        <label for="schedule">Schedule:</label>
        <select id="schedule" name="schedule">
            <?php
            $scheduleSql = "SELECT ScheduleID, Day, StartTime, EndTime, ClassDate FROM ClassSchedules";
            $scheduleResult = $conn->query($scheduleSql);
            if ($scheduleResult && $scheduleResult->num_rows > 0) {
                while ($row = $scheduleResult->fetch_assoc()) {
                    $scheduleInfo = $row["EndTime"] . " " . $row["StartTime"] . " - " . $row["Day"] ." - ". $row["ClassDate"];
                    echo "<option value='" . $row["ScheduleID"] . "'>" . htmlspecialchars($scheduleInfo) . "</option>";
                }
            } else {
                echo "<option value=''>No schedules found.</option>";
            }
            ?>

        </select>

        <label for="instructor">Instructor:</label>
        <select id="instructor" name="instructor">
            <?php
            $instructorSql = "SELECT InstructorID, Firstname, Lastname, Description FROM Instructors";
            $instructorResult = $conn->query($instructorSql);
            if ($instructorResult && $instructorResult->num_rows > 0) {
                while ($row = $instructorResult->fetch_assoc()) {
                    $instructorInfo = $row["FirstName"] . " " . $row["LastName"] . " - " . $row["Description"];
                    echo "<option value='" . $row["InstructorID"] . "'>" . htmlspecialchars($instructorInfo) . "</option>";
                }
            } else {
                echo "<option value=''>No instructors found.</option>";
            }
            ?>
            <select></select>
            <button type="submit" class="enroll-button">Enroll Now</button>

        </select>
    </form>
</section>

<footer>
    <p>&copy; 2024 Excel Driving School. All rights reserved.
        <a href="mailto:services@exceldriving.com">exceldriving@syd.com.au</a>
    </p>
</footer>

</body>
</html>

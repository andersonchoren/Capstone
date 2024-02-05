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

// TODO: Ensure that $selectedScheduleId is set based on user selection or logic
$selectedScheduleId = isset($_POST['schedule']) ? $_POST['schedule'] : 0;

?>

<header>
    <div class="logo">
        <img src="image/logo..JPEG" alt="Excel Driving School Logo">
        <h1>Excel Driving School</h1>
    </div>
    <p>Your Journey Begins Here</p>
</header>
<nav>
    <ul>
        <li><a href="javascript:if (window.history.length > 1) { window.history.back(); } else { window.location.href = 'index.html'; }">Back to Previous Page</a></li>
    </ul>
</nav>
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
            $branchSql = "SELECT BranchID, BranchName FROM Branches";
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
            $vehicleSql = "
                SELECT v.VehicleID, v.VehicleType, v.Model
                FROM Fleet v
                LEFT JOIN Bookings b ON v.VehicleID = b.VehicleID AND b.ScheduleID = ?
                WHERE b.VehicleID IS NULL;
            ";

            $stmt = $conn->prepare($vehicleSql);
            $stmt->bind_param("i", $selectedScheduleId);
            $stmt->execute();
            $vehicleResult = $stmt->get_result();

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
                    $instructorInfo = $row["Firstname"] . " " . $row["Lastname"] . " - " . $row["Description"];
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

<footer style="background-color:#383737; padding: 20px 0; text-align: center;">
    <div style="max-width: 400px; margin: 0 auto;">

        <h3 style="color: black;">Contact Us</h3>

        <address style="font-style: normal; margin-bottom: 10px;">
            123 Main Street<br>
            Melbourne, VIC 3000
        </address>

        <ul style="list-style: none; padding: 0; margin: 0;">
            <li style="margin-bottom: 10px;">Customer Service: (555) 123-4567</li>
            <li style="margin-bottom: 10px;">Appointment Booking: (555) 987-6543</li>
            <li style="margin-bottom: 10px;">Email: <a href="mailto:info@exceldrivingschool.com" style="color: #007BFF; text-decoration: none;">info@exceldrivingschool.com</a></li>
        </ul>

        <p style="color:black; margin-bottom: 10px;">Office Hours:</p>
        <p>Monday - Friday: 9:00 AM - 6:00 PM<br>
            Saturday: 10:00 AM - 4:00 PM<br>
            Closed on Sundays</p>

        <div class="social-icons" style="margin-top: 20px;">
            <a href="#" style="margin: 0 10px; color:black; text-decoration: none;" target="_blank">Facebook</a>
            <a href="#" style="margin: 0 10px; color:black; text-decoration: none;" target="_blank">Twitter</a>
            <a href="#" style="margin: 0 10px; color:black; text-decoration: none;" target="_blank">Instagram</a>
        </div>

    </div>
</footer>

</body>
</html>

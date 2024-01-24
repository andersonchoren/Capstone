<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Enroll - Excel Driving School</title>
  <link rel="stylesheet" href="style.css">
</head>

<body>

<header>
  <div class="logo">
    <img src="logo.JPEG" alt="Excel Driving School Logo">
    <h1>Excel Driving School</h1>
  </div>
  <p>Your Journey Begins Here</p>
</header>

<nav>
  <ul>
    <li><a href="index.html">Home</a></li>
    <li><a href="about.html">About Us</a></li>
    <li><a href="driving-course.html">Driving Course</a></li>
    <li><a href="special-offers.html">Special Offers</a></li>
    <li><a href="media.html">Media</a></li>
    <li><a href="contactus.html">Contact Us</a></li>
    <li><a href="students-login.html">Students Login</a></li>
  </ul>
</nav>

<?php
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

<section class="enrollment-section">
  <h2>Course Enrollment</h2>
  <form action="enrollment.php" method="post">

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
            $vehicleSql = "SELECT VehicleID, Model FROM Fleet";
            $vehicleResult = $conn->query($vehicleSql);
      if ($vehicleResult && $vehicleResult->num_rows > 0) {
      while ($row = $vehicleResult->fetch_assoc()) {
      echo "<option value='" . $row["VehicleID"] . "'>" . htmlspecialchars($row["Model"]) . "</option>";
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
            $courseSql = "SELECT CourseID, CourseName FROM Courses";
            $courseResult = $conn->query($courseSql);
      if ($courseResult && $courseResult->num_rows > 0) {
      while ($row = $courseResult->fetch_assoc()) {
      echo "<option value='" . $row["CourseID"] . "'>" . htmlspecialchars($row["CourseName"]) . "</option>";
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
            $scheduleSql = "SELECT ScheduleID, Day, StartTime, EndTime FROM ClassSchedules";
            $scheduleResult = $conn->query($scheduleSql);
      if ($scheduleResult && $scheduleResult->num_rows > 0) {
      while ($row = $scheduleResult->fetch_assoc()) {
      $scheduleInfo = $row["Day"] . " " . $row["StartTime"] . " - " . $row["EndTime"];
      echo "<option value='" . $row["ScheduleID"] . "'>" . htmlspecialchars($scheduleInfo) . "</option>";
      }
      } else {
      echo "<option value=''>No schedules found.</option>";
      }
      ?>
    </select>

    <button type="submit" class="enroll-button">Enroll Now</button>
  </form>
</section>

<footer>
  <p>&copy; 2024 Excel Driving School. All rights reserved.
    <a href="mailto:services@exceldriving.com">exceldriving@syd.com.au</a>
  </p>
</footer>

</body>
</html>
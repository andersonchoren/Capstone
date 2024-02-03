<?php
session_start();
require_once "connect.php";

// Check if the user is logged in and has the role of staff
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'staff') {
    header('Location: login.php'); // Redirect to login page
    exit();
}
// Fetch bookings
$sql = "select br.BranchName,ins.FirstName,ins.LastName,f.Model,bk.BookingID,bk.BookingDate,bk.Status, bk.PaymentConfirmed,c.CourseName,cls.StartTime,cls.ClassDate,st.firstname,st.lastname from branches br
    inner join bookings bk on br.BranchID = bk.BranchID
    inner join instructors ins on bk.InstructorID = ins.InstructorID
    inner join fleet f on bk.VehicleID = f.VehicleID
    inner join courses c on bk.CourseID = c.CourseID
    inner join classschedules cls on bk.ScheduleID = cls.ScheduleID
    inner join students st ON bk.StudentID = st.StudentID";

$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <div class="logo">
        <img src="image/logo..JPEG" alt="Excel Driving School Logo" style="width:15%; height: auto;">
        <h1>Excel Driving School - Staff Dashboard</h1>
    </div>
    <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>! (Staff)</p>
</header>
<nav>
    <ul>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</nav>
<div id="bookings" class="dashboard-section">
    <h2>Students Bookings</h2>
    <?php
    if ($result->num_rows > 0) {
        echo "<table>"; // Start a table
        echo "<tr><th>Instructor First Name</th><th>Instructor Last Name</th><th>Student First Name</th><th>Student Last Name</th><th>Branch</th><th>Booking Date</th><th>Course Date</th><th>Payment Status</th><th>Booking Accept</th><th>Actions</th></tr>";
        // Output data of each row
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row["FirstName"]). "</td>";
            echo "<td>" . htmlspecialchars($row["LastName"]). "</td>";
            echo "<td>" . htmlspecialchars($row["firstname"]). "</td>";
            echo "<td>" . htmlspecialchars($row["lastname"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["BranchName"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["BookingDate"]). "</td>";
            echo "<td>" . htmlspecialchars($row["ClassDate"]). "</td>";
            echo "<td>" . htmlspecialchars($row["Status"]). "</td>";
            echo "<td>" . ($row["PaymentConfirmed"] ? 'Yes' : 'No') . "</td>";
            // Edit link (pass the BookingID as a GET parameter)
            echo "<td><a href='booking_edit.php?bookingId=" . $row["BookingID"] . "'>Edit</a></td>";
            echo "</tr>";
        }
        echo "</table>"; // Close the table
    } else {
        echo "0 results";
    }
    ?>
</div>
<!-- Implement similar structures for Schedules, Reminders, Invoices, Payments -->
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

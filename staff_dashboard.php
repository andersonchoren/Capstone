<?php
session_start();

if (isset($_SESSION['message'])) {
    echo "<p>" . $_SESSION['message'] . "</p>";
    unset($_SESSION['message']);
}
require_once "connect.php";

// Check if the user is logged in and has the role of staff
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'staff') {
    header('Location: login.php'); // Redirect to login page
    exit();
}

// Fetch bookings
$sql = "SELECT br.BranchName, ins.FirstName AS InstructorFirstName, ins.LastName AS InstructorLastName, f.Model, 
               bk.BookingID, bk.BookingDate, bk.Status, bk.PaymentConfirmed, c.CourseName, 
               cls.StartTime, cls.ClassDate, st.FirstName AS StudentFirstName, st.LastName AS StudentLastName, st.StudentID 
        FROM branches br
        INNER JOIN bookings bk ON br.BranchID = bk.BranchID
        INNER JOIN instructors ins ON bk.InstructorID = ins.InstructorID
        INNER JOIN fleet f ON bk.VehicleID = f.VehicleID
        INNER JOIN courses c ON bk.CourseID = c.CourseID
        INNER JOIN classschedules cls ON bk.ScheduleID = cls.ScheduleID
        INNER JOIN students st ON bk.StudentID = st.StudentID";

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
        <li><a href="invoice_management.php">Invoice Management</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</nav>
<div id="bookings" class="dashboard-section">
    <h2>Students Bookings</h2>
    <?php
    if ($result->num_rows > 0) {
        echo "<table>";
        echo "<tr>
        <th>Instructor First Name</th>
        <th>Instructor Last Name</th>
        <th>Student First Name</th>
        <th>Student Last Name</th>
        <th>Branch</th>
        <th>Booking Date</th>
        <th>Course Date</th>
        <th>Payment Status</th>
        <th>Booking Accept</th>
        <th>Actions</th>
        <th>Send Reminder</th>
        <th>Cancel Booking</th>  <!-- Add this line -->
      </tr>";

        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row["InstructorFirstName"]). "</td>";
            echo "<td>" . htmlspecialchars($row["InstructorLastName"]). "</td>";
            echo "<td>" . htmlspecialchars($row["StudentFirstName"]). "</td>";
            echo "<td>" . htmlspecialchars($row["StudentLastName"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["BranchName"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["BookingDate"]). "</td>";
            echo "<td>" . htmlspecialchars($row["ClassDate"]). "</td>";
            echo "<td>" . htmlspecialchars($row["Status"]). "</td>";
            echo "<td>" . ($row["PaymentConfirmed"] ? 'Yes' : 'No') . "</td>";
            echo "<td><a href='booking_edit.php?bookingId=" . $row["BookingID"] . "'>Edit</a></td>";
            echo "<td><a href='send_reminder.php?bookingId=" . $row["BookingID"] . "&studentId=" . $row["StudentID"] . "'>Send Reminder</a></td>";
            echo "<td><a href='cancel_booking.php?bookingId=" . $row["BookingID"] . "' onclick='return confirm(\"Are you sure you want to cancel this booking?\");'>Cancel</a></td>"; // Add this line
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "0 results";
    }
    ?>
</div>
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

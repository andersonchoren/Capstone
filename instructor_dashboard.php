<?php
session_start();

// Redirect the user to the login page if they're not logged in or if they're not an instructor.
if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== true || !isset($_SESSION['is_instructor']) || $_SESSION['is_instructor'] !== true) {
    header("Location: login.php");
    exit;
}

require_once "connect.php";


// Get the list of students' bookings
$bookings = [];
$sql = "SELECT * FROM bookings";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $bookings[] = $row;
    }
}

// Get the list of schedules
$schedules = [];
$sql = "SELECT * FROM schedules";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $schedules[] = $row;
    }
}

// Get the list of reminders
$reminders = [];
$sql = "SELECT * FROM reminders";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $reminders[] = $row;
    }
}

// Get the list of invoices
$invoices = [];
$sql = "SELECT * FROM invoices";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $invoices[] = $row;
    }
}

// Get the list of payments
$payments = [];
$sql = "SELECT * FROM payments";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $payments[] = $row;
    }
}
// ... (Same PHP code for fetching data as in staff_dashboard.php)

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instructor Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <div class="logo">
        <img src="image/logo.JPEG" alt="Excel Driving School Logo">
        <h1>Excel Driving School - Instructor Dashboard</h1>
    </div>
    <p>Welcome, <?php echo $_SESSION['username']; ?>! (Instructor)</p>
</header>

<nav>
    <ul>
        <li><a href="#bookings">Students Bookings</a></li>
        <li><a href="#schedules">Schedules</a></li>
        <li><a href="#reminders">Reminders</a></li>
        <li><a href="#invoices">Invoices</a></li>
        <li><a href="#payments">Payments</a></li>
    </ul>
</nav>

<div class="description-container">
    <h1 class="title">Welcome to the Instructor Dashboard!</h1>
    <p class="subtitle">Manage students' bookings, schedules, reminders, invoices, and payments.</p>
    <div id="bookings">
        <h2>Students Bookings</h2>
        <table>
            <tr>
                <th>Student Name</th>
                <th>Booking Date</th>
                <th>Course</th>
            </tr>
            <?php foreach ($bookings as $booking): ?>
                <tr>
                    <td><?php echo $booking['student_name']; ?></td>
                    <td><?php echo $booking['booking_date']; ?></td>
                    <td><?php echo $booking['course']; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <div id="schedules">
        <h2>Schedules</h2>
        <table>
            <tr>
                <th>Course</th>
                <th>Date</th>
                <th>Time</th>
            </tr>
            <?php foreach ($schedules as $schedule): ?>
                <tr>
                    <td><?php echo $schedule['course']; ?></td>
                    <td><?php echo $schedule['date']; ?></td>
                    <td><?php echo $schedule['time']; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <div id="reminders">
        <h2>Reminders</h2>
        <table>
            <tr>
                <th>Student Name</th>
                <th>Reminder Date</th>
                <th>Reminder Message</th>
            </tr>
            <?php foreach ($reminders as $reminder): ?>
                <tr>
                    <td><?php echo $reminder['student_name']; ?></td>
                    <td><?php echo $reminder['reminder_date']; ?></td>
                    <td><?php echo $reminder['reminder_message']; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <div id="invoices">
        <h2>Invoices</h2>
        <table>
            <tr>
                <th>Student Name</th>
                <th>Invoice Date</th>
                <th>Amount</th>
            </tr>
            <?php foreach ($invoices as $invoice): ?>
                <tr>
                    <td><?php echo $invoice['student_name']; ?></td>
                    <td><?php echo $invoice['invoice_date']; ?></td>
                    <td><?php echo $invoice['amount']; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <div id="payments">
        <h2>Payments</h2>
        <table>
            <tr>
                <th>Student Name</th>
                <th>Payment Date</th>
                <th>Amount</th>
            </tr>
            <?php foreach ($payments as $payment): ?>
                <tr>
                    <td><?php echo $payment['student_name']; ?></td>
                    <td><?php echo $payment['payment_date']; ?></td>
                    <td><?php echo $payment['amount']; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>
    <!-- The sections for bookings, schedules, reminders, invoices, and payments go here -->
    <!-- Similar to the structure provided in your original code -->

    <!-- ... existing HTML code for displaying data ... -->

</div>

<footer>
    <p>&copy; <?php echo date("Y"); ?> Excel Driving School. All rights reserved.
        <a href="mailto:services@exceldriving.com">exceldriving@syd.com.au</a>
    </p>
</footer>
</body>
</html>
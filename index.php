<?php
require_once "connect.php";

// Start session and check if the user is authenticated.
session_start();
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('Location: login.php'); // Redirect to login page if not authenticated
    exit();
}

// Include your database connection here
// require 'db_connection.php';

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excel Driving School - Admin Area</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
<header>
    <div class="logo">
        <img src="image/logo.JPEG" alt="Excel Driving School Logo">
        <h1>Excel Driving School - Staff Area</h1>
    </div>
    <p>Welcome to the Staff Administration Area</p>
</header>
<nav>
    <ul>
        <li><a href="student_bookings.php">Student Bookings</a></li>
        <li><a href="payment_management.php">Payment Management</a></li>
        <li><a href="schedule_management.php">Schedule Management</a></li>
        <li><a href="course_management.php">Course Management</a></li>
        <li><a href="contact_queries.php">Contact Queries</a></li>
        <li><a href="staff_settings.php">Staff Settings</a></li>
    </ul>
</nav>
<main>
    <section class="admin-intro">
        <h2>Staff Dashboard</h2>
        <p>Welcome to your dashboard. Here you can manage bookings, schedules, payments, and more. Use the navigation menu to access different administrative sections.</p>
    </section>

    <section class="admin-functions">
        <div class="function-item">
            <h3>Manage Student Bookings</h3>
            <p>View and manage all student bookings. Confirm, reschedule, or cancel appointments.</p>
            <a href="student_bookings.php" class="admin-link">Go to Student Bookings</a>
        </div>

        <div class="function-item">
            <h3>Payment Management</h3>
            <p>Oversee student payments, confirm payment receipts, and manage billing issues.</p>
            <a href="payment_management.php" class="admin-link">Go to Payment Management</a>
        </div>

        <div class="function-item">
            <h3>Schedule Management</h3>
            <p>Plan and modify class schedules, assign instructors, and manage class capacities.</p>
            <a href="schedule_management.php" class="admin-link">Go to Schedule Management</a>
        </div>

        <div class="function-item">
            <h3>Course Management</h3>
            <p>Add, update, or remove courses. Manage course descriptions, requirements, and availability.</p>
            <a href="course_management.php" class="admin-link">Go to Course Management</a>
        </div>

        <div class="function-item">
            <h3>Contact Queries</h3>
            <p>Respond to inquiries from potential and current students.</p>
            <a href="contact_queries.php" class="admin-link">Go to Contact Queries</a>
        </div>
    </section>
</main>
<footer>
    <p>&copy; 2024 Excel Driving School. All rights reserved.
        <a href="mailto:services@exceldriving.com">exceldriving@syd.com.au</a>
    </p>
</footer>
</body>

</html>



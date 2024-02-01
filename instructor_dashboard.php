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
        <img src="image/logo..JPEG" alt="Excel Driving School Logo" style="width:15%; height: auto;">
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


<footer>
    <p>&copy; <?php echo date("Y"); ?> Excel Driving School. All rights reserved.
        <a href="mailto:services@exceldriving.com">exceldriving@syd.com.au</a>
    </p>
</footer>
</body>
</html>

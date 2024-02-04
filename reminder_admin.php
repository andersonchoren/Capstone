<?php
if (isset($_GET['bookingId']) && isset($_GET['action']) && $_GET['action'] == 'confirm') {
    $bookingId = $_GET['bookingId'];
    // Here you would normally update the database to mark the booking as confirmed.
    // For the dummy page, just set a variable.
    $confirmationStatus = "Confirmed";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reminder Administration</title>
</head>
<body>
<h1>Reminder Administration</h1>
<?php
if (isset($confirmationStatus)) {
    echo "<p>Booking ID " . htmlspecialchars($bookingId) . " has been " . htmlspecialchars($confirmationStatus) . ".</p>";
}
?>
<!-- Here you could list all bookings and their confirmation status -->
</body>
</html>

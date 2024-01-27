<?php
session_start();
require_once "connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $studentId = $_SESSION['user_id']; // Assuming the student's ID is stored in the session
    $branchId = $_POST['branch'];
    $vehicleId = $_POST['vehicle'];
    $courseId = $_POST['course'];
    $scheduleId = $_POST['schedule'];
    $instructorId = $_POST['instructor'];

    $bookingDate = date("Y-m-d"); // Current date for the booking
    $status = "Pending"; // Default status
    $paymentConfirmed = 0; // Default to 0 (not confirmed)

    // Prepare an insert statement for the Bookings table
    $sql = "INSERT INTO Bookings (StudentID, BranchID, VehicleID, CourseID, ScheduleID, InstructorID, BookingDate, Status, PaymentConfirmed) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("iiiiisssi", $studentId, $branchId, $vehicleId, $courseId, $scheduleId, $instructorId, $bookingDate, $status, $paymentConfirmed);

        if ($stmt->execute()) {
            // Store the last inserted booking ID in the session for later retrieval
            $_SESSION['lastBookingId'] = $conn->insert_id;

            // Redirect to the next page (e.g., booking_details.php or another page)
            header("Location: booking_details.php");
            exit;
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
    $conn->close();
} else {
    echo "Form not submitted properly.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excel Driving School</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <div class="logo">
        <img src="image/logo.JPEG" alt="Excel Driving School Logo">
        <h1>Excel Driving School</h1>
    </div>
    <p>Your Journey Begins Here</p>
</header>
<nav>
    <ul>
        <li><a href="javascript:if (window.history.length > 1) { window.history.back(); } else { window.location.href = 'index.html'; }">Back to Previous Page</a></li>
    </ul>
</nav>
<h1>Payment Form</h1>

<!-- Show error message if there is one -->
<?php if (!empty($error_message)) { ?>
    <p class="error"><?php echo $error_message; ?></p>
<?php } ?>

<form id="payment-form" action="payment.php" method="POST">
    <!-- Choose Payment Method -->
    <div>
        <input type="radio" id="credit_card" name="payment_method" value="Credit Card" checked>
        <label for="credit_card">Credit Card</label>
    </div>
    <div>
        <input type="radio" id="paypal" name="payment_method" value="PayPal">
        <label for="paypal">PayPal</label>
    </div>
    <div>
        <input type="radio" id="afterpay" name="payment_method" value="Afterpay">
        <label for="afterpay">Afterpay</label>
    </div>
    <!-- Credit Card Details -->
    <div id="credit_card_details">
        <label for="card-number">Card Number:</label>
        <input type="text" id="card-number" name="card-number">

        <label for="card-expiration-date">Expiration Date:</label>
        <input type="text" id="card-expiration-date" name="card-expiration-date">

        <label for="card-cvc">CVC:</label>
        <input type="text" id="card-cvc" name="card-cvc">

    </div>
    <!-- PayPal Email -->
    <div id="paypal_details" style="display: none;">
        <label for="paypal_email">PayPal Email:</label>
        <input type="email" id="paypal_email" name="paypal_email">
    </div>

    <!-- Afterpay Details -->
    <div id="afterpay_details" style="display: none;">
        <p>Afterpay will be processed on the next page.</p>

    </div>
    <button type="submit">Pay Now</button>
</form>

<footer>
    <p>&copy; 2024 Excel Driving School. All rights reserved. <a href="mailto:services@exceldriving.com">exceldriving@syd.com.au</a></p>
</footer>

<script>
    // JavaScript to handle display of payment method details
    document.querySelectorAll('input[name="payment_method"]').forEach(input => {
        input.addEventListener('change', function() {
            document.getElementById('credit_card_details').style.display = this.value === 'Credit Card' ? 'block' : 'none';
            document.getElementById('paypal_details').style.display = this.value === 'PayPal' ? 'block' : 'none';
            document.getElementById('afterpay_details').style.display = this.value === 'Afterpay' ? 'block' : 'none';
        });
    });
</script>
</body>
</html>

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
    document.querySelectorAll('input[name="payment_method"]').forEach(input => {
        input.addEventListener('change', function() {
            document.getElementById('credit_card_details').style.display = this.value === 'Credit Card' ? 'block' : 'none';
            document.getElementById('paypal_details').style.display = this.value === 'PayPal' ? 'block' : 'none';
            document.getElementById('afterpay_details').style.display = this.value === 'Afterpay' ? 'block' : 'none';
        });
    });
</script>
<?php
// payment.php
session_start();
require_once "connect.php"; // Database connection

// Simulate payment processing here
$paymentSuccess = true; // This should be replaced with actual payment logic

if ($paymentSuccess) {
    $bookingId = $_SESSION['lastBookingId'];
    $sql = "UPDATE Bookings SET PaymentConfirmed = 1 WHERE BookingID = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $bookingId);
        if ($stmt->execute()) {
            header("Location: booking_details.php"); // Redirect to booking details page
            exit;
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
    $conn->close();
}
?>

</body>
</html>

<?php
session_start();
require_once "connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Initialize the connection to start transaction
    $conn->begin_transaction();

    try {
        // Retrieve form data
        $studentId = $_SESSION['user_id']; // Assuming the student's ID is stored in the session
        $branchId = $_POST['branch'];
        $vehicleId = $_POST['vehicle'];
        $courseId = $_POST['course'];
        $scheduleId = $_POST['schedule'];
        $instructorId = $_POST['instructor'];

        // Fetch the price of the selected course
        $sqlPrice = "SELECT Price FROM Courses WHERE CourseID = ?";
        $stmtPrice = $conn->prepare($sqlPrice);
        $stmtPrice->bind_param("i", $courseId);
        $stmtPrice->execute();
        $stmtPrice->bind_result($amountDue); // This will be used as the invoice amount
        $stmtPrice->fetch();
        $stmtPrice->close();

        $bookingDate = date("Y-m-d"); // Current date for the booking
        $status = "Pending"; // Default status for booking
        $paymentConfirmed = 0; // Default to 0 (not confirmed)

        // Prepare an insert statement for the Bookings table
        $sql = "INSERT INTO Bookings (StudentID, BranchID, VehicleID, CourseID, ScheduleID, InstructorID, BookingDate, Status, PaymentConfirmed) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("iiiiisssi", $studentId, $branchId, $vehicleId, $courseId, $scheduleId, $instructorId, $bookingDate, $status, $paymentConfirmed);
            if (!$stmt->execute()) {
                throw new Exception("Error: " . $stmt->error);
            }
            // Store the last inserted booking ID in the session for later retrieval
            $_SESSION['lastBookingId'] = $conn->insert_id;
            $stmt->close();
        } else {
            throw new Exception("Error preparing statement: " . $conn->error);
        }

        $dueDate = date("Y-m-d", strtotime("+30 days")); // setting due date as 30 days from today
        $invoiceStatus = 'Pending'; // Default invoice status

        // Prepare an insert statement for the Invoices table
        $sqlInvoice = "INSERT INTO Invoices (StudentID, AmountDue, DueDate, Status) VALUES (?, ?, ?, ?)";
        if ($stmtInvoice = $conn->prepare($sqlInvoice)) {
            $stmtInvoice->bind_param("idss", $studentId, $amountDue, $dueDate, $invoiceStatus);
            if (!$stmtInvoice->execute()) {
                throw new Exception("Error: " . $stmtInvoice->error);
            }
            // Get the last inserted invoice ID
            $lastInvoiceId = $conn->insert_id;
            $stmtInvoice->close();
        } else {
            throw new Exception("Error preparing invoice statement: " . $conn->error);
        }

        // Fetching student email
        $sqlEmail = "SELECT Email FROM Students WHERE StudentID = ?";
        $email = '';
        if ($stmtEmail = $conn->prepare($sqlEmail)) {
            $stmtEmail->bind_param("i", $studentId);
            $stmtEmail->execute();
            $stmtEmail->bind_result($email);
            $stmtEmail->fetch();
            $stmtEmail->close();
        } else {
            throw new Exception("Error preparing email fetch statement: " . $conn->error);
        }
        // Sending email notification
        if (!empty($email)) {
            $to = $email;
            $subject = "Invoice from Excel Driving School";
            $message = "Dear Student,\n\nYour invoice has been created.\nInvoice ID: $lastInvoiceId\nAmount Due: $amountDue\nDue Date: $dueDate\n\nThank you for choosing Excel Driving School.";
            $headers = "From: admin@exceldrivingschool.com";
            if (!mail($to, $subject, $message, $headers)) {
                throw new Exception("Failed to send the invoice.");
            }
        }
        // If everything was successful, commit the transaction
        $conn->commit();
    } catch (Exception $e) {
        // An error occurred, roll back the transaction
        $conn->rollback();
        echo $e->getMessage(); // Or handle error appropriately
    }
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
        <img src="image/logo..JPEG" alt="Excel Driving School Logo">
        <h1>Excel Driving School</h1>
    </div>
    <p>Your Journey Begins Here</p>
</header>
<nav>
    <ul>
        <li>
            <a href="javascript:if (window.history.length > 1) { window.history.back(); } else { window.location.href = 'index.html'; }">Back
                to Previous Page</a></li>
    </ul>
</nav>

<?php
$branchId = $_POST['branch'];
$branchSql = "SELECT BranchName FROM Branches where BranchID = $branchId";
$branchResult = $conn->query($branchSql);
$arrayBranch = $branchResult->fetch_assoc();

$vehicleId = $_POST['vehicle'];
$vehicleSql = "SELECT Model FROM fleet where VehicleID = $vehicleId";
$vehicleResult = $conn->query($vehicleSql);
$arrayVehicle = $vehicleResult->fetch_assoc();

// Close the connection
$conn->close();
?>
<table>
    <thead>
    <tr>
        <th>Branch</th>
        <th>Vehicle model</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td><?= $arrayBranch['BranchName'] ?></td>
        <td><?= $arrayVehicle['Model'] ?></td>
    </tr>
    </tbody>
</table>
<h1>Payment Form</h1>
<!-- Show error message if there is one -->
<?php if (!empty($error_message)) { ?>
    <p class="error"><?php echo $error_message; ?></p>
<?php } ?>

<form id="payment-form" action="student-area.html" method="POST">
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
    <button type="submit" name="submit_payment">Pay Now</button>

    <footer>
        <p> 2024 Excel Driving School. All rights reserved. <a href="mailto:services@exceldriving.com">exceldriving@syd.com.au</a>
        </p>
    </footer>

    <script>
        // JavaScript to handle display and validation of payment method details
        document.getElementById('payment-form').addEventListener('submit', function (e) {
            const selectedPaymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
            let isValid = true;

            // Validate Credit Card Details
            if (selectedPaymentMethod === 'Credit Card') {
                if (!document.getElementById('card-number').value || !document.getElementById('card-expiration-date').value || !document.getElementById('card-cvc').value) {
                    alert('Please fill out all credit card details.');
                    isValid = false;
                }
            }

            // Validate PayPal Email
            if (selectedPaymentMethod === 'PayPal' && !document.getElementById('paypal_email').value) {
                alert('Please enter your PayPal email.');
                isValid = false;
            }

            // If any validation failed, prevent form submission
            if (!isValid) {
                e.preventDefault();
            }
        });

        // Handle display of payment method details
        document.querySelectorAll('input[name="payment_method"]').forEach(input => {
            input.addEventListener('change', function () {
                document.getElementById('credit_card_details').style.display = this.value === 'Credit Card' ? 'block' : 'none';
                document.getElementById('paypal_details').style.display = this.value === 'PayPal' ? 'block' : 'none';
                document.getElementById('afterpay_details').style.display = this.value === 'Afterpay' ? 'block' : 'none';
            });
        });
    </script>

</body>
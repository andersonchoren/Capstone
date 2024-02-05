<?php
session_start();
require_once "connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Initialize the connection to start transaction
    $conn->begin_transaction();

    try {
        // Retrieve form data
        $studentId = $_SESSION['Studentid']; // Assuming the student's ID is stored in the session
        $branchId = $_POST['branch'];
        $vehicleId = $_POST['vehicle'];
        $courseId = $_POST['course'];
        $scheduleId = $_POST['schedule'];
        $instructorId = $_POST['instructor'];
// Check availability
        $checkSql = "SELECT COUNT(*) FROM Bookings 
                     WHERE ScheduleID = ? AND (VehicleID = ? OR InstructorID = ?)";
        $stmtCheck = $conn->prepare($checkSql);
        $stmtCheck->bind_param("iii", $scheduleId, $vehicleId, $instructorId);
        $stmtCheck->execute();
        $stmtCheck->bind_result($bookingExists);
        $stmtCheck->fetch();
        $stmtCheck->close();

        if ($bookingExists > 0) {
            // If a booking exists, throw an exception
            throw new Exception("This slot is already booked. Please select another.");
        }
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
$branchSql = "SELECT BranchName FROM Branches WHERE BranchID = ?";
$stmt = $conn->prepare($branchSql);
$stmt->bind_param("i", $branchId);
$stmt->execute();
$arrayBranch = $stmt->get_result()->fetch_assoc();
$stmt->close();

$vehicleId = $_POST['vehicle'];
$vehicleSql = "SELECT Model FROM fleet WHERE VehicleID = ?";
$stmt = $conn->prepare($vehicleSql);
$stmt->bind_param("i", $vehicleId);
$stmt->execute();
$arrayVehicle = $stmt->get_result()->fetch_assoc();
$stmt->close();

$courseId = $_POST['course'];
$courseSql = "SELECT CourseName, Price FROM courses WHERE CourseID = ?";
$stmt = $conn->prepare($courseSql);
$stmt->bind_param("i", $courseId);
$stmt->execute();
$arrayCourse = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Close the connection
$conn->close();
?>
<table>
    <thead>
    <tr>
        <th>Branch</th>
        <th>Vehicle Model</th>
        <th>Course Name</th> <!-- Added Course Name header -->
        <th>Course Price</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td><?= htmlspecialchars($arrayBranch['BranchName']) ?></td>
        <td><?= htmlspecialchars($arrayVehicle['Model']) ?></td>
        <td><?= htmlspecialchars($arrayCourse['CourseName']) ?></td> <!-- Displaying Course Name -->
        <td><?= htmlspecialchars($arrayCourse['Price']) ?></td>
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

    <!-- Credit Card Details -->
    <div id="credit_card_details">
        <label for="card-number">Card Number (16 digits):</label>
        <input type="text" id="card-number" name="card-number" maxlength="16" placeholder="1234 5678 9012 3456">

        <label for="card-expiration-date">Expiration Date (MM/YY):</label>
        <input type="text" id="card-expiration-date" name="card-expiration-date" placeholder="MM/YY">

        <label for="card-cvc">CVC (3 digits):</label>
        <input type="text" id="card-cvc" name="card-cvc" maxlength="3" placeholder="123">

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" placeholder="email@example.com">
    </div>

    <button type="submit" name="submit_payment">Pay Now</button>
</form>

<footer>
    <p> 2024 Excel Driving School. All rights reserved. <a href="mailto:services@exceldriving.com">exceldriving@syd.com.au</a>
    </p>
</footer>

<script>
    // JavaScript to handle display and validation of payment method details
    document.getElementById('payment-form').addEventListener('submit', function (e) {
        const selectedPaymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
        let isValid = true;
        let alertMessage = '';

        // Validate Credit Card Details
        if (selectedPaymentMethod === 'Credit Card') {
            const cardNumber = document.getElementById('card-number').value;
            const cardExpirationDate = document.getElementById('card-expiration-date').value;
            const cardCVC = document.getElementById('card-cvc').value;
            const email = document.getElementById('email').value;

            // Basic checks for empty fields
            if (!cardNumber || !cardExpirationDate || !cardCVC || !email) {
                alertMessage = 'Please fill out all fields.';
                isValid = false;
            } else {
                // Specific check for card number length
                if (cardNumber.length !== 16) {
                    alertMessage += 'Card number must be 16 digits.\n';
                    isValid = false;
                }

                // Specific check for CVC length
                if (cardCVC.length !== 3) {
                    alertMessage += 'CVC must be 3 digits.\n';
                    isValid = false;
                }

                // Check for a dummy email
                if (email === 'test@test.com') {
                    alertMessage += 'Please use a real email address.\n';
                    isValid = false;
                }
            }
        }

        // If any validation failed, show alert message and prevent form submission
        if (!isValid) {
            alert(alertMessage);
            e.preventDefault();
        } else {
            // Ask for a 4-digit code for confirmation
            const code = prompt('Please enter the 4-digit confirmation code sent to your email:');
            if (code.length !== 4) {
                alert('Invalid code. Please enter the correct 4-digit code.');
                e.preventDefault();
            } else {
                alert('Payment Confirmed.');
            }
        }
    });

    // Handle display of payment method details
    document.querySelectorAll('input[name="payment_method"]').forEach(input => {
        input.addEventListener('change', function () {
            document.getElementById('credit_card_details').style.display = this.value === 'Credit Card' ? 'block' : 'none';
        });
    });
</script>

</body>
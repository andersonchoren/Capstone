<?php

session_start();
require_once "connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
    $studentId = $_SESSION['user_id'];
    $amountDue = 0; // You might want to calculate or retrieve this based on the course or other parameters
    $dueDate = date("Y-m-d"); // Current date for the due date, adjust as needed
    $status = "Pending"; // Default status, adjust based on your application's logic

    // Prepare an insert statement for the Invoices table
    $sql = "INSERT INTO Invoices (StudentID, AmountDue, DueDate, Status) VALUES (?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("idss", $studentId, $amountDue, $dueDate, $status);

        if ($stmt->execute()) {
            // Redirect to the student area upon successful invoice creation
            header("Location: student-area.html");
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
    echo "Form not submitted properly or user ID not set in session.";
}



<?php
require_once "connect.php";

// Invoice data (replace these with actual data or form inputs)
$studentID = 1; // Example student ID
$amount = 100.00; // Example amount
$issueDate = date("Y-m-d"); // Current date
$dueDate = date("Y-m-d", strtotime("+30 days")); // Due in 30 days
$status = "Unpaid"; // Initial status

// SQL to insert a new invoice
$sql = "INSERT INTO Invoices (StudentID, Amount, IssueDate, DueDate, Status) VALUES (?, ?, ?, ?, ?)";

// Prepare statement
$stmt = $conn->prepare($sql);
$stmt->bind_param("idsss", $studentID, $amount, $issueDate, $dueDate, $status);

// Execute the query
if ($stmt->execute()) {
    echo "New invoice created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Close statement and connection
$stmt->close();
$conn->close();
?>

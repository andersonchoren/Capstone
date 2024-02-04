<?php
require_once "connect.php";
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'staff') {
    header('Location: login.php');
    exit();
}

if (isset($_GET['invoiceId'])) {
    $invoiceId = $_GET['invoiceId'];

    $sql = "DELETE FROM Invoices WHERE InvoiceID = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $invoiceId);
        if ($stmt->execute()) {
            $_SESSION['message'] = "Invoice deleted successfully.";
        } else {
            $_SESSION['message'] = "Error deleting invoice: " . $conn->error;
        }
        $stmt->close();
    } else {
        $_SESSION['message'] = "Error preparing statement: " . $conn->error;
    }
} else {
    $_SESSION['message'] = "No invoice ID provided.";
}

header('Location: invoice_management.php');
exit();
?>

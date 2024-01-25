<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $cardNumber = $_POST['card-number'];
    $expirationDate = $_POST['card-expiration-date'];
    $cvc = $_POST['card-cvc'];

    // Process the payment
    // Add your payment processing logic here

    // Generate the invoice
    $invoiceNumber = uniqid();
    $amount = 99.99; // Change this to the actual payment amount
    $customerName = "John Doe"; // Change this to the actual customer name

    // Print the invoice
    echo "Invoice Number: " . $invoiceNumber . "<br>";
    echo "Amount: $" . $amount . "<br>";
    echo "Customer Name: " . $customerName . "<br>";
}

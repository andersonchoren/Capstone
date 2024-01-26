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
        <img src="logo.JPEG" alt="Excel Driving School Logo">
        <h1>Excel Driving School</h1>
    </div>
    <p>Your Journey Begins Here</p>
</header>
<nav>
    <ul>
        <li><a href="index.html">Home</a></li>
        <li><a href="about.html">About Us</a></li>
        <li><a href="driving-course.html">Driving Course</a></li>
        <li><a href="special-offers.html">Special Offers</a></li>
        <li><a href="media.html">Media</a></li>
        <li><a href="contactus.html">Contact Us</a></li>
        <li><a href="students-login.html">Students Login</a></li>
    </ul>
</nav>
<h1>Payment Form</h1>
<form id="payment-form" method="POST">
    <label for="card-number">
        Card Number
    </label>
    <input id="card-number" name="card-number">

    <label for="card-expiration-date">
        Expiration Date
    </label>
    <input id="card-expiration-date" name="card-expiration-date">

    <label for="card-cvc">
        CVC
    </label>
    <input id="card-cvc" name="card-cvc">

    <button type="submit">Pay Now</button>
    </form>

<footer>
    <p>&copy; 2024 Excel Driving School. All rights reserved. <a href="mailto:services@exceldriving.com">exceldriving@syd.com.au</a></p>
</footer>

<script>
    // Function to generate a unique invoice number
    function generateInvoiceNumber() {
        var characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        var invoiceNumber = "";

        for (var i = 0; i < 10; i++) {
            var randomIndex = Math.floor(Math.random() * characters.length);
            invoiceNumber += characters.charAt(randomIndex);
        }

        return invoiceNumber;
    }

    // Function to handle form submission and generate invoice
    function generateInvoice() {
        var cardNumber = document.getElementById("card-number").value;
        var expirationDate = document.getElementById("card-expiration-date").value;
        var cvc = document.getElementById("card-cvc").value;

        // Perform validation on the form fields
        // Add your validation logic here

        // Generate the invoice
        var invoiceNumber = generateInvoiceNumber();
        var amount = 99.99; // Change this to the actual payment amount
        var customerName = "John Doe"; // Change this to the actual customer name

        // Print the invoice
        console.log("Invoice Number: " + invoiceNumber);
        console.log("Amount: $" + amount);
        console.log("Customer Name: " + customerName);
    }
</script>
<?php

require_once "connect.php";

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
?>
</body>
</html>
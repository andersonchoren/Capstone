<?php
require_once "connect.php";

session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'staff') {
    header('Location: login.php'); // Redirect to login page
    exit();
}

// Display session message if exists
if (isset($_SESSION['message'])) {
    echo "<p>" . $_SESSION['message'] . "</p>";
    unset($_SESSION['message']);
}

// Fetch all invoices
$sql = "SELECT * FROM Invoices";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Management - Excel Driving School</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <div class="logo">
        <img src="image/logo..JPEG" alt="Excel Driving School Logo" style="width:15%; height: auto;">
        <h1>Excel Driving School - Invoice Management</h1>
    </div>
    <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>! (Staff)</p>
</header>
<nav>
    <ul>
        <li><a href="invoice_management.php">Invoice Management</a></li>
        <li><a href="javascript:if (window.history.length > 1) { window.history.back(); } else { window.location.href = 'staff_dashboard.php'; }">Back to Previous Page</a></li>
        <li><a href="logout.php">Logout</a></li>

    </ul>
</nav>
<main>
    <h2>Invoice List</h2>
    <table>
        <tr>
            <th>Invoice ID</th>
            <th>Student ID</th>
            <th>Amount</th>
            <th>Issue Date</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["InvoiceID"] . "</td>";
                echo "<td>" . $row["StudentID"] . "</td>";
                echo "<td>" . $row["AmountDue"] . "</td>";
                echo "<td>" . $row["DueDate"] . "</td>";
                echo "<td>" . $row["Status"] . "</td>";
                echo "<td><a href='edit_invoice.php?invoiceId=" . $row["InvoiceID"] . "'>Edit</a> | <a href='delete_invoice.php?invoiceId=" . $row["InvoiceID"] . "' onclick='return confirm(\"Are you sure?\");'>Delete</a></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7'>No invoices found</td></tr>";
        }
        ?>
    </table>
    <h2>Add New Invoice</h2>
    <form action="add_invoice.php" method="post">
        <label for="studentID">Student ID:</label>
        <input type="number" name="studentID" required><br>
        <label for="amount">Amount:</label>
        <input type="text" name="amount" required><br>
        <label for="issueDate">Issue Date:</label>
        <input type="date" name="issueDate" required><br>
        <label for="dueDate">Due Date:</label>
        <input type="date" name="dueDate" required><br>
        <label for="status">Status:</label>
        <select name="status" required>
            <option value="Unpaid">Unpaid</option>
            <option value="Paid">Paid</option>
            <option value="Cancelled">Cancelled</option>
        </select><br>
        <input type="submit" value="Add Invoice">
    </form>
</main>
<footer style="background-color:#8b8686; padding: 20px 0; text-align: center;">
    <div style="max-width: 800px; margin: 0 auto;">
        <h3 style="color: black;">Contact Us</h3>
        <address style="font-style: normal; margin-bottom: 10px;">
            123 Main Street<br>
            Cityville, State 12345
        </address>
        <ul style="list-style: none; padding: 0; margin: 0;">
            <li style="margin-bottom: 10px;">Customer Service: (555) 123-4567</li>
            <li style="margin-bottom: 10px;">Appointment Booking: (555) 987-6543</li>
            <li style="margin-bottom: 10px;">Email: <a href="mailto:info@exceldrivingschool.com" style="color: #007BFF; text-decoration: none;">info@exceldrivingschool.com</a></li>
        </ul>
        <p style="color:black; margin-bottom: 10px;">Office Hours:</p>
        <p>Monday - Friday: 9:00 AM - 6:00 PM<br>
            Saturday: 10:00 AM - 4:00 PM<br>
            Closed on Sundays</p>
        <div class="social-icons" style="margin-top: 20px;">
            <a href="#" style="margin: 0 10px; color:black; text-decoration: none;" target="_blank">Facebook</a>
            <a href="#" style="margin: 0 10px; color:black; text-decoration: none;" target="_blank">Twitter</a>
            <a href="#" style="margin: 0 10px; color:black; text-decoration: none;" target="_blank">Instagram</a>
        </div>
    </div>
</footer>
</body>
</html>
<?php
$conn->close();
?>

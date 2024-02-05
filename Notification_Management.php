<?php
session_start();
require_once "connect.php";

// Check if the user is logged in and has the role of staff
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'staff') {
    header('Location: login.php'); // Redirect to login page
    exit();
}

// Handle notification form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $studentId = $_POST['studentId'];
    $instructorId = $_SESSION['userId']; // Assuming instructor's user ID is in the session
    $type = 'Reminder'; // or any other type you want to set
    $message = $_POST['message'];
    $dateCreated = date('Y-m-d H:i:s');
    $isRead = 0; // default value for unread notification

    $sql = "INSERT INTO Notifications (StudentID, InstructorID, Type, Message, DateCreated, IsRead) VALUES (?, ?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("iisssi", $studentId, $instructorId, $type, $message, $dateCreated, $isRead);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Notification sent successfully!";
        } else {
            $_SESSION['message'] = "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $_SESSION['message'] = "Error: " . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notification Management - Excel Driving School</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>

    <div class="logo">
        <img src="image/logo..JPEG" alt="Excel Driving School Logo" style="width:15%; height: auto;">
        <h1>Excel Driving School - Notification Management</h1>
    </div>
    <nav>
        <ul>
            <li><a href="javascript:if (window.history.length > 1) { window.history.back(); } else { window.location.href = 'index.html'; }">Back to Previous Page</a></li>
        </ul>
    </nav>
    <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>! (Staff)</p>
</header>

<!-- Display Session Message -->
<?php if (isset($_SESSION['message'])): ?>
    <p><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></p>
<?php endif; ?>

<!-- Notification Form -->
<div id="notification-area" class="dashboard-section">
    <h2>Send Notification</h2>
    <form action="Notification_Management.php" method="post">
        <div>
            <label for="studentId">Student ID:</label>
            <input type="number" id="studentId" name="studentId" required>
        </div>
        <div>
            <label for="message">Message:</label>
            <textarea id="message" name="message" required></textarea>
        </div>
        <div>
            <button type="submit">Send Notification</button>
        </div>
    </form>
</div>

<footer style="background-color:#494747; padding: 20px 0; text-align: center;">
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

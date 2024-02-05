<?php
// student_notifications.php
session_start();
require_once "connect.php";

// Enable error reporting for debugging (remove in production)
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['Username']) && $_SESSION['Role'] !== 'Student') {
    header('Location: students-login.html'); // Redirect to login page
    exit();
}
// Initialize $notifications to avoid "Undefined variable" warning
$notifications = array();

// Check if StudentID is set in the session
if (isset($_SESSION['userId']) && !is_array($_SESSION['userId'])) {  // Change 'StudentID' to 'userId' to match your session variable
    $studentId = $_SESSION['Studentid'];  // Adjust the session variable key as per your login script
    $sql = "SELECT NotificationID, Type, Message, DateCreated, IsRead 
            FROM Notifications 
            WHERE StudentID = ? 
            ORDER BY DateCreated DESC";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $studentId);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $notifications[] = $row;
            }
            if (count($notifications) === 0) {
                echo "No notifications found for student ID: " . $studentId;
            }
        } else {
            echo "Execute failed: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Prepare failed: " . $conn->error;
    }
} else {
    echo "Student ID session variable is not set or is not a proper ID.";
    // Optional: Print the content of $_SESSION for debugging
    echo '<pre>';
    print_r($_SESSION);
    echo '</pre>';
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excel Driving School - Student Notifications</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <div class="logo">
        <img src="image/logo..JPEG" alt="Excel Driving School Logo" style="width:15%; height: auto;">
        <h1>Excel Driving School - Student Notifications</h1>
    </div>
    <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>! (Student)</p>
</header>
<nav>
    <ul>
        <li><a href="javascript:if (window.history.length > 1) { window.history.back(); } else { window.location.href = 'index.html'; }">Back to Previous Page</a></li>
    </ul>
</nav>
<!-- ... existing navigation and other content ... -->
<div class="container">
    <h2>Your Notifications</h2>
    <div class="responsive-table">
        <?php if (count($notifications) > 0): ?>
            <table>
                <thead>
                <tr>
                    <th>Type</th>
                    <th>Message</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($notifications as $notification): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($notification['Type']); ?></td>
                        <td><?php echo htmlspecialchars($notification['Message']); ?></td>
                        <td><?php echo htmlspecialchars($notification['DateCreated']); ?></td>
                        <td><?php echo $notification['IsRead'] ? 'Read' : 'Unread'; ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>You have no notifications.</p>
        <?php endif; ?>
    </div>
</div>
<footer style="background-color:#8b8686; padding: 20px 0; text-align: center;">
    <div style="max-width: 400px; margin: 0 auto;">

        <h3 style="color: black;">Contact Us</h3>

        <address style="font-style: normal; margin-bottom: 10px;">
            123 Main Street<br>
            Melbourne, VIC 3000
        </address>

        <ul style="list-style: none; padding: 0; margin: 0;">
            <li style="margin-bottom: 10px;">Customer Service: (555) 2123-4567</li>
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

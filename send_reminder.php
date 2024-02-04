<?php
session_start();
require_once "connect.php";

// Check if the user is logged in and has the role of staff
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'staff') {
    header('Location: login.php'); // Redirect to login page
    exit();
}

if (isset($_GET['noteId']) && isset($_GET['studentId'])) {
    $noteId = $_GET['noteId'];
    $studentId = $_GET['studentId'];

    // Fetch student email and note details
    $sql = "SELECT st.Email as StudentEmail, nt.Content, nt.DateCreated 
            FROM students st 
            INNER JOIN Notes nt ON st.StudentID = nt.StudentID 
            WHERE nt.NoteID = ? AND st.StudentID = ?";

    // Prepare and bind parameters
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ii", $noteId, $studentId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $studentEmail = $row['StudentEmail'];
            $noteContent = $row['Content'];  // Assuming you want to include some part of the content in the email
            $dateCreated = $row['DateCreated'];

            // Send Email Logic (Simulation)
            // Normally you would use a mailer library or API to send the email.
            $to = $studentEmail;
            $subject = "Reminder: Review Your Class Notes";
            $message = "Hello, this is a reminder to review the class notes created on $dateCreated. Here is a snippet: $noteContent";
            $headers = "From: noreply@yourschool.com";

            if (mail($to, $subject, $message, $headers)) {
                echo "Reminder sent successfully to $to";
                // Optionally, you might want to log this in the database.
            } else {
                echo "Failed to send reminder.";
            }
        } else {
            echo "No note found.";
        }
        $stmt->close();
    } else {
        echo "Error in database query.";
    }
} else {
    echo "Invalid request.";
}

$conn->close();
?>

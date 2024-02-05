<?php
// fetch_notifications.php
session_start();
require_once "connect.php";

header('Content-Type: application/json');

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'student') {
    echo json_encode(array("error" => "Unauthorized access"));
    exit();
}

$studentId = $_SESSION['StudentId']; // Ensure the session variable name matches across your scripts

$response = array();
$sql = "SELECT * FROM Notifications WHERE StudentID = ? AND IsRead = 0";

if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $studentId);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $response[] = $row;
            }
        } else {
            $response = array("error" => "Failed to fetch result.");
        }
    } else {
        $response = array("error" => $stmt->error);
    }
    $stmt->close();
} else {
    $response = array("error" => $conn->error);
}

echo json_encode($response);
?>

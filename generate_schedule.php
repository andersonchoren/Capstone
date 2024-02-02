<?php
require_once "connect.php";

// Days, instructors, branches, and time slots
$daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
$timeSlots = ['08:00:00', '09:30:00', '11:00:00', '12:30:00', '14:00:00', '15:30:00'];
$courseTypes = range(1, 5); // Assuming course types are 1-2
$instructors = range(1, 3); // Assuming instructor IDs are 1-3
$branches = range(1, 2); // Assuming branch IDs are 1-2

// Prepare the SQL statement
$sql = "INSERT INTO ClassSchedules (CourseID, InstructorID, BranchID, Day, StartTime, EndTime, ClassDate) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo "Error preparing statement: " . $conn->error;
    exit;
}

// Determine the date range
$startDate = new DateTime(); // today
$endDate = (new DateTime())->modify('+1 weeks'); // 2 months from today

// Iterate over each day in the range
for ($date = clone $startDate; $date <= $endDate; $date->modify('+1 day')) {
    $dayOfWeek = $date->format('l'); // Get the day of the week
    $dayFormatted = $date->format('Y-m-d'); // Format the date as 'Y-m-d'
    if (in_array($dayOfWeek, $daysOfWeek)) {
        foreach ($timeSlots as $startTime) {
            $endTime = date('H:i:s', strtotime($startTime) + 90 * 60); // Adding 1.5 hours for each class duration
            foreach ($courseTypes as $courseType) {
                foreach ($instructors as $instructor) {
                    foreach ($branches as $branch) {
                        // Bind parameters and execute the statement for each schedule
                        $stmt->bind_param("iiissss", $courseType, $instructor, $branch, $dayOfWeek, $startTime, $endTime, $dayFormatted);
                        if ($stmt->execute()) {
                            echo "New record created successfully for " . $dayFormatted . " " . $dayOfWeek . "\n";
                        } else {
                            echo "Error: " . $stmt->error . "\n";
                        }
                    }
                }
            }
        }
    }
}

// Close statement and connection
$stmt->close();
$conn->close();
?>

?>

<?php
require_once "connect.php";

// Days, instructors, branches, and time slots
$daysOfWeek = ['Monday', 'Wednesday', 'Friday']; // Onl
$timeSlots = ['09:30:00', '14:00:00']; // Reduced time slots
$courseTypes = range(1, 5); // Assuming you want fewer course types
$instructors = [1,7]; // Assuming you only want schedules for 1 instructor
// Similar changes can be made to $branches// Assuming instructor IDs are 1-3
$branches = range(1, 4); // Assuming branch IDs are 1-2

// Prepare the SQL statement
$sql = "INSERT INTO ClassSchedules (CourseID, InstructorID, BranchID, Day, StartTime, EndTime, ClassDate) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo "Error preparing statement: " . $conn->error;
    exit;
}

// Determine the date range
$startDate = (new DateTime())->modify('+2');
$endDate = (new DateTime())->modify('+3 days'); // Reduced from '+1 weeks'

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
                        if (rand(0, 1)) { // 50% chance to create a schedule for this combination
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
}

// Close statement and connection
$stmt->close();
$conn->close();
?>

?>

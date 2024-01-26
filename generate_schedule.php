<?php
require_once "connect.php";

// Days, instructors, branches, and time slots
$days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
$timeSlots = ['08:00:00', '09:30:00', '11:00:00', '12:30:00', '14:00:00', '15:30:00'];
$courseTypes = range(1, 2); // Assuming course types are 1-5
$instructors = range(1, 3); // Assuming instructor IDs are 1-20
$branches = range(1, 2); // Assuming branch IDs are 1-5

foreach ($days as $day) {
    foreach ($timeSlots as $startTime) {
        $endTime = date('H:i:s', strtotime($startTime) + 90 * 60); // Adding 1.5 hours for each class duration
        foreach ($courseTypes as $courseType) {
            foreach ($instructors as $instructor) {
                foreach ($branches as $branch) {
                    // SQL to insert schedule
                    $sql = "INSERT INTO ClassSchedules (CourseID, InstructorID, BranchID, Day, StartTime, EndTime) 
                            VALUES ($courseType, $instructor, $branch, '$day', '$startTime', '$endTime')";

                    if ($conn->query($sql) === TRUE) {
                        echo "New record created successfully\n";
                    } else {
                        echo "Error: " . $sql . "\n" . $conn->error;
                    }
                }
            }
        }
    }
}
$conn->close();


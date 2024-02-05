<?php
require_once "connect.php";

session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'staff') {
    header('Location: student_login.php'); // Redirect to login page
    exit();
}

// Display session message if exists
if (isset($_SESSION['message'])) {
    echo "<p>" . $_SESSION['message'] . "</p>";
    unset($_SESSION['message']);
}

if (isset($_POST['username'], $_POST['password'], $_POST['firstname'], $_POST['lastname'], $_POST['contactinfo'], $_POST['address'], $_POST['dob'], $_POST['email']) && isset($_FILES['driverLicenseUpload'])) {
    $new_username = $_POST['username'];
    $new_password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
    $new_firstname = $_POST['firstname'];
    $new_lastname = $_POST['lastname'];
    $new_contactinfo = $_POST['contactinfo'];
    $new_address = $_POST['address'];
    $new_dob = $_POST['dob'];
    $new_email = $_POST['email'];

    // Start the transaction
    $conn->begin_transaction();

    try {
        // Insert the new student into the Students table
        $sql = "INSERT INTO Students (username, password, firstname, lastname, contactinfo, address, dob, email) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . htmlspecialchars($conn->error));
        }
        $stmt->bind_param("ssssssss", $new_username, $new_password, $new_firstname, $new_lastname, $new_contactinfo, $new_address, $new_dob, $new_email);
        $stmt->execute();
        $student_id = $conn->insert_id; // Get the ID of the newly inserted student
        $stmt->close();

        // Process the uploaded file
        $file = $_FILES['driverLicenseUpload'];
        $fileName = $file['name'];
        $fileTmpName = $file['tmp_name'];
        $fileSize = $file['size'];
        $fileError = $file['error'];
        $fileType = $file['type'];

        $fileExt = explode('.', $fileName);
        $fileActualExt = strtolower(end($fileExt));

        // Allowed file types
        $allowed = array('jpg', 'jpeg', 'png', 'pdf');

        if (in_array($fileActualExt, $allowed)) {
            if ($fileError === 0) {
                if ($fileSize < 1000000) { // 1MB limit
                    $fileNameNew = uniqid('', true).".".$fileActualExt; // Unique name for the file
                    $fileDestination = 'uploads/'.$fileNameNew; // Destination in your "uploads" directory

                    move_uploaded_file($fileTmpName, $fileDestination);

                    // Insert file info into the Attachments table
                    $sql = "INSERT INTO Attachments (StudentID, FileName, FilePath) VALUES (?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    if (!$stmt) {
                        throw new Exception("Prepare failed: " . htmlspecialchars($conn->error));
                    }
                    $stmt->bind_param("iss", $student_id, $fileName, $fileDestination);
                    $stmt->execute();
                    $stmt->close();

                } else {
                    throw new Exception("Your file is too big!");
                }
            } else {
                throw new Exception("There was an error uploading your file!");
            }
        } else {
            throw new Exception("You cannot upload files of this type!");
        }

        // If everything is fine, commit the transaction
        $conn->commit();

        // Redirect to login page after successful registration
        header("Location: students-login.html");
        exit;

    } catch (Exception $e) {
        // An error occurred, rollback the transaction and show the error message
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Required fields or file not provided.";
}

$conn->close();
?>

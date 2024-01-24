
<?php

require_once "connect.php";

// Check if username, password, and other fields are set
if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['firstname']) && isset($_POST['lastname']) && isset($_POST['contactinfo']) && isset($_POST['address']) && isset($_POST['dob']) && isset($_POST['email'])) {
    $new_username = $_POST['username'];
    $new_password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
    $new_firstname = $_POST['firstname'];
    $new_lastname = $_POST['lastname'];
    $new_contactinfo = $_POST['contactinfo'];
    $new_address = $_POST['address'];
    $new_dob = $_POST['dob'];
    $new_email = $_POST['email'];

    // Insert the new student into the database
    $sql = "INSERT INTO Students (username, password, firstname, lastname, contactinfo, address, dob, email) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssss", $new_username, $new_password, $new_firstname, $new_lastname, $new_contactinfo, $new_address, $new_dob, $new_email);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        // Redirect to login page after successful registration
        header("Location: students-login.html");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Username, password, or other fields not provided.";
}

$conn->close();
?>
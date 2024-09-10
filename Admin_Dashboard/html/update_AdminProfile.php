<?php
// Include the database connection file
include './connection.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $username = $_POST['username2'];
    $name = $_POST['name2'];
    $email = $_POST['email2'];
    $contact = $_POST['contact2'];
    $userType = $_POST['userType'];
  

    // Validate input
    if (empty($username) || empty($name) || empty($email) || empty($contact)) {
        echo "All fields are required.";
        exit;
    }
 
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
        exit;
    }

    // Check if the database connection was successful
    if ($conn->connect_error) {
        echo "Database connection failed: " . $conn->connect_error;
        exit;
    }

    // Prepare SQL statement
    $sql = "UPDATE users SET name=?, email=?, contact=? WHERE username=? and userType= 'ADMIN'";

    $stmt = $conn->prepare($sql);

    // Print error details if prepare fails
    if (!$stmt) {
        echo "Error preparing statement: " . $conn->error;
        exit;
    }

    // Bind parameters and execute
    $stmt->bind_param("ssss", $name, $email, $contact, $username);

    // Debugging - print statements
    if ($stmt->execute()) {
        echo '<script>alert("Profile updated successfully.");</script>';
        echo "Updated rows: " . $stmt->affected_rows; // To check how many rows were updated
        header("Location: pages_profile.html");
    } else {
        echo "Error updating profile: " . $stmt->error;
    }

    $stmt->close();
}

// Close connection
$conn->close();
?>

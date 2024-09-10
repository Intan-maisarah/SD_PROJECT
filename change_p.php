<?php
session_start();

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include database connection file
include 'connection.php';

if (isset($_SESSION['id']) && isset($_SESSION['username'])) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        function validate($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        $oldPassword = validate($_POST['op']);
        $newPassword = validate($_POST['np']);
        $confirmNewPassword = validate($_POST['c_np']);

        // Basic validation
        if (empty($oldPassword) || empty($newPassword) || empty($confirmNewPassword)) {
            header("Location: security.html?error=All fields are required");
            exit();
        }

        if ($newPassword !== $confirmNewPassword) {
            header("Location: security.html?error=New passwords do not match");
            exit();
        }

        // Fetch the current password from the database
        $userId = $_SESSION['id'];
        $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->bind_result($currentPassword);
        $stmt->fetch();
        $stmt->close();

        // Check if the old password is correct
        if (!password_verify($oldPassword, $currentPassword)) {
            header("Location: security.html?error=Old password is incorrect");
            exit();
        }

        // Hash the new password and update it in the database
        $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $newPasswordHash, $userId);
        if ($stmt->execute()) {
            header("Location: security.html?success=Password changed successfully");
        } else {
            header("Location: security.html?error=An error occurred while changing the password");
        }
        $stmt->close();
    } else {
        header("Location: security.html");
        exit();
    }
} else {
    header("Location: security.html");
    exit();
}

$conn->close();
?>

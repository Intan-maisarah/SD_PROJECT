<?php
include 'connection.php'; // Ensure this path is correct

// Fetch services with status = 'available'
$sql = "SELECT service_name, service_description, image FROM services WHERE status = 'available'";
$result = $conn->query($sql);

// Fetch all results into an array
$services = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $services[] = $row;
    }
}

// Close the database connection
$conn->close();
?>

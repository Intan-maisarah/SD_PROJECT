<?php
include 'connection.php'; 

$sql = "SELECT service_name, service_description, image FROM services WHERE status = 'available'";
$result = $conn->query($sql);

$services = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $services[] = $row;
    }
}
$conn->close();
?>

<?php
// Connect to the database
include('../../connection.php'); // Include your database connection file

// Check if action is set in the URL
$action = isset($_GET['action']) ? $_GET['action'] : 'view';

switch($action) {
    case 'view':
        // View customers
        $query = "SELECT * FROM users WHERE userType = 'user'";
        $result = mysqli_query($conn, $query);

        echo "<h2>Customer List</h2>";
        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>Name</th><th>Email</th><th>Phone Number</th><th>Address</th><th>Actions</th></tr>";
        
        while($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['name'] . "</td>";
            echo "<td>" . $row['email'] . "</td>";
            echo "<td>" . $row['contact'] . "</td>";
            echo "<td>" . $row['address'] . "</td>";
            echo "<td>
                    <a href='customer.php?action=edit&id=" . $row['id'] . "'>Edit</a> | 
                    <a href='customer.php?action=delete&id=" . $row['id'] . "' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                  </td>";
            echo "</tr>";
        }
        
        echo "</table>";
        break;

    case 'edit':
        // Edit customer
        if(isset($_GET['id'])) {
            $id = $_GET['id'];
            if($_SERVER['REQUEST_METHOD'] == 'POST') {
                // Update customer data
                $name = $_POST['name'];
                $email = $_POST['email'];
                $updateQuery = "UPDATE users SET name='$name', email='$email', contact='$contact',address='$address' WHERE id='$id'";
                mysqli_query($conn, $updateQuery);
                header("Location: customer.php?action=view"); // Redirect to view after editing
            } else {
                // Fetch current customer data for editing
                $query = "SELECT * FROM users WHERE id='$id'";
                $result = mysqli_query($conn, $query);
                $customer = mysqli_fetch_assoc($result);
                ?>
                <h2>Edit Customer</h2>
                <form method="POST" action="">
                    <label for="name">Name:</label>
                    <input type="text" name="name" value="<?php echo $customer['name']; ?>" required><br>
                    <label for="email">Email:</label>
                    <input type="email" name="email" value="<?php echo $customer['email']; ?>" required><br>
                    <label for="email">Phone Number:</label>
                    <input type="number" name="contact" value="<?php echo $customer['contact']; ?>" required><br>
                    <label for="email">Address:</label>
                    <input type="text" name="address" value="<?php echo $customer['address']; ?>" required><br>
                    <input type="submit" value="Update">
                </form>
                <?php
            }
        }
        break;

    case 'delete':
        // Delete customer
        if(isset($_GET['id'])) {
            $id = $_GET['id'];
            $deleteQuery = "DELETE FROM users WHERE id='$id'";
            mysqli_query($conn, $deleteQuery);
            header("Location: customer.php?action=view"); // Redirect to view after deleting
        }
        break;

    default:
        // Default action is to view customers
        header("Location: customer.php?action=view");
        break;
}
?>

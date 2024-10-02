<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include the database connection file
include '../connection.php';

session_start();

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    die('User not logged in.');
}

// Fetch user data
$query = "SELECT name, username, email, contact, address FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $name = $row['name'] ?? "N/A";
    $username = $row['username'] ?? "N/A";
    $email = $row['email'] ?? "N/A";
    $contact = $row['contact'] ?? "N/A";
    $address = $row['address'] ?? "N/A";
} else {
    $name = "N/A";
    $username = "N/A";
    $email = "N/A";
    $contact = "N/A";
    $address = "N/A";
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];
    $address = $_POST['address'];

    if (empty($name) || empty($email) || empty($contact) || empty($address)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        $sql = "UPDATE users SET name=?, username=?, email=?, contact=?, address=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssi", $name, $username, $email, $contact, $address, $user_id);

        if ($stmt->execute()) {
            $success = "Profile updated successfully.";
        } else {
            $error = "Error updating profile: " . $stmt->error;
        }

        $stmt->close();
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>View Profile</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet">
<style type="text/css">
    /* Your existing styles */
</style>
</head>
<body>
    <div class="container-xl px-4 mt-4">
        <nav class="nav nav-borders">
            <a class="nav-link active ms-0" href="view_profile.php" target="_self">Profile</a>
            <a class="nav-link" href="update_profile.php" target="_self">Security</a>
            <a class="nav-link ms-auto" href="../index.php" target="_self">Home</a>
        </nav>   
        <hr class="mt-0 mb-4">
        
        <!-- View Profile Section -->
        <div id="viewProfile">
            <div class="row">
                <div class="col-xl-4">
                    <div class="card mb-4 mb-xl-0">
                        <div class="card-header">Profile Picture</div>
                        <div class="card-body text-center">
                            <img id="profileImage" class="img-account-profile rounded-circle mb-2" src="https://bootdey.com/img/Content/avatar/avatar1.png" alt="">
                        </div>
                    </div>
                </div>
                <div class="col-xl-8">
                    <div class="card mb-4">
                        <div class="card-header">Account Details</div>
                        <div class="card-body">
                            <p><strong>Username:</strong> <span id="displayUsername"><?php echo htmlspecialchars($username); ?></span></p>
                            <p><strong>Name:</strong> <span id="displayName"><?php echo htmlspecialchars($name); ?></span></p>
                            <p><strong>Email:</strong> <span id="displayEmail"><?php echo htmlspecialchars($email); ?></span></p>
                            <p><strong>Address:</strong> <span id="displayAddress"><?php echo htmlspecialchars($address); ?></span></p>
                            <p><strong>Phone Number:</strong> <span id="displayContact"><?php echo htmlspecialchars($contact); ?></span></p>
                            <button class="btn btn-secondary" type="button" onclick="toggleUpdate()">Update Profile</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Profile Section -->
        <div id="editProfile" style="display: none;">
            <div class="row">
                <div class="col-xl-4">
                    <div class="card mb-4 mb-xl-0">
                        <div class="card-header">Profile Picture</div>
                        <div class="card-body text-center">
                            <img id="editProfileImage" class="img-account-profile rounded-circle mb-2" src="https://bootdey.com/img/Content/avatar/avatar1.png" alt="">
                            <div class="small font-italic text-muted mb-4">JPG or PNG no larger than 5 MB</div>
                            <button class="btn btn-primary" type="button" onclick="document.getElementById('imageInput').click();">Upload new image</button>
                            <input type="file" id="imageInput" style="display: none;">
                        </div>
                    </div>
                </div>
                <div class="col-xl-8">
                    <div class="card mb-4">
                        <div class="card-header">Edit Account Details</div>
                        <div class="card-body">
                            <form action="update_profile.php" method="POST">
                                <div class="mb-3">
                                    <label class="small mb-1" for="inputUsername">Username</label>
                                    <input class="form-control" name="username" id="inputUsername" type="text" placeholder="Enter your username" value="<?php echo htmlspecialchars($username); ?>" disabled >
                                </div>
                                <div class="row gx-3 mb-3">
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="inputName">Name</label>
                                        <input class="form-control" name="name" id="inputName" type="text" placeholder="Enter your name" value="<?php echo htmlspecialchars($name); ?>" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="inputEmail">Email</label>
                                        <input class="form-control" name="email" id="inputEmail" type="email" placeholder="Enter your email" value="<?php echo htmlspecialchars($email); ?>" >
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="small mb-1" for="inputContact">Phone number</label>
                                    <input class="form-control" name="contact" id="inputContact" type="text" placeholder="Enter your phone number" value="<?php echo htmlspecialchars($contact); ?>" disabled>
                                </div>
                                <div class="mb-3">
                                    <label class="small mb-1" for="inputAddress">Address</label>
                                    <input class="form-control" name="address" id="inputAddress" type="text" placeholder="Enter your address" value="<?php echo htmlspecialchars($address); ?>" required>
                                </div>
                                <button class="btn btn-primary" name="updateButton" type="submit">Update changes</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript">
    function toggleUpdate() {
        var viewSection = document.getElementById('viewProfile');
        var editSection = document.getElementById('editProfile');
        if (viewSection.style.display === 'none') {
            viewSection.style.display = 'block';
            editSection.style.display = 'none';
        } else {
            viewSection.style.display = 'none';
            editSection.style.display = 'block';
        }
    }

    document.getElementById('imageInput').addEventListener('change', function(event) {
        const file = event.target.files[0];

        if (file) {
            if (file.size > 5 * 1024 * 1024) {
                alert('File size exceeds 5 MB');
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('profileImage').src = e.target.result;
                document.getElementById('editProfileImage').src = e.target.result;
            };
            reader.readAsDataURL(file);

            const formData = new FormData();
            formData.append('image', file);

            // Example AJAX request
            // fetch('/upload-endpoint', {
            //     method: 'POST',
            //     body: formData
            // })
            // .then(response => response.json())
            // .then(data => console.log('Image uploaded successfully:', data))
            // .catch(error => console.error('Error uploading image:', error));
        }
    });

    function saveChanges() {
        var username = document.getElementById('inputUsername').value;
        var name = document.getElementById('inputName').value;
        var email = document.getElementById('inputEmail').value;
        var address = document.getElementById('inputAddress').value;
        var contact = document.getElementById('inputContact').value;

        document.getElementById('displayUsername').textContent = username;
        document.getElementById('displayName').textContent = name;
        document.getElementById('displayEmail').textContent = email;
        document.getElementById('displayAddress').textContent = address;
        document.getElementById('displayContact').textContent = contact;

        // Uncomment the line below to show a success message or handle the form submission properly
         alert('Changes saved');
    }
</script>
</body>
</html>

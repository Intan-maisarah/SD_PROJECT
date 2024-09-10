<?php
// Include the database connection file
include 'connection.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $username = $_POST['username'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];
    $address = $_POST['address'];

    // Validate input
    if (empty($username) || empty($name) || empty($email) || empty($contact) || empty($address)) {
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
    $sql = "UPDATE users SET name=?, email=?, contact=?, address=? WHERE username=?";
    $stmt = $conn->prepare($sql);

    // Print error details if prepare fails
    if (!$stmt) {
        echo "Error preparing statement: " . $conn->error;
        exit;
    }

    // Bind parameters and execute
    $stmt->bind_param("sssss", $name, $email, $contact, $address, $username);

    // Debugging - print statements
    if ($stmt->execute()) {
        echo '<script>alert("Profile updated successfully.");</script>';
        echo "Updated rows: " . $stmt->affected_rows; // To check how many rows were updated
        header("Location: view_profile.html");
    } else {
        echo "Error updating profile: " . $stmt->error;
    }

    $stmt->close();
}

// Close connection
$conn->close();
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
            <a class="nav-link active ms-0" href="view_profile.html" target="_self">Profile</a>
            <a class="nav-link" href="security.html" target="_self">Security</a>
            <a class="nav-link ms-auto" href="index.php" target="_self">Home</a>
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
                        <div class="small font-italic text-muted mb-4">JPG or PNG no larger than 5 MB</div>
                        
                        <!-- Hidden file input for image upload -->
                        <input type="file" id="imageInput" accept="image/png, image/jpeg" style="display:none;">
                        
                        <!-- Button triggers the file input -->
                        <button class="btn btn-primary" type="button" onclick="document.getElementById('imageInput').click();">
                            Upload new image
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-xl-8">
                <div class="card mb-4">
                    <div class="card-header">Account Details</div>
                    <div class="card-body">
                        <p><strong>Username:</strong> <span id="displayUsername">JohnDoe</span></p>
                        <p><strong>Name:</strong> <span id="displayName">John</span></p>
                        <p><strong>Email:</strong> <span id="displayEmail">john.doe@example.com</span></p>
                        <p><strong>Address:</strong> <span id="displayAddress">1234 Main St, Kuala Lumpur</span></p>
                        <p><strong>Phone Number:</strong> <span id="displayContact">+123456789</span></p>
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
                                <input class="form-control" name="username" id="inputUsername" type="text" placeholder="Enter your username" required>
                            </div>
                            <div class="row gx-3 mb-3">
                                <div class="col-md-6">
                                    <label class="small mb-1" for="inputName">Name</label>
                                    <input class="form-control" name="name" id="inputName" type="text" placeholder="Enter your name" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="small mb-1" for="inputEmail">Email</label>
                                    <input class="form-control" name="email" id="inputEmail" type="email" placeholder="Enter your email" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="small mb-1" for="inputContact">Phone number</label>
                                <input class="form-control" name="contact" id="inputContact" type="text" placeholder="Enter your phone number" required>
                            </div>
                            <div class="mb-3">
                                <label class="small mb-1" for="inputAddress">Address</label>
                                <input class="form-control" name="address" id="inputAddress" type="text" placeholder="Enter your address" required>
                            </div>
                            <button class="btn btn-primary" name="updateButton" onclick="saveChanges()" type="submit">Update changes</button>
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
    // Toggle between view and edit profile sections
    function toggleInsert() {
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

    // Handle image upload and preview
    document.getElementById('imageInput').addEventListener('change', function(event) {
        const file = event.target.files[0];

        if (file) {
            // Ensure file size is under 5MB
            if (file.size > 5 * 1024 * 1024) {
                alert('File size exceeds 5 MB');
                return;
            }

            // Preview the uploaded image
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('profileImage').src = e.target.result;
                document.getElementById('editProfileImage').src = e.target.result;
            };
            reader.readAsDataURL(file);

            // Image upload logic (AJAX)
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

    // Save changes and update profile view
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

    
    }
</script>
</body>
</html>

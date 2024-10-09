<?php
// Display errors for debugging purposes (disable in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include the database connection file
include '../connection.php';
session_start();

// Fetch user ID from session
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

// Update user data if form is submitted
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
            // Refresh the page to show updated profile data
            header("Location: view_profile.php");
            exit();
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
<link rel="stylesheet" href="../assets/ipasss.css">
</head>
<body>
<br>
    <div class="container-xl px-4 mt-4">
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#"><img src="../assets/images/logo.png" alt="Logo" style="width: 100px; height: auto;"></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <nav>
                <div id="marker"></div>
                <a href="../index.php#home" class="active">Home</a>
                <a href="../index.php#services">Services</a>
                <a href="../index.php#about">About</a>
                <a href="../index.php#contact">Contact</a>
                <a href="../index.php#feedback">Feedback</a>

                </nav>
                <?php if (!isset($_SESSION['signin'])): ?>
                    <div class="nav-item">
                        <button class="btn btn-primary rounded ml-4" onclick="window.location.href='signin.php'">Log In</button>
                    </div>
                    <div class="nav-item">
                        <button class="btn btn-primary rounded ml-4" onclick="window.location.href='signup.php'">Sign Up</button>
                    </div>
                <?php else: ?>
                    <div class="nav-item">
                        <button class="btn btn-primary rounded ml-4" onclick="window.location.href='../logout.php'">Log Out</button>
                    </div>
                    <div class="nav-item">
                        <button class="btn btn-primary rounded ml-4" onclick="window.location.href='view_profile.php'">Profile</button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </nav>
        <hr class="mt-0 mb-4">
    
        <!-- View Profile Section -->
        <div id="viewProfile">
            <div class="row">
                
                <div class="col-xl-8">
                    <div class="card mb-4">
                        <div class="card-header">Account Details</div>
                        <div class="card-body">
                            <p><strong>Username:</strong> <span id="displayUsername"><?php echo htmlspecialchars($username); ?></span></p>
                            <p><strong>Name:</strong> <span id="displayName"><?php echo htmlspecialchars($name); ?></span></p>
                            <p><strong>Email:</strong> <span id="displayEmail"><?php echo htmlspecialchars($email); ?></span></p>
                            <p><strong>Address:</strong> <span id="displayAddress"><?php echo htmlspecialchars($address); ?></span></p>
                            <p><strong>Phone Number:</strong> <span id="displayContact"><?php echo htmlspecialchars($contact); ?></span></p>
                            <button class="btn btn-secondary" type="button" onclick="toggleInsert()">Update Profile</button>
                            <button class="btn btn-secondary" type="button" onclick="window.location.href='change_password.php';">Change Password</button>
                            </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Profile Section -->
        <div id="editProfile" style="display: none;">
            <div class="row">
                
                <div class="col-xl-8">
                    <div class="card mb-4">
                        <div class="card-header">Edit Account Details</div>
                        <div class="card-body">
                            <form action="view_profile.php" method="POST">
                                <div class="mb-3">
                                    <label class="small mb-1" for="inputUsername">Username</label>
                                    <input class="form-control" name="username" id="inputUsername" type="text" placeholder="Enter your username" value="<?php echo htmlspecialchars($username); ?>" required>
                                </div>
                                <div class="row gx-3 mb-3">
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="inputName">Name</label>
                                        <input class="form-control" name="name" id="inputName" type="text" placeholder="Enter your name" value="<?php echo htmlspecialchars($name); ?>" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="inputEmail">Email</label>
                                        <input class="form-control" name="email" id="inputEmail" type="email" placeholder="Enter your email" value="<?php echo htmlspecialchars($email); ?>" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="small mb-1" for="inputContact">Phone number</label>
                                    <input class="form-control" name="contact" id="inputContact" type="text" placeholder="Enter your phone number" value="<?php echo htmlspecialchars($contact); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="small mb-1" for="inputAddress">Address</label>
                                    <input class="form-control" name="address" id="inputAddress" type="text" placeholder="Enter your address" value="<?php echo htmlspecialchars($address); ?>" required>
                                </div>
                                <button class="btn btn-secondary" name="InsertButton" onclick="saveChanges()" type="submit">Save changes</button>
                                <button class="btn btn-secondary" onclick="history.back()">Back</button>

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

    document.addEventListener("DOMContentLoaded", function() {
        const marker = document.getElementById("marker");
        const navLinks = document.querySelectorAll("nav a");
        const sections = document.querySelectorAll("section"); // Ensure all your sections have the correct IDs like #home, #services, etc.

        // Function to move the marker
        function moveMarker(element) {
            marker.style.width = element.offsetWidth + "px";
            marker.style.left = element.offsetLeft + "px";
        }

        // IntersectionObserver callback
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    // Get the active section ID
                    const sectionId = entry.target.id;

                    // Find the corresponding nav link
                    const activeLink = document.querySelector(`nav a[href="#${sectionId}"]`);

                    // Remove active class from all links and add to the current one
                    navLinks.forEach(link => link.classList.remove("active"));
                    activeLink.classList.add("active");

                    // Move the marker
                    moveMarker(activeLink);
                }
            });
        }, {
            threshold: 0.5  // Trigger when 50% of the section is visible
        });

        // Observe each section
        sections.forEach(section => observer.observe(section));

        // Add click event listeners to each navigation link for click-based marker movement
        navLinks.forEach(link => {
            link.addEventListener("click", function() {
                navLinks.forEach(nav => nav.classList.remove("active"));
                this.classList.add("active");
                moveMarker(this);
            });
        });
    });
</script>
</body>
</html>
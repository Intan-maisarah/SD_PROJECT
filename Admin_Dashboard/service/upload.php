<!-- this is a test file-->
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Document</title>
</head>
<body>
    <h1>Upload Document</h1>
    <form action="upload.php" method="post" enctype="multipart/form-data">
        <label for="file">Choose document to upload:</label>
        <input type="file" name="file" id="file" required>
        <input type="submit" value="Upload Document">
    </form>

    <?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    // Check if a file was uploaded
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $uploadDir = 'document_upload/'; // Directory to save uploaded files
        $uploadFile = $uploadDir . basename($_FILES['file']['name']);
    
        // Check for file upload errors
        if ($_FILES['file']['error'] !== UPLOAD_ERR_OK) {
            echo "File upload error: " . $_FILES['file']['error'];
            exit;
        }
    
        // Create uploads directory if it doesn't exist
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
    
        // Move uploaded file to the specified directory
        if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {
            echo "File is valid, and was successfully uploaded.<br>";
            echo "File name: " . htmlspecialchars(basename($_FILES['file']['name']));
        } else {
            echo "An error occurred during file upload.";
        }
    }
    
    ?>
</body>
</html>

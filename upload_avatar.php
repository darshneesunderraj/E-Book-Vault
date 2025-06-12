<?php
session_start();

# Database Connection File
include "db_conn.php"; // Ensure this file creates a PDO connection

# Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Handle the file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['avatar'])) {
        $avatar = $_FILES['avatar'];
        $errors = [];
        $fileName = $avatar['name'];
        $fileTmp = $avatar['tmp_name'];
        $fileSize = $avatar['size'];
        $fileError = $avatar['error'];
        $fileType = $avatar['type'];

        // Define allowed file types and size limit
        $allowed = ['image/jpeg', 'image/png', 'image/gif'];
        $maxFileSize = 2 * 1024 * 1024; // 2MB

        // Validate the uploaded file
        if ($fileError === 0) {
            if ($fileSize > $maxFileSize) {
                $errors[] = "File size exceeds 2MB limit.";
            }

            if (!in_array($fileType, $allowed)) {
                $errors[] = "File type not allowed. Only JPEG, PNG and GIF files are accepted.";
            }

            if (empty($errors)) {
                // Define the upload directory
                $uploadDir = 'uploads/avatars/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true); // Create the directory if it doesn't exist
                }

                // Create a unique file name to prevent overwriting
                $fileNewName = uniqid('', true) . '.' . pathinfo($fileName, PATHINFO_EXTENSION);
                $fileDestination = $uploadDir . $fileNewName;

                // Move the uploaded file to the destination
                if (move_uploaded_file($fileTmp, $fileDestination)) {
                    // Update the user's avatar in the database
                    $sql = "UPDATE users SET avatar = ? WHERE id = ?";
                    $stmt = $conn->prepare($sql);
                    if ($stmt->execute([$fileNewName, $user_id])) {
                        header("Location: dashboard.php?success=Avatar updated successfully.");
                        exit;
                    } else {
                        $errors[] = "Failed to update avatar in database.";
                    }
                } else {
                    $errors[] = "Failed to move uploaded file.";
                }
            }
        } else {
            $errors[] = "Error during file upload.";
        }
    } else {
        $errors[] = "No file uploaded.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Avatar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h3>Edit Your Avatar</h3>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form action="upload_avatar.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="avatar" class="form-label">Choose a new avatar:</label>
                <input type="file" name="avatar" id="avatar" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Upload Avatar</button>
            <a href="user_dashboard.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

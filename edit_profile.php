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

// Fetch user profile information
$sql = "SELECT username, avatar, interests, genres FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle the form submission for profile and avatar update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Update profile information
    $username = $_POST['username'];
    $interests = $_POST['interests'];
    $genres = $_POST['genres'];

    $update_sql = "UPDATE users SET username = ?, interests = ?, genres = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->execute([$username, $interests, $genres, $user_id]);

    // Handle avatar upload
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === 0) {
        $avatar = $_FILES['avatar'];
        $fileName = $avatar['name'];
        $fileTmp = $avatar['tmp_name'];
        $fileSize = $avatar['size'];
        $fileError = $avatar['error'];
        $fileType = $avatar['type'];

        // Define allowed file types and size limit
        $allowed = ['image/jpeg', 'image/png', 'image/gif'];
        $maxFileSize = 2 * 1024 * 1024; // 2MB

        // Validate the uploaded file
        if ($fileSize > $maxFileSize) {
            echo "<script>alert('File size exceeds 2MB limit.');</script>";
        } elseif (!in_array($fileType, $allowed)) {
            echo "<script>alert('File type not allowed. Only JPEG, PNG, and GIF files are accepted.');</script>";
        } else {
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
                $avatar_sql = "UPDATE users SET avatar = ? WHERE id = ?";
                $avatar_stmt = $conn->prepare($avatar_sql);
                $avatar_stmt->execute([$fileNewName, $user_id]);
            } else {
                echo "<script>alert('Failed to move uploaded file.');</script>";
            }
        }
    }
    
    header("Location: user_dashboard.php?success=Profile updated successfully.");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h3>Edit Your Profile</h3>

        <form action="edit_profile.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="username" class="form-label">Username:</label>
                <input type="text" name="username" id="username" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="interests" class="form-label">Interests:</label>
                <input type="text" name="interests" id="interests" class="form-control" value="<?php echo htmlspecialchars($user['interests']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="genres" class="form-label">Favorite Genres:</label>
                <input type="text" name="genres" id="genres" class="form-control" value="<?php echo htmlspecialchars($user['genres']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="avatar" class="form-label">Choose a new avatar (optional):</label>
                <input type="file" name="avatar" id="avatar" class="form-control" accept="image/*">
            </div>
            <button type="submit" class="btn btn-primary">Update Profile</button>
            <a href="user_dashboard.php" class="btn btn-secondary">Cancel</a>
        </form>

        <div class="mt-4">
            <?php if (!empty($user['avatar'])): ?>
                <h4>Current Avatar:</h4>
                <img src="uploads/avatars/<?php echo htmlspecialchars($user['avatar']); ?>" alt="Avatar" width="100" height="100" class="rounded-circle">
            <?php else: ?>
                <div class="avatar-placeholder">??</div> <!-- Placeholder for avatar -->
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Close database connections
$conn = null; // For PDO, set the connection to null to close it
?>

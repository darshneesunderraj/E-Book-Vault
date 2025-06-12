<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id']; // Logged-in user

// Database connection
$conn = new mysqli('localhost', 'root', 'password', 'library');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user profile information
$sql = "SELECT username, avatar, interests, genres FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_result = $stmt->get_result();
$user = $user_result->fetch_assoc();

// Fetch downloaded books
$downloads_sql = "SELECT books.title, books.author, books.genre 
                  FROM downloads 
                  JOIN books ON downloads.book_id = books.id 
                  WHERE downloads.user_id = ?";
$downloads_stmt = $conn->prepare($downloads_sql);
$downloads_stmt->bind_param("i", $user_id);
$downloads_stmt->execute();
$downloads_result = $downloads_stmt->get_result();

// Suggest books based on user's favorite genres
$suggest_sql = "SELECT books.title, books.author 
                FROM books 
                WHERE books.genre IN (
                    SELECT genres FROM users WHERE id = ?
                ) LIMIT 5";
$suggest_stmt = $conn->prepare($suggest_sql);
$suggest_stmt->bind_param("i", $user_id);
$suggest_stmt->execute();
$suggest_result = $suggest_stmt->get_result();

// Fetch pending follow requests
$request_sql = "SELECT follow_requests.id, users.username 
                FROM follow_requests 
                JOIN users ON follow_requests.sender_id = users.id 
                WHERE follow_requests.receiver_id = ? 
                AND follow_requests.status = 'pending'";
$request_stmt = $conn->prepare($request_sql);
$request_stmt->bind_param("i", $user_id);
$request_stmt->execute();
$requests_result = $request_stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <!-- User Profile Section -->
        <div class="profile">
            <img src="uploads/avatars/<?php echo $user['avatar']; ?>" alt="Avatar" width="100" height="100">
            <h3><?php echo $user['username']; ?></h3>
            <p>Interests: <?php echo $user['interests']; ?></p>
            <p>Favorite Genres: <?php echo $user['genres']; ?></p>
        </div>

        <!-- Downloaded Books Section -->
        <h3>Your Downloaded Books</h3>
        <?php while ($book = $downloads_result->fetch_assoc()) { ?>
            <p><?php echo $book['title'] . " by " . $book['author'] . " (Genre: " . $book['genre'] . ")"; ?></p>
        <?php } ?>

        <!-- Suggested Books Section -->
        <h3>Suggested Books</h3>
        <?php while ($suggestion = $suggest_result->fetch_assoc()) { ?>
            <p><?php echo $suggestion['title'] . " by " . $suggestion['author']; ?></p>
        <?php } ?>

        <!-- Follow Requests Section -->
        <h3>Follow Requests</h3>
        <?php while ($request = $requests_result->fetch_assoc()) { ?>
            <p><?php echo $request['username']; ?></p>
            <form method="POST" action="manage_request.php">
                <input type="hidden" name="request_id" value="<?php echo $request['id']; ?>">
                <button type="submit" name="action" value="accept" class="btn btn-success">Accept</button>
                <button type="submit" name="action" value="decline" class="btn btn-danger">Decline</button>
            </form>
        <?php } ?>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Close database connections
$conn->close();
?>

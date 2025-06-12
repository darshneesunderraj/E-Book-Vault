<?php
session_start();
include "db_conn.php";

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "You need to log in first.";
    exit;
}

$user_id = $_SESSION['user_id'];
$message = "";

if (isset($_POST['request_id']) && isset($_POST['action'])) {
    $request_id = intval($_POST['request_id']);
    $action = $_POST['action'];

    try {
        if ($action === 'accept') {
            $update_sql = "UPDATE follow_requests SET status = 'accepted' WHERE id = ? AND receiver_id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->execute([$request_id, $user_id]);
            $message = "Follow request accepted.";
        } elseif ($action === 'decline') {
            $delete_sql = "DELETE FROM follow_requests WHERE id = ? AND receiver_id = ?";
            $delete_stmt = $conn->prepare($delete_sql);
            $delete_stmt->execute([$request_id, $user_id]);
            $message = "Follow request declined.";
        }
    } catch (PDOException $e) {
        $message = "Database error: " . $e->getMessage();
    }
}

try {
    $sql = "SELECT username, avatar, interests FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    $downloads_sql = "
        SELECT b.title, b.cover, b.file, d.download_date, c.name AS genre 
        FROM downloads d
        JOIN books b ON d.book_id = b.id
        JOIN categories c ON b.category_id = c.id 
        WHERE d.user_id = ?";
    $downloads_stmt = $conn->prepare($downloads_sql);
    $downloads_stmt->execute([$user_id]);
    $downloads_result = $downloads_stmt->fetchAll(PDO::FETCH_ASSOC);

    $suggest_sql = "
        SELECT DISTINCT b.id, b.title, b.cover, b.file
        FROM books b
        JOIN categories c ON b.category_id = c.id
        WHERE b.category_id IN (
            SELECT DISTINCT b.category_id 
            FROM downloads d
            JOIN books b ON d.book_id = b.id
            WHERE d.user_id = ?
        )
        AND b.id NOT IN (
            SELECT book_id FROM downloads WHERE user_id = ?
        )
        LIMIT 5";
    $suggest_stmt = $conn->prepare($suggest_sql);
    $suggest_stmt->execute([$user_id, $user_id]);
    $suggest_result = $suggest_stmt->fetchAll(PDO::FETCH_ASSOC);

    $request_sql = "
        SELECT fr.id, u.username
        FROM follow_requests fr
        JOIN users u ON fr.sender_id = u.id
        WHERE fr.receiver_id = ? AND fr.status = 'pending'";
    $request_stmt = $conn->prepare($request_sql);
    $request_stmt->execute([$user_id]);
    $requests_result = $request_stmt->fetchAll(PDO::FETCH_ASSOC);
    $num_requests = count($requests_result);

    $suggest_users_sql = "
        SELECT u.id, u.username, u.avatar,
        CASE 
            WHEN u.id IN (
                SELECT DISTINCT d.user_id 
                FROM downloads d 
                JOIN books b ON d.book_id = b.id 
                WHERE b.category_id IN (
                    SELECT DISTINCT b.category_id 
                    FROM downloads d2 
                    JOIN books b ON d2.book_id = b.id 
                    WHERE d2.user_id = ?
                )
            ) THEN 'You both downloaded the same book.'
            ELSE 'You have similar interests.'
        END AS reason 
        FROM users u 
        WHERE u.id != ? 
        AND (u.interests LIKE ? OR u.id IN (
            SELECT DISTINCT d.user_id 
            FROM downloads d 
            JOIN books b ON d.book_id = b.id 
            WHERE b.category_id IN (
                SELECT DISTINCT b.category_id 
                FROM downloads d2 
                JOIN books b ON d2.book_id = b.id 
                WHERE d2.user_id = ?
            )
        ))
        LIMIT 5";
    $suggest_users_stmt = $conn->prepare($suggest_users_sql);
    $user_interest = '%' . $user['interests'] . '%';
    $suggest_users_stmt->execute([$user_id, $user_id, $user_interest, $user_id]);
    $suggest_users_result = $suggest_users_stmt->fetchAll(PDO::FETCH_ASSOC);

    $followers_sql = "SELECT COUNT(*) as count FROM follow_requests WHERE receiver_id = ? AND status = 'accepted'";
    $followers_stmt = $conn->prepare($followers_sql);
    $followers_stmt->execute([$user_id]);
    $followers_count = $followers_stmt->fetch(PDO::FETCH_ASSOC)['count'];

    $following_sql = "SELECT COUNT(*) as count FROM follow_requests WHERE sender_id = ? AND status = 'accepted'";
    $following_stmt = $conn->prepare($following_sql);
    $following_stmt->execute([$user_id]);
    $following_count = $following_stmt->fetch(PDO::FETCH_ASSOC)['count'];

} catch (PDOException $e) {
    $message = "Database error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .logout-container {
            background-color: white;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .followers-following {
            display: flex;
            justify-content: center;
            margin-bottom: 5px;
            gap: 20px; /* Adjust gap between followers and following */
        }
        .followers-following div {
            text-align: center;
            color: black;
            font-size: 16px;
        }
        .sidebar-section {
            background-color: lightgrey;
            padding: 5px; /* Further reduced padding */
            border-radius: 10px;
            margin-bottom: 5px;
            min-width: 150px; /* Further reduced minimum width */
        }
        .book-cover {
            width: 150px;
            height: 200px;
            object-fit: cover;
        }
        .book-info-hover {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            color: white;
            display: none;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 10px;
        }
        .card-book {
            width: 150px;
            height: 250px;
            position: relative;
            margin-right: 15px;
            margin-bottom: 15px;
        }
        .follow-requests {
            background-color: lightgrey;
            padding: 5px; /* Further reduced padding */
            border-radius: 10px;
            min-width: 150px; /* Further reduced minimum width */
        }
        .request-count {
            font-size: 18px;
            background-color: #e0e0e0;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
            text-align: center;
        }
        .request-item {
            margin-bottom: 10px;
        }
        .suggested-user {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
        }
        .suggested-user img {
            flex-shrink: 0;
        }
        .suggested-user .user-info {
            display: flex;
            flex-direction: column;
        }
        .suggested-user .username {
            font-weight: bold;
            margin: 0;
        }
        .suggested-user .reason {
            font-size: 0.85rem;
            color: #6c757d;
            margin: 0;
        }
        .content-row {
            display: flex;
            justify-content: space-between;
        }
        .content-left {
            flex: 2; /* Increased size for left content */
            margin: 10px;
        }
        .content-right {
            flex: 1; /* Reduced size for right content */
            margin: 10px;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="logout-container">
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>

        <div class="row">
            <div class="col-12 mb-4 text-center">
                <?php if (!empty($user['avatar'])): ?>
                    <img src="uploads/avatars/<?php echo htmlspecialchars($user['avatar']); ?>" alt="Avatar" width="100" height="100" class="rounded-circle">
                <?php else: ?>
                    <div class="avatar-placeholder">??</div>
                <?php endif; ?>
                <h2><?php echo htmlspecialchars($user['username']); ?></h2>
                <div class="followers-following">
                    <div>Followers: <?php echo $followers_count; ?></div>
                    <div>Following: <?php echo $following_count; ?></div>
                </div>
                <p>Interests: <?php echo htmlspecialchars($user['interests']); ?></p>
                <div class="followers-following">
                    <a href="edit_profile.php" class="btn btn-primary">Edit Profile</a>
                    <a href="store.php" class="btn btn-success">Visit Store</a>
                </div>
            </div>
        </div>

        <div class="content-row">
            <div class="content-left">
                <h4>Downloaded Books</h4>
                <div class="row">
                    <?php foreach ($downloads_result as $book): ?>
                        <div class="col-3 card-book">
                            <img src="uploads/cover/<?php echo htmlspecialchars($book['cover']); ?>" alt="<?php echo htmlspecialchars($book['title']); ?>" class="book-cover">
                            <div class="book-info-hover">
                                <a href="uploads/cover/<?php echo htmlspecialchars($book['file']); ?>" class="btn btn-light">Download</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <h4>Suggested Books</h4>
                <div class="row">
                    <?php foreach ($suggest_result as $book): ?>
                        <div class="col-3 card-book">
                            <a href="book_details.php?book_id=<?php echo $book['id']; ?>" style="text-decoration: none; color: inherit;">
                                <img src="uploads/cover/<?php echo htmlspecialchars($book['cover']); ?>" alt="<?php echo htmlspecialchars($book['title']); ?>" class="book-cover">
                                <div class="book-info-hover">
                                    <a href="uploads/cover/<?php echo htmlspecialchars($book['file']); ?>" class="btn btn-light">Download</a>
                                </div>
                            </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="content-right">
                <div class="follow-requests sidebar-section">
                    <h4>Follow Requests (<?php echo $num_requests; ?>)</h4>
                    <?php if ($num_requests > 0): ?>
                        <?php foreach ($requests_result as $request): ?>
                            <div class="request-item">
                                <span><?php echo htmlspecialchars($request['username']); ?></span>
                                <form action="" method="POST" style="display:inline;">
                                    <input type="hidden" name="request_id" value="<?php echo $request['id']; ?>">
                                    <button type="submit" name="action" value="accept" class="btn btn-success btn-sm">Accept</button>
                                    <button type="submit" name="action" value="decline" class="btn btn-danger btn-sm">Decline</button>
                                </form>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No follow requests.</p>
                    <?php endif; ?>
                </div>

                <div class="suggested-users sidebar-section">
                <h4>Suggested Followers</h4>
                <?php 
                $suggested_followers = isset($_SESSION['follow_requests']) ? $_SESSION['follow_requests'] : [];
                foreach ($suggest_users_result as $suggested): ?>
                    <div class="suggested-user">
                        <img src="uploads/avatars/<?php echo htmlspecialchars($suggested['avatar']); ?>" alt="<?php echo htmlspecialchars($suggested['username']); ?>" width="40" height="40" class="rounded-circle">
                        <div class="user-info">
                            <p class="username"><?php echo htmlspecialchars($suggested['username']); ?></p>
                            <p class="reason"><?php echo htmlspecialchars($suggested['reason']); ?></p>
                            <form action="follow_user.php" method="POST" style="display:inline;">
                                <input type="hidden" name="follow_user_id" value="<?php echo $suggested['id']; ?>">
                                <?php if (in_array($suggested['id'], $suggested_followers)): ?>
                                    <button type="button" class="btn btn-secondary btn-sm" disabled>Requested</button>
                                <?php else: ?>
                                    <button type="submit" class="btn btn-primary btn-sm">Follow</button>
                                <?php endif; ?>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

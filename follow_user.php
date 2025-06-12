<?php
session_start();

// Database connection using PDO
include "db_conn.php"; // Ensure this file creates a PDO connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Check if a user ID was provided
if (isset($_POST['follow_user_id'])) {
    $follow_user_id = intval($_POST['follow_user_id']);

    try {
        // Check if a follow request already exists
        $check_sql = "SELECT * FROM follow_requests WHERE sender_id = ? AND receiver_id = ? AND status = 'pending'";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->execute([$user_id, $follow_user_id]);

        if ($check_stmt->rowCount() == 0) {
            // Insert a follow request into the database
            $follow_sql = "INSERT INTO follow_requests (sender_id, receiver_id, status) VALUES (?, ?, 'pending')";
            $follow_stmt = $conn->prepare($follow_sql);
            $follow_stmt->execute([$user_id, $follow_user_id]);

            // Store followed users in session for button state (optional)
            if (!isset($_SESSION['follow_requests'])) {
                $_SESSION['follow_requests'] = [];
            }
            $_SESSION['follow_requests'][] = $follow_user_id;

            // Redirect back to the dashboard with a success message
            $_SESSION['message'] = "Follow request sent successfully.";
        } else {
            $_SESSION['error'] = "You have already sent a follow request to this user.";
        }

        header("Location: user_dashboard.php");
        exit;

    } catch (PDOException $e) {
        $_SESSION['error'] = "Database error: " . $e->getMessage();
        header("Location: user_dashboard.php");
        exit;
    }
} else {
    // Redirect back to the dashboard if no user ID was provided
    $_SESSION['error'] = "No user selected to follow.";
    header("Location: user_dashboard.php");
    exit;
}
?>

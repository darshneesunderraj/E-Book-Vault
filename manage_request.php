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

if (isset($_POST['request_id']) && isset($_POST['action'])) {
    $request_id = intval($_POST['request_id']);
    $action = $_POST['action'];

    try {
        if ($action === 'accept') {
            // Update follow request status to accepted
            $update_sql = "UPDATE follow_requests SET status = 'accepted' WHERE id = ? AND receiver_id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->execute([$request_id, $user_id]);
            
            $_SESSION['message'] = "Follow request accepted.";
        } elseif ($action === 'decline') {
            // Delete follow request
            $delete_sql = "DELETE FROM follow_requests WHERE id = ? AND receiver_id = ?";
            $delete_stmt = $conn->prepare($delete_sql);
            $delete_stmt->execute([$request_id, $user_id]);
            
            $_SESSION['message'] = "Follow request declined.";
        }

        header("Location: user_dashboard.php");
        exit;
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
} else {
    $_SESSION['error'] = "Invalid request.";
    header("Location: user_dashboard.php");
    exit;
}
?>

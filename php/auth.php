<?php 
session_start();

if (isset($_POST['email']) && isset($_POST['password']) && isset($_POST['role'])) {
    include "../db_conn.php";  // Include database connection
    include "func-validation.php";  // Include validation functions

    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];  // Get selected role

    is_empty($email, "Email", "../login.php", "error", "");
    is_empty($password, "Password", "../login.php", "error", "");

    if ($role === 'admin') {
        // Admin login logic
        $sql = "SELECT * FROM admin WHERE email = ?";
    } else {
        // User login logic
        $sql = "SELECT * FROM users WHERE email = ?";
    }

    $stmt = $conn->prepare($sql);
    
    if ($stmt->execute([$email])) {
        if ($stmt->rowCount() === 1) {
            $user = $stmt->fetch();
            $user_id = $user['id'];
            $user_email = $user['email'];
            $user_password = $user['password'];

            if (password_verify($password, $user_password)) {
                $_SESSION['user_id'] = $user_id;
                $_SESSION['user_email'] = $user_email;
                $_SESSION['user_role'] = $role;  // Set user role based on selection
                header("Location: ../" . ($role === 'admin' ? 'admin.php' : 'user_dashboard.php')); // Redirect based on role
                exit;
            } else {
                $em = "Incorrect username or password";
                header("Location: ../login.php?error=" . urlencode($em));
                exit;
            }
        } else {
            $em = "Incorrect username or password";
            header("Location: ../login.php?error=" . urlencode($em));
            exit;
        }
    } else {
        echo "Error executing query: " . implode(":", $stmt->errorInfo());
    }
} else {
    $em = "Email, password, and role must be provided.";
    header("Location: ../login.php?error=" . urlencode($em));
    exit;
}

<?php
session_start();
include 'db_conn.php'; // Include your database connection

// If the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $auth_method = 'register'; // Manual registration

    // Validation
    if (empty($name) || empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "All fields are required";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match";
    } else {
        // Check if email or username already exists
        $sql = "SELECT * FROM users WHERE email = ? OR username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$email, $username]);

        if ($stmt->rowCount() > 0) {
            $error = "Email or Username is already registered";
        } else {
            // Hash the password
            $password_hashed = password_hash($password, PASSWORD_DEFAULT);

            // Insert new user into the database
            $sql = "INSERT INTO users (name, username, email, password, auth_method) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            if ($stmt->execute([$name, $username, $email, $password_hashed, $auth_method])) {
                $success = "Registered successfully";
            } else {
                $error = "Failed to register";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>REGISTER</title>

    <!-- Bootstrap 5 CDN-->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
    <!-- Bootstrap 5 JS Bundle CDN-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>

</head>
<body>
	<div class="d-flex justify-content-center align-items-center" style="min-height: 100vh;">
		<form class="p-5 rounded shadow" style="max-width: 30rem; width: 100%" method="POST" action="">
		  <h1 class="text-center display-4 pb-5">REGISTER</h1>

          <!-- Display Error Message -->
		  <?php if (isset($error)) { ?>
          <div class="alert alert-danger" role="alert">
			  <?=htmlspecialchars($error); ?>
		  </div>
		  <?php } ?>

          <!-- Display Success Message -->
		  <?php if (isset($success)) { ?>
          <div class="alert alert-success" role="alert">
			  <?=htmlspecialchars($success); ?>
		  </div>
		  <?php } ?>

          <!-- Full Name -->
		  <div class="mb-3">
		    <label for="name" class="form-label">Full Name</label>
		    <input type="text" class="form-control" name="name" id="name" required>
		  </div>

          <!-- Username -->
		  <div class="mb-3">
		    <label for="username" class="form-label">Username</label>
		    <input type="text" class="form-control" name="username" id="username" required>
		  </div>

          <!-- Email -->
		  <div class="mb-3">
		    <label for="exampleInputEmail1" class="form-label">Email address</label>
		    <input type="email" class="form-control" name="email" id="exampleInputEmail1" required>
		  </div>

          <!-- Password -->
		  <div class="mb-3">
		    <label for="exampleInputPassword1" class="form-label">Password</label>
		    <input type="password" class="form-control" name="password" id="exampleInputPassword1" required>
		  </div>

          <!-- Confirm Password -->
		  <div class="mb-3">
		    <label for="exampleConfirmPassword1" class="form-label">Confirm Password</label>
		    <input type="password" class="form-control" name="confirm_password" id="exampleConfirmPassword1" required>
		  </div>

          <!-- Submit Button -->
		  <button type="submit" class="btn btn-primary">Register</button>
		  <a href="login.php" class="btn btn-primary mx-2">Login</a>
		</form>
	</div>
</body>
</html>

<?php 
session_start();

# Database Connection File
include "db_conn.php";

# Book helper function
include "php/func-book.php";
$books = get_all_books($conn);

# Author helper function
include "php/func-author.php";
$authors = get_all_author($conn);

# Category helper function
include "php/func-category.php";
$categories = get_all_categories($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> E BOOK VAULT! </title>

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" href="css/style.css">
    <style>
        .card {
            width: 100%; /* Make the card take the full width of the column */
            margin-bottom: 20px; /* Space between cards */
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php">E BOOK VAULT!</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php">About</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="store.php">Store</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="contact.php">Contact</a>
                        </li>

                        <!-- Only show "User Dashboard" if a regular user is logged in -->
                        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'user') { ?>
                            <li class="nav-item">
                                <a class="nav-link" href="user_dashboard.php">User Dashboard</a>
                            </li>
                        <?php } ?>

                        <!-- Only show "Admin" if the logged-in user is an admin -->
                        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') { ?>
                            <li class="nav-item">
                                <a class="nav-link" href="admin.php">Admin</a>
                            </li>
                        <?php } ?>

                        <!-- Show Login and Register if no user is logged in -->
                        <?php if (!isset($_SESSION['user_id'])) { ?>
                            <li class="nav-item">
                                <a class="nav-link" href="login.php">Login</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="register.php">Register</a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Search Form -->
        <form action="search.php" method="get" class="my-5" style="max-width: 30rem;">
            <div class="input-group">
                <input type="text" class="form-control" name="key" placeholder="Search Book..." aria-label="Search Book...">
                <button class="input-group-text btn btn-primary" type="submit">
                    <img src="img/search.png" width="20" alt="Search">
                </button>
            </div>
        </form>

        <!-- Book Display Section -->
        <div class="row pt-4">
            <?php if (count($books) == 0) { ?>
                <div class="alert alert-warning text-center p-5 col-12">
                    <img src="img/empty.png" width="100" alt="No Books">
                    <br>No books available in the store.
                </div>
            <?php } else { ?>
                <?php foreach ($books as $book) { ?>
                    <div class="col-md-3 col-sm-6">
                        <div class="card">
                            <!-- Corrected book cover height -->
                            <img src="uploads/cover/<?= htmlspecialchars($book['cover']) ?>" 
                                 class="card-img-top" 
                                 style="height: 300px; object-fit: cover;" 
                                 alt="<?= htmlspecialchars($book['title']) ?>">
                            <div class="card-body">
                                <h5 class="card-title" style="font-size: 1rem;"><?= htmlspecialchars($book['title']) ?></h5>
                                <p class="card-text" style="font-size: 0.9rem;">
                                    <i><b>By:
                                        <?php foreach ($authors as $author) { 
                                            if ($author['id'] == $book['author_id']) {
                                                echo htmlspecialchars($author['name']);
                                                break;
                                            }
                                        } ?>
                                    </b></i><br>
                                    <?= htmlspecialchars(substr($book['description'], 0, 100)) ?>... <!-- Truncated description -->
                                </p>
                                <a href="book_details.php?id=<?= $book['id'] ?>" class="btn btn-info">Read More</a> <!-- Redirect to book details page -->
                            </div>
                        </div>
                    </div>
                <?php } ?>
            <?php } ?>
        </div>
    </div>
</body>
</html>

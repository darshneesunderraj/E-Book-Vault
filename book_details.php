<?php 
session_start();

// Database Connection File
include "db_conn.php";

// Book helper function
include "php/func-book.php";  // Ensure this file includes all book-related functions

// Author helper function
include "php/func-author.php"; // Include the author functions

// Check if ID is set and valid
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $book_id = intval($_GET['id']); // Get the book ID from the URL
    $book = get_book($conn, $book_id); // Call the correct function
} else {
    die("Invalid book ID."); // Handle invalid ID
}

// Check if the book was found
if (!$book) {
    die("Book not found."); // Handle the case where no book is found
}

// Check if the user has already downloaded this book
$user_id = $_SESSION['user_id'];
$has_downloaded = false;

if ($user_id) {
    $check_download_sql = "SELECT COUNT(*) FROM downloads WHERE user_id = ? AND book_id = ?";
    $check_download_stmt = $conn->prepare($check_download_sql);
    $check_download_stmt->execute([$user_id, $book_id]);
    $has_downloaded = $check_download_stmt->fetchColumn() > 0;
}

// Force download the PDF if 'download' is set in the URL
if (isset($_GET['download']) && !$has_downloaded) {
    // Insert download into the database if not downloaded yet
    $insert_sql = "INSERT INTO downloads (user_id, book_id, download_date) VALUES (?, ?, NOW())";
    $insert_stmt = $conn->prepare($insert_sql);
    $insert_stmt->execute([$user_id, $book_id]);

    // Path to the file
    $file_path = 'uploads/files/' . $book['file'];

    // Force download the PDF
    if (file_exists($file_path)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file_path));
        readfile($file_path);
        exit;
    } else {
        die("Error: File not found.");
    }
}

// Retrieve author details
$author_id = $book['author_id']; // Fetch author ID from the book
$author = get_author($conn, $author_id); // Now this function should work

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($book['title']) ?>V-LIBRARY</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmDownload() {
            return confirm("You have already downloaded this book. Do you want to download it again?");
        }
    </script>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">V-LIBRARY</a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="store.php">Store</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="user_dashboard.php">User Dashboard</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-4">
            <img src="uploads/cover/<?= htmlspecialchars($book['cover']) ?>" class="img-fluid" style="height: 300px; object-fit: cover;" alt="<?= htmlspecialchars($book['title']) ?>">
        </div>
        <div class="col-md-8">
            <h1><?= htmlspecialchars($book['title']) ?></h1>
            <p><strong>Description:</strong> <?= nl2br(htmlspecialchars($book['description'])) ?></p>
            <p><strong>Author:</strong> <?= htmlspecialchars($author['name']) ?></p>

            <!-- Download Button -->
            <a href="?id=<?= $book_id ?>&download=true" 
               class="btn <?= $has_downloaded ? 'btn-warning' : 'btn-primary' ?>" 
               <?= $has_downloaded ? 'onclick="return confirmDownload();"' : '' ?>>
                <?= $has_downloaded ? 'Downloaded' : 'Download' ?>
            </a>
        </div>
    </div>
</div>
</body>
</html>

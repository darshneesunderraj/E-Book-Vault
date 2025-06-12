<?php

# Get All Books Function
function get_all_books($con) {
    $sql = "SELECT * FROM books ORDER BY id DESC"; // Fixed the casing in 'ORDER BY'
    $stmt = $con->prepare($sql);
    $stmt->execute();

    // Fetch all books, or return an empty array instead of 0
    return $stmt->rowCount() > 0 ? $stmt->fetchAll() : [];
}

# Get Book by ID Function
function get_book($con, $id) {
    $sql = "SELECT * FROM books WHERE id = ?"; // Added space for readability
    $stmt = $con->prepare($sql);
    $stmt->execute([$id]);

    // Return the book or null if not found
    return $stmt->rowCount() > 0 ? $stmt->fetch() : null;
}

# Search Books Function
function search_books($con, $key) {
    $key = "%{$key}%"; // Use wildcards for LIKE clause

    $sql = "SELECT * FROM books 
            WHERE title LIKE ? OR description LIKE ?";
    $stmt = $con->prepare($sql);
    $stmt->execute([$key, $key]);

    // Return found books or an empty array
    return $stmt->rowCount() > 0 ? $stmt->fetchAll() : [];
}

# Get Books by Category Function
function get_books_by_category($con, $id) {
    $sql = "SELECT * FROM books WHERE category_id = ?";
    $stmt = $con->prepare($sql);
    $stmt->execute([$id]);

    // Return found books or an empty array
    return $stmt->rowCount() > 0 ? $stmt->fetchAll() : [];
}

# Get Books by Author Function
function get_books_by_author($con, $id) {
    $sql = "SELECT * FROM books WHERE author_id = ?";
    $stmt = $con->prepare($sql);
    $stmt->execute([$id]);

    // Return found books or an empty array
    return $stmt->rowCount() > 0 ? $stmt->fetchAll() : [];
}

?>

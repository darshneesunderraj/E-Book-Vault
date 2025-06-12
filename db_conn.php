<?php 

$sName = "localhost";
$uName = "root";
$pass = "";
$db_name = "library";

try {

    $conn = new PDO("mysql:host=$sName;dbname=$db_name;charset=utf8mb4", $uName, $pass);
    
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $conn->exec("SET NAMES 'utf8mb4'");
    
    $conn->exec("SET time_zone = '+00:00'"); 
    
    echo ""; 
} catch(PDOException $e) {
   
    error_log("Connection failed: " . $e->getMessage()); 
    echo "Database connection error. Please try again later.";
}

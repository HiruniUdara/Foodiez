<?php
/**
 * Database connection using PDO.
 * Handles the connection to 'foodiez_db'.
 */

// Database credentials
$host = "127.0.0.1";
$port = "3307";
$db_name = "foodiez_db";
$username = "root";
$password = ""; // Default XAMPP password is empty

try {
    // Creating a new PDO instance with the specified port
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db_name;charset=utf8", $username, $password);
    
    // Setting error mode to exception for better debugging
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Setting default fetch mode to associative array
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    // If connection fails, display error and stop execution
    die("Connection failed: " . $e->getMessage());
}
?>

<?php
/**
 * User Registration Handler.
 * Processes the registration form data.
 */

// Start session
session_start();

// Include database connection and helper functions
require_once '../includes/db.php';
require_once '../includes/functions.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input
    $username = sanitize_input($_POST['username']);
    $email = sanitize_input($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Basic validation
    if (empty($username) || empty($email) || empty($password)) {
        $_SESSION['error'] = "All fields are required.";
        header("Location: ../login.php#register");
        exit();
    }

    if (!is_valid_email($email)) {
        $_SESSION['error'] = "Invalid email format.";
        header("Location: ../login.php#register");
        exit();
    }

    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match.";
        header("Location: ../login.php#register");
        exit();
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Check if email already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        
        if ($stmt->rowCount() > 0) {
            $_SESSION['error'] = "Email already registered.";
            header("Location: ../login.php#register");
            exit();
        }

        // Insert new user into database
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        if ($stmt->execute([$username, $email, $hashed_password])) {
            $_SESSION['success'] = "Registration successful! Please login.";
            header("Location: ../login.php#login");
            exit();
        } else {
            $_SESSION['error'] = "Something went wrong. Please try again.";
            header("Location: ../login.php#register");
            exit();
        }

    } catch (PDOException $e) {
        $_SESSION['error'] = "Database error: " . $e->getMessage();
        header("Location: ../login.php#register");
        exit();
    }
} else {
    // If accessed directly, redirect to registration form
    header("Location: ../login.php#register");
    exit();
}
?>

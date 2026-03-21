<?php
/**
 * User Login Handler.
 * Processes the login form data and manages sessions.
 */

// Start session
session_start();

// Include database connection and helper functions
require_once '../includes/db.php';
require_once '../includes/functions.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = sanitize_input($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "Please fill in all fields.";
        header("Location: ../login.php#login");
        exit();
    }

    try {
        // Fetch user from database
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        // Verify user exists and password is correct
        if ($user && password_verify($password, $user['password'])) {
            // Regeneration of session ID for security against session fixation
            session_regenerate_id(true);

            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];

            // Redirect to dashboard
            header("Location: ../dashboard.php");
            exit();
        } else {
            // Generic error message for security
            $_SESSION['error'] = "Invalid email or password.";
            header("Location: ../login.php#login");
            exit();
        }

    } catch (PDOException $e) {
        $_SESSION['error'] = "Database error: " . $e->getMessage();
        header("Location: ../login.php#login");
        exit();
    }
} else {
    // If accessed directly, redirect to login form
    header("Location: ../login.php#login");
    exit();
}
?>

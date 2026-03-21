<?php
/**
 * Helper and validation functions for the Foodiez application.
 */

/**
 * Sanitize user input to prevent XSS.
 *
 * @param string $data The input string.
 * @return string The sanitized string.
 */
function sanitize_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

/**
 * Validate email format.
 *
 * @param string $email
 * @return bool
 */
function is_valid_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

/**
 * Check if user is logged in.
 * Redirects to login page if not.
 */
function check_login() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }
}
?>

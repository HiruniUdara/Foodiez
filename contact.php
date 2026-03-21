<?php
session_start();
/**
 * Contact Page & Handler.
 * Displays the contact form and processes submissions.
 */

// Include database connection and helper functions
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Handle Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input
    $name = sanitize_input($_POST['name']);
    $email = sanitize_input($_POST['email']);
    $message = sanitize_input($_POST['message']);

    // Basic validation
    if (empty($name) || empty($email) || empty($message)) {
        $_SESSION['error'] = "Please fill in all required fields.";
        header("Location: contact.php");
        exit();
    }

    if (!is_valid_email($email)) {
        $_SESSION['error'] = "Invalid email format.";
        header("Location: contact.php");
        exit();
    }

    try {
        // Insert message into database
        $stmt = $pdo->prepare("INSERT INTO messages (name, email, message) VALUES (?, ?, ?)");
        if ($stmt->execute([$name, $email, $message])) {
            $_SESSION['success'] = "Thank you! Your message has been sent successfully.";
            header("Location: contact.php");
            exit();
        } else {
            $_SESSION['error'] = "Something went wrong. Please try again later.";
            header("Location: contact.php");
            exit();
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Database error: " . $e->getMessage();
        header("Location: contact.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us | Foodies</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="container-fluid">

        <div class="main">
            <!-- Centralized Navbar -->
            <nav class="navbar">
                <a href="index.html" class="nav-logo">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="var(--primary-color)">
                        <path
                            d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z" />
                    </svg>
                    Foodiez<span>.</span>
                </a>
                <div class="nav-links">
                    <a href="index.html">Home</a>
                    <a href="about.html">About Us</a>
                    <a href="menu.html">Menu</a>
                    <a href="order.php">Order</a>
                    <a href="contact.php" class="active">Contact</a>
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <a href="dashboard.php">Dashboard</a>
                        <a href="auth/logout.php" style="color: #dc2626;">Logout</a>
                    <?php else: ?>
                        <a href="login.php">Login</a>
                    <?php endif; ?>
                </div>
                <div class="menu-toggle">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </nav>

            <!-- Contact Layout -->
            <div class="contact-container">

                <!-- Left: Contact Details -->
                <div class="contact-left">
                    <h2 class="section-title">Contact Information</h2>

                    <div class="contact-info-block">
                        <h4>Call us</h4>
                        <div class="contact-detail">
                            <svg viewBox="0 0 24 24">
                                <path
                                    d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z" />
                            </svg>
                            <span>+94 772650813</span>
                        </div>
                    </div>

                    <div class="contact-info-block">
                        <h4>Email</h4>
                        <div class="contact-detail">
                            <svg viewBox="0 0 24 24">
                                <path
                                    d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z" />
                            </svg>
                            <span>foodiez@.com</span>
                        </div>
                    </div>

                    <div class="contact-info-block">
                        <h4>Address</h4>
                        <div class="contact-detail">
                            <svg viewBox="0 0 24 24">
                                <path
                                    d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z" />
                            </svg>
                            <span>123 Food Street,Kaluthara District<br>Sri Lanka</span>
                        </div>
                    </div>
                </div>

                <!-- Right: Contact Form -->
                <div class="contact-right">
                    <?php if(isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger" style="border-radius:10px; margin-bottom:1rem;">
                            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                        </div>
                    <?php endif; ?>
                    <?php if(isset($_SESSION['success'])): ?>
                        <div class="alert alert-success" style="border-radius:10px; margin-bottom:1rem;">
                            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                        </div>
                    <?php endif; ?>

                    <form class="contact-form-card" id="contact-form" action="contact.php" method="POST">
                        <h2 class="section-title">Contact Form</h2>

                        <div class="form-group">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-input" placeholder="Your Name" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Phone</label>
                            <input type="tel" class="form-input" placeholder="Your Phone Number" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-input" placeholder="Your Email" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Address</label>
                            <input type="text" class="form-input" placeholder="Your Address">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Message</label>
                            <textarea name="message" class="form-input" placeholder="Your Message..." required></textarea>
                        </div>

                        <button type="submit" class="submit-btn"
                            style="background-color: #3b82f6; border-radius: 4px;">Submit</button>
                    </form>
                </div>

            </div> <!-- /contact-container -->
        </div> <!-- /main -->

        <!-- Centralized Footer -->
        <footer>
            <p>&copy; 2026 <strong>Foodiez.</strong> All rights reserved.</p>
        </footer>

    </div>

    <script src="script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

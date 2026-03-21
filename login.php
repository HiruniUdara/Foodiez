<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Foodiez</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* Auth Page Layout */
        .auth-page {
            min-height: calc(100vh - 80px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem 1rem;
        }

        .auth-container {
            display: flex;
            width: 100%;
            max-width: 900px;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.12);
        }

        /* Left Panel */
        .auth-panel-left {
            flex: 1;
            background: linear-gradient(145deg, #b91c1c, #dc2626, #ef4444);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 3.5rem 2.5rem;
            color: white;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .auth-panel-left::before {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.06);
            border-radius: 50%;
            top: -80px;
            right: -80px;
        }

        .auth-panel-left::after {
            content: '';
            position: absolute;
            width: 220px;
            height: 220px;
            background: rgba(255, 255, 255, 0.06);
            border-radius: 50%;
            bottom: -60px;
            left: -60px;
        }

        .auth-brand {
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            letter-spacing: -0.5px;
            position: relative;
            z-index: 1;
        }

        .auth-brand span {
            color: #fbbf24;
        }

        .auth-panel-left h2 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: white;
            position: relative;
            z-index: 1;
        }

        .auth-panel-left p {
            font-size: 0.95rem;
            color: rgba(255, 255, 255, 0.85);
            line-height: 1.7;
            margin-bottom: 2rem;
            position: relative;
            z-index: 1;
        }

        .auth-switch-btn {
            display: inline-block;
            padding: 12px 32px;
            border: 2px solid white;
            border-radius: 50px;
            color: white;
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
            transition: all 0.3s ease;
            position: relative;
            z-index: 1;
        }

        .auth-switch-btn:hover {
            background: white;
            color: #dc2626;
        }

        .auth-food-icon {
            font-size: 4rem;
            margin-bottom: 1.5rem;
            position: relative;
            z-index: 1;
        }

        /* Right Panel - Form */
        .auth-panel-right {
            flex: 1.2;
            background: white;
            padding: 3.5rem 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .auth-form-header {
            margin-bottom: 2.5rem;
        }

        .auth-form-header h1 {
            font-size: 2rem;
            font-weight: 800;
            color: #1e293b;
            margin-bottom: 0.4rem;
        }

        .auth-form-header h1 span {
            color: #dc2626;
        }

        .auth-form-header p {
            color: #64748b;
            font-size: 0.95rem;
        }

        /* Form styles */
        .auth-form {
            display: flex;
            flex-direction: column;
            gap: 1.3rem;
        }

        .form-field {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .form-field label {
            font-size: 0.85rem;
            font-weight: 600;
            color: #334155;
            letter-spacing: 0.3px;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-wrapper svg {
            position: absolute;
            left: 14px;
            width: 18px;
            height: 18px;
            fill: #94a3b8;
            pointer-events: none;
            transition: fill 0.3s ease;
        }

        .input-wrapper input {
            width: 100%;
            padding: 13px 14px 13px 42px;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            font-family: 'Poppins', sans-serif;
            font-size: 0.92rem;
            color: #1e293b;
            background: #f8fafc;
            transition: all 0.3s ease;
            outline: none;
        }

        .input-wrapper input:focus {
            border-color: #dc2626;
            background: white;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.08);
        }

        .input-wrapper input:focus+svg,
        .input-wrapper:focus-within svg {
            fill: #dc2626;
        }

        .input-wrapper svg:first-child {
            z-index: 1;
        }

        /* Eye toggle for password */
        .toggle-password {
            position: absolute;
            right: 14px;
            cursor: pointer;
            width: 18px;
            height: 18px;
            fill: #94a3b8;
            transition: fill 0.2s;
        }

        .toggle-password:hover {
            fill: #dc2626;
        }

        /* Two-column row */
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        /* Password strength */
        .password-strength {
            display: flex;
            gap: 5px;
            margin-top: 5px;
        }

        .strength-bar {
            flex: 1;
            height: 3px;
            border-radius: 10px;
            background: #e2e8f0;
            transition: background 0.3s ease;
        }

        .strength-bar.weak {
            background: #ef4444;
        }

        .strength-bar.medium {
            background: #f59e0b;
        }

        .strength-bar.strong {
            background: #22c55e;
        }

        /* Terms checkbox */
        .terms-check {
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        .terms-check input[type="checkbox"] {
            width: 16px;
            height: 16px;
            margin-top: 2px;
            accent-color: #dc2626;
            cursor: pointer;
            flex-shrink: 0;
        }

        .terms-check span {
            font-size: 0.83rem;
            color: #64748b;
            line-height: 1.5;
        }

        .terms-check a {
            color: #dc2626;
            font-weight: 600;
            text-decoration: none;
        }

        .terms-check a:hover {
            text-decoration: underline;
        }

        /* Mode Toggle Animations */
        .auth-form-container {
            display: none;
            animation: formIn 0.5s ease both;
        }

        .auth-form-container.active {
            display: flex;
            flex-direction: column;
            gap: 1.3rem;
        }

        @keyframes formIn {
            from {
                opacity: 0;
                transform: translateX(20px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: -0.3rem;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }

        .remember-me input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: #dc2626;
            cursor: pointer;
        }

        .remember-me span {
            font-size: 0.85rem;
            color: #64748b;
        }

        .forgot-link {
            font-size: 0.85rem;
            color: #dc2626;
            font-weight: 500;
            text-decoration: none;
            transition: color 0.2s;
        }

        .forgot-link:hover {
            color: #b91c1c;
            text-decoration: underline;
        }

        .auth-submit-btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            color: white;
            border: none;
            border-radius: 10px;
            font-family: 'Poppins', sans-serif;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            letter-spacing: 0.3px;
            margin-top: 0.3rem;
        }

        .auth-submit-btn:hover {
            background: linear-gradient(135deg, #b91c1c, #991b1b);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(220, 38, 38, 0.3);
        }

        .auth-submit-btn:active {
            transform: translateY(0);
        }

        .auth-footer-link {
            text-align: center;
            font-size: 0.88rem;
            color: #64748b;
            margin-top: 1rem;
        }

        .auth-footer-link a {
            color: #dc2626;
            font-weight: 600;
            text-decoration: none;
        }

        .auth-footer-link a:hover {
            text-decoration: underline;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .auth-page {
                padding: 1.5rem 1rem;
            }

            .auth-container {
                flex-direction: column;
                border-radius: 16px;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            }

            .auth-panel-left {
                padding: 1.5rem;
                min-height: auto;
                text-align: center;
            }

            .auth-panel-right {
                padding: 2rem 1.2rem;
            }

            .auth-panel-left h2,
            .auth-panel-left p,
            .auth-switch-btn {
                display: none;
            }

            .poster-content {
                display: flex;
                flex-direction: row;
                align-items: center;
                justify-content: center;
                gap: 10px;
            }

            .auth-food-icon {
                font-size: 1.8rem;
                margin-bottom: 0;
            }

            .auth-brand {
                font-size: 1.4rem;
            }

            .form-row {
                grid-template-columns: 1fr;
                gap: 0.8rem;
            }

            .auth-form-header h1 {
                font-size: 1.8rem;
            }

            .auth-submit-btn {
                padding: 12px;
            }
        }

        @media (max-width: 480px) {
            .auth-page {
                padding: 1rem 0.5rem;
            }

            .navbar {
                padding: 0.7rem 1rem;
            }

            .auth-panel-right {
                padding: 1.5rem 1rem;
            }
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="main">
            <!-- Navbar -->
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
                    <a href="contact.php">Contact</a>
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <a href="dashboard.php">Dashboard</a>
                        <a href="auth/logout.php" style="color: #dc2626;">Logout</a>
                    <?php else: ?>
                        <a href="login.php" class="active">Login</a>
                    <?php endif; ?>
                </div>
                <div class="menu-toggle">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </nav>

            <!-- Auth Page -->
            <div class="auth-page">
                <div class="auth-container">

                    <!-- Left Panel (Poster) -->
                    <div class="auth-panel-left" id="auth-poster">
                        <div id="poster-login" class="poster-content">
                            <div class="auth-food-icon">🍕</div>
                            <div class="auth-brand">Foodiez<span>.</span></div>
                            <h2>Welcome Back!</h2>
                            <p>Don't have an account?<br>Sign up and enjoy our<br>amazing food today!</p>
                            <a href="javascript:void(0)" onclick="toggleAuthMode('register')"
                                class="auth-switch-btn">Create
                                Account</a>
                        </div>
                        <div id="poster-register" class="poster-content" style="display:none;">
                            <div class="auth-food-icon">🍔</div>
                            <div class="auth-brand">Foodiez<span>.</span></div>
                            <h2>Join Our Family!</h2>
                            <p>Already have an account?<br>Sign in to continue your<br>food journey with us!</p>
                            <a href="javascript:void(0)" onclick="toggleAuthMode('login')" class="auth-switch-btn">Sign
                                In</a>
                        </div>
                    </div>

                    <!-- Right Panel -->
                    <div class="auth-panel-right">
                        <!-- Sign In View -->
                        <div id="login-container" class="auth-form-container active">
                            <div class="auth-form-header">
                                <h1>Sign <span>In</span></h1>
                                <p>Login to your account to order delicious food</p>
                            </div>

                            <?php if(isset($_SESSION['error'])): ?>
                                <div class="alert alert-danger" style="border-radius:10px; font-size:0.9rem; margin-bottom:1rem;">
                                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                                </div>
                            <?php endif; ?>
                            <?php if(isset($_SESSION['success'])): ?>
                                <div class="alert alert-success" style="border-radius:10px; font-size:0.9rem; margin-bottom:1rem;">
                                    <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                                </div>
                            <?php endif; ?>

                            <form class="auth-form" id="login-form" action="auth/login.php" method="POST">
                                <!-- Email -->
                                <div class="form-field">
                                    <label for="login-email">Email Address</label>
                                    <div class="input-wrapper">
                                        <svg viewBox="0 0 24 24">
                                            <path
                                                d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z" />
                                        </svg>
                                        <input type="email" id="login-email" name="email" placeholder="you@example.com" required>
                                    </div>
                                </div>

                                <!-- Password -->
                                <div class="form-field">
                                    <label for="login-password">Password</label>
                                    <div class="input-wrapper">
                                        <svg viewBox="0 0 24 24">
                                            <path
                                                d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z" />
                                        </svg>
                                        <input type="password" id="login-password" name="password" placeholder="Enter your password"
                                            required>
                                        <svg class="toggle-password" viewBox="0 0 24 24"
                                            onclick="togglePassword('login-password', this)">
                                            <path
                                                d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z" />
                                        </svg>
                                    </div>
                                </div>

                                <!-- Options -->
                                <div class="form-options">
                                    <label class="remember-me">
                                        <input type="checkbox" id="remember">
                                        <span>Remember me</span>
                                    </label>
                                    <a href="#" class="forgot-link">Forgot password?</a>
                                </div>

                                <!-- Submit -->
                                <button type="submit" class="auth-submit-btn">Sign In</button>

                                <p class="auth-footer-link">
                                    Don't have an account? <a href="javascript:void(0)"
                                        onclick="toggleAuthMode('register')">Sign up here</a>
                                </p>
                            </form>
                        </div>

                        <!-- Register View -->
                        <div id="register-container" class="auth-form-container">
                            <div class="auth-form-header">
                                <h1>Create <span>Account</span></h1>
                                <p>Join Foodiez and get access to amazing food deals</p>
                            </div>

                            <form class="auth-form" id="register-form" action="auth/register.php" method="POST">
                                <!-- Name Row -->
                                <div class="form-row">
                                            <input type="text" id="username" name="username" placeholder="Username" required>
                                </div>

                                <!-- Email -->
                                <div class="form-field">
                                    <label for="reg-email">Email Address</label>
                                    <div class="input-wrapper">
                                        <svg viewBox="0 0 24 24">
                                            <path
                                                d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z" />
                                        </svg>
                                        <input type="email" id="reg-email" name="email" placeholder="you@example.com" required>
                                    </div>
                                </div>

                                <!-- Password Row -->
                                <div class="form-row">
                                    <div class="form-field">
                                        <label for="reg-password">Password</label>
                                        <div class="input-wrapper">
                                            <input type="password" id="reg-password" name="password" placeholder="Min 8 characters"
                                                required oninput="updateStrength(this.value)">
                                            <svg class="toggle-password" viewBox="0 0 24 24"
                                                onclick="togglePassword('reg-password', this)">
                                                <path
                                                    d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z" />
                                            </svg>
                                        </div>
                                        <div class="password-strength" id="strength-bars">
                                            <div class="strength-bar" id="bar1"></div>
                                            <div class="strength-bar" id="bar2"></div>
                                            <div class="strength-bar" id="bar3"></div>
                                            <div class="strength-bar" id="bar4"></div>
                                        </div>
                                    </div>
                                    <div class="form-field">
                                        <label for="reg-confirm">Confirm Password</label>
                                        <div class="input-wrapper">
                                            <input type="password" id="reg-confirm" name="confirm_password" placeholder="Re-enter password"
                                                required>
                                        </div>
                                    </div>
                                </div>

                                <!-- Terms -->
                                <div class="terms-check">
                                    <input type="checkbox" id="terms" required>
                                    <span>I agree to the <a href="#">Terms of Service</a></span>
                                </div>

                                <!-- Submit -->
                                <button type="submit" class="auth-submit-btn">Create Account</button>

                                <p class="auth-footer-link">
                                    Already a member? <a href="javascript:void(0)"
                                        onclick="toggleAuthMode('login')">Sign in here</a>
                                </p>
                            </form>
                        </div>
                    </div>

                </div>
            </div>

        </div><!-- /main -->

        <!-- Footer -->
        <footer>
            <p>&copy; 2026 <strong>Foodiez.</strong> All rights reserved.</p>
            <div class="socials">
                <a href="#" aria-label="Facebook">
                    <svg viewBox="0 0 24 24">
                        <path
                            d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 4.84 3.44 8.87 8 9.8V15H8v-3h2V9.5C10 7.57 11.57 6 13.5 6H16v3h-2c-1.1 0-2 .9-2 2v1h4l-1 3h-3v6.8c4.56-.93 8-4.96 8-9.8z" />
                    </svg>
                </a>
                <a href="#" aria-label="Instagram">
                    <svg viewBox="0 0 24 24">
                        <path
                            d="M7.8 2h8.4C19.4 2 22 4.6 22 7.8v8.4a5.8 5.8 0 0 1-5.8 5.8H7.8C4.6 22 2 19.4 2 16.2V7.8A5.8 5.8 0 0 1 7.8 2zm-.2 2A3.6 3.6 0 0 0 4 7.6v8.8C4 18.39 5.61 20 7.6 20h8.8a3.6 3.6 0 0 0 3.6-3.6V7.6C20 5.61 18.39 4 16.4 4H7.6zm9.65 1.5a1.25 1.25 0 0 1 1.25 1.25A1.25 1.25 0 0 1 17.25 8 1.25 1.25 0 0 1 16 6.75a1.25 1.25 0 0 1 1.25-1.25zM12 7a5 5 0 0 1 5 5 5 5 0 0 1-5 5 5 5 0 0 1-5-5 5 5 0 0 1 5-5zm0 2a3 3 0 0 0-3 3 3 3 0 0 0 3 3 3 3 0 0 0 3-3 3 3 0 0 0-3-3z" />
                    </svg>
                </a>
                <a href="#" aria-label="LinkedIn">
                    <svg viewBox="0 0 24 24">
                        <path
                            d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z" />
                    </svg>
                </a>
            </div>
        </footer>
    </div>

    <script src="script.js"></script>
    <script>
        function togglePassword(inputId, icon) {
            const input = document.getElementById(inputId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.innerHTML = '<path d="M12 7c2.76 0 5 2.24 5 5 0 .65-.13 1.26-.36 1.83l2.92 2.92c1.51-1.26 2.7-2.89 3.43-4.75-1.73-4.39-6-7.5-11-7.5-1.4 0-2.74.25-3.98.7l2.16 2.16C10.74 7.13 11.35 7 12 7zM2 4.27l2.28 2.28.46.46C3.08 8.3 1.78 10.02 1 12c1.73 4.39 6 7.5 11 7.5 1.55 0 3.03-.3 4.38-.84l.42.42L19.73 22 21 20.73 3.27 3 2 4.27zM7.53 9.8l1.55 1.55c-.05.21-.08.43-.08.65 0 1.66 1.34 3 3 3 .22 0 .44-.03.65-.08l1.55 1.55c-.67.33-1.41.53-2.2.53-2.76 0-5-2.24-5-5 0-.79.2-1.53.53-2.2zm4.31-.78l3.15 3.15.02-.16c0-1.66-1.34-3-3-3l-.17.01z"/>';
            } else {
                input.type = 'password';
                icon.innerHTML = '<path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>';
            }
        }


        function toggleAuthMode(mode) {
            const loginContainer = document.getElementById('login-container');
            const registerContainer = document.getElementById('register-container');
            const posterLogin = document.getElementById('poster-login');
            const posterRegister = document.getElementById('poster-register');

            if (mode === 'register') {
                loginContainer.classList.remove('active');
                registerContainer.classList.add('active');
                posterLogin.style.display = 'none';
                posterRegister.style.display = 'block';
                window.location.hash = 'register';
                document.title = 'Register | Foodiez';
            } else {
                loginContainer.classList.add('active');
                registerContainer.classList.remove('active');
                posterLogin.style.display = 'block';
                posterRegister.style.display = 'none';
                window.location.hash = 'login';
                document.title = 'Login | Foodiez';
            }
        }

        function updateStrength(val) {
            const bars = [
                document.getElementById('bar1'),
                document.getElementById('bar2'),
                document.getElementById('bar3'),
                document.getElementById('bar4')
            ];
            bars.forEach(b => { b.className = 'strength-bar'; });

            let score = 0;
            if (val.length >= 6) score++;
            if (val.length >= 10) score++;
            if (/[A-Z]/.test(val) && /[0-9]/.test(val)) score++;
            if (/[^A-Za-z0-9]/.test(val)) score++;

            const cls = score <= 1 ? 'weak' : score <= 2 ? 'medium' : 'strong';
            for (let i = 0; i < score; i++) bars[i].classList.add(cls);
        }


        // Initialize on load
        window.addEventListener('DOMContentLoaded', () => {
            if (window.location.hash === '#register') {
                toggleAuthMode('register');
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
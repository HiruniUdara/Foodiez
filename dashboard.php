<?php
session_start();
/**
 * User Dashboard.
 * Displays user profile and order history.
 */

// Include dependencies
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Security: Check if user is logged in
check_login();

// Fetch user data
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$email = $_SESSION['email'];

try {
    // Fetch order history for the user
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([$user_id]);
    $orders = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Foodiez</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .dashboard-container {
            padding: 4rem 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .user-profile-header {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            color: white;
            padding: 3rem;
            border-radius: 20px;
            margin-bottom: 3rem;
            box-shadow: 0 15px 35px rgba(220, 38, 38, 0.2);
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        .user-avatar {
            width: 100px;
            height: 100px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
        }

        .user-info h1 {
            margin: 0;
            font-size: 2.5rem;
            font-weight: 800;
        }

        .user-info p {
            margin: 0;
            opacity: 0.9;
            font-size: 1.1rem;
        }

        .order-history-card {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.05);
        }

        .order-history-card h2 {
            font-weight: 700;
            margin-bottom: 2rem;
            color: #1e293b;
        }

        .order-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 1rem;
        }

        .order-table th {
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 1px;
            padding: 0 1rem;
        }

        .order-row {
            background: #f8fafc;
            border-radius: 12px;
            transition: transform 0.2s;
        }

        .order-row:hover {
            transform: scale(1.01);
            background: #f1f5f9;
        }

        .order-row td {
            padding: 1.2rem 1rem;
            color: #334155;
            font-weight: 500;
        }

        .order-row td:first-child {
            border-radius: 12px 0 0 12px;
        }

        .order-row td:last-child {
            border-radius: 0 12px 12px 0;
        }

        .status-badge {
            padding: 6px 14px;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
            background: #dcfce7;
            color: #166534;
        }

        .logout-btn {
            background: white;
            color: #dc2626;
            border: 2px solid white;
            padding: 10px 25px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            margin-top: 1rem;
            display: inline-block;
        }

        .logout-btn:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }

        @media (max-width: 768px) {
            .user-profile-header {
                flex-direction: column;
                text-align: center;
                padding: 2rem;
            }
            .order-table thead {
                display: none;
            }
            .order-row td {
                display: block;
                text-align: right;
                padding: 0.5rem 1rem;
            }
            .order-row td::before {
                content: attr(data-label);
                float: left;
                font-weight: 600;
                color: #64748b;
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
                    <a href="dashboard.php" class="active">Dashboard</a>
                    <a href="auth/logout.php" style="color: #dc2626;">Logout</a>
                </div>
            </nav>

            <div class="dashboard-container">
                <!-- User Profile Section -->
                <div class="user-profile-header">
                    <div class="user-avatar">👤</div>
                    <div class="user-info">
                        <h1>Welcome, <?php echo htmlspecialchars($username); ?>!</h1>
                        <p><?php echo htmlspecialchars($email); ?></p>
                        <a href="auth/logout.php" class="logout-btn">Logout</a>
                    </div>
                </div>

                <!-- Order History Section -->
                <div class="order-history-card">
                    <h2>Order History</h2>
                    <?php if (empty($orders)): ?>
                        <div style="text-align:center; padding: 2rem; color: #64748b;">
                            <p>You haven't placed any orders yet. <a href="menu.html" style="color:#dc2626; font-weight:600;">Order now!</a></p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="order-table">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Food Item</th>
                                        <th>Quantity</th>
                                        <th>Total Price</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($orders as $order): ?>
                                        <tr class="order-row">
                                            <td data-label="Order ID">#<?php echo $order['id']; ?></td>
                                            <td data-label="Food Item"><?php echo htmlspecialchars($order['food_item']); ?></td>
                                            <td data-label="Quantity"><?php echo $order['quantity']; ?></td>
                                            <td data-label="Total Price">Rs. <?php echo number_format($order['total_price'], 2); ?></td>
                                            <td data-label="Date"><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                                            <td data-label="Status"><span class="status-badge">Processing</span></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div><!-- /main -->

        <!-- Footer -->
        <footer>
            <p>&copy; 2026 <strong>Foodiez.</strong> All rights reserved.</p>
        </footer>
    </div>

    <script src="script.js"></script>
</body>

</html>

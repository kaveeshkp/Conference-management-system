<?php
// logout.php - Ends the session and redirects to the login page

session_start();

// Destroy the session to log out the user
session_unset();
session_destroy();

// Redirect to the login page
header("Location: log.php?msg=logged_out");
exit();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IRC - User Dashboard</title>
    <style>
        /* Add your existing user dashboard styles here */
        .logout-btn {
            background-color: #dc3545;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
        }
        .logout-btn:hover {
            background-color: #c82333;
        }
        .user-info {
            color: white;
            padding: 10px;
            margin-right: 20px;
        }
        .dashboard-content {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <div class="logo">International Research Conference</div>
            <div style="display: flex; align-items: center;">
                <span class="user-info">
                    Welcome, <?php echo htmlspecialchars($_SESSION['email']); ?>
                </span>
                <!-- Logout button -->
                <a href="logout.php" class="logout-btn">Logout</a>
            </div>
        </nav>
    </header>

    <!-- User Dashboard Content -->
    <div class="dashboard-content">
        <h2>User Dashboard</h2>
        <p>Welcome to your personal dashboard! Here you can view and manage your details.</p>
        <!-- Additional user-specific content can be added here -->
    </div>

</body>
</html>

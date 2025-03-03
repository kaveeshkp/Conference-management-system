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
<html>
<head>
    <title>IRC - Admin Dashboard</title>
    <style>
        /* Add your existing admin dashboard styles here */
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
                <a href="logout.php" class="logout-btn">Logout</a>
            </div>
        </nav>
    </header>

    <!-- Rest of your admin dashboard content -->
</body>
</html>  

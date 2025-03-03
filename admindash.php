<?php
session_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if user is logged in and is Admin
if (!isset($_SESSION['user_id'])) {
    header("Location: log.html");
    exit();
}

// Verify admin role using the correct column name from your database
try {
    $conn = new mysqli("localhost", "root", "", "IRC");
    
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Get user role from database
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT participation_category FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user || $user['participation_category'] !== 'Admin') {
        header("Location: log.html");
        exit();
    }

    // Get dashboard statistics
    // Count total users
    $result = $conn->query("SELECT COUNT(*) as total FROM users WHERE participation_category = 'Participant'");
    $total_users = $result->fetch_assoc()['total'];

    // Count total abstracts/submissions (if you have a submissions table)
    $total_abstracts = 0;
    if ($conn->query("SHOW TABLES LIKE 'submissions'")->num_rows > 0) {
        $result = $conn->query("SELECT COUNT(*) as total FROM submissions");
        $total_abstracts = $result->fetch_assoc()['total'];
    }

    // Count total sessions (if you have a sessions table)
    $total_sessions = 0;
    if ($conn->query("SHOW TABLES LIKE 'sessions'")->num_rows > 0) {
        $result = $conn->query("SELECT COUNT(*) as total FROM sessions");
        $total_sessions = $result->fetch_assoc()['total'];
    }

    // Get recent registrations
    $recent_users = [];
    $stmt = $conn->prepare("SELECT name_with_initials, email_address, participation_category 
                           FROM users 
                           WHERE participation_category = 'Participant' 
                           ORDER BY id DESC LIMIT 5");
    $stmt->execute();
    $recent_users = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

} catch (Exception $e) {
    error_log("Dashboard Error: " . $e->getMessage());
    $error_message = "An error occurred while loading the dashboard.";
} finally {
    if (isset($stmt)) {
        $stmt->close();
    }
    if (isset($conn)) {
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IRC - Admin Dashboard</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
        }
        
        header {
            background-color: #003366;
            color: white;
            padding: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: bold;
        }

        nav a {
            color: white;
            text-decoration: none;
            margin-left: 1rem;
        }

        .sidebar {
            background-color: #003366;
            color: white;
            width: 200px;
            height: calc(100vh - 60px);
            position: fixed;
            padding: 1rem;
        }

        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 0.5rem 0;
        }

        .main-content {
            margin-left: 220px;
            padding: 2rem;
        }

        .card {
            background: white;
            padding: 1.5rem;
            margin-bottom: 1rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
        }

        .recent-users {
            margin-top: 1rem;
        }

        .recent-users table {
            width: 100%;
            border-collapse: collapse;
        }

        .recent-users th, .recent-users td {
            padding: 0.5rem;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        footer {
            background-color: #003366;
            color: white;
            text-align: center;
            padding: 1rem;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">International Research Conference</div>
        <nav>
            <a href="admindash.php">Home</a>
            <a href="adminschedule.php">Schedule</a>
            <a href="adminsessions.php">Sessions</a>
            <a href="adminusers.php">Users</a>
            <a href="admindashlog.php">Logout</a>
        </nav>
    </header>

    <aside class="sidebar">
        <a href="admindash.php">Dashboard</a>
        <a href="amuser.php">Manage Users</a>
        <a href="amsessions.php">Manage Sessions</a>
        <a href="admsubmission.php">Manage Submissions</a>
        
        <a href="admannouncement.php">Announcements</a>
    </aside>

    <main class="main-content">
        <?php if (isset($error_message)): ?>
            <div class="card" style="color: red;">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php else: ?>
            <div class="card">
                <h2>Dashboard Overview</h2>
                <div class="stats-grid">
                    <div>
                        <h3>Total Participants</h3>
                        <p><?php echo $total_users; ?></p>
                    </div>
                    <div>
                        <h3>Total Submissions</h3>
                        <p><?php echo $total_abstracts; ?></p>
                    </div>
                    <div>
                        <h3>Total Sessions</h3>
                        <p><?php echo $total_sessions; ?></p>
                    </div>
                </div>
            </div>

            <div class="card">
                <h2>Recent Registrations</h2>
                <div class="recent-users">
                    <table>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Category</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recent_users as $user): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($user['name_with_initials']); ?></td>
                                    <td><?php echo htmlspecialchars($user['email_address']); ?></td>
                                    <td><?php echo htmlspecialchars($user['participation_category']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </main>

    <footer>
        &copy; <?php echo date("Y"); ?> International Research Conference. All rights reserved.
    </footer>
</body>
</html>
<?php
session_start();

// Debug line - remove in production
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check session
if (!isset($_SESSION['user_id'])) {
    header("Location: log.html");
    exit();
}

// Database connection constants - modify these according to your setup
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'IRC');

// Create connection
try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    // Check connection
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    // Get user ID from session
    $user_id = $_SESSION['user_id'];
    
    // Simple query to test database connection
    $query = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    
    if ($stmt === false) {
        throw new Exception("Failed to prepare statement: " . $conn->error);
    }
    
    $stmt->bind_param("i", $user_id);
    
    if (!$stmt->execute()) {
        throw new Exception("Failed to execute query: " . $stmt->error);
    }
    
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    if ($user === null) {
        throw new Exception("User not found");
    }
    
} catch (Exception $e) {
    // Log the actual error
    error_log("Profile page error: " . $e->getMessage());
    $error_message = "An error occurred. Please try again later.";
}

// Close resources
if (isset($stmt)) $stmt->close();
if (isset($conn)) $conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
    <link href="uprofile.css" rel="stylesheet">
</head>
<body>
    <header>
        <div class="logo">International Research Conference</div>
        <nav>
            <a href="userdash.php">Home</a>
            <a href="uschedule.php">Schedule</a>
            <a href="usessions.php">Sessions</a>
            <a href="unetworking.php">Networking</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <main class="main-content">
        <?php if (isset($error_message)): ?>
            <div class="error-message">
                <?php echo htmlspecialchars($error_message); ?>
                <p>Debug info (remove in production):</p>
                <pre><?php error_reporting(E_ALL); ini_set('display_errors', 1); ?></pre>
            </div>
        <?php elseif (isset($user)): ?>
            <div class="card">
                <h2>My Profile</h2>
                <p>
                    <strong>Name:</strong> <?php echo htmlspecialchars($user['name_with_initials'] ?? ''); ?><br>
                    <strong>Email:</strong> <?php echo htmlspecialchars($user['email_address'] ?? ''); ?><br>
                    <strong>Category:</strong> <?php echo htmlspecialchars($user['participation_category'] ?? ''); ?><br>
                    <strong>Role:</strong> <?php echo htmlspecialchars($user['user_role'] ?? ''); ?>
                </p>
                <a href="edit_profile.php" class="edit-button">Edit Profile</a>
            </div>
        <?php endif; ?>
    </main>

    <footer>
        &copy; <?php echo date("Y"); ?> International Research Conference. All rights reserved.
    </footer>
</body>
</html>
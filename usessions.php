<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: log.html");
    exit();
}

$conn = new mysqli("localhost", "root", "", "IRC");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT name_with_initials FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Sessions</title>
    <link href="userdash.css" rel="stylesheet">
</head>
<body>
    <!-- Header Section -->
    <header>
        <div class="logo">International Research Conference</div>
        <nav>
        <a href="userdash.php">Home</a>
            <a href="uschedule.php">Schedule</a>
            <a href="usessions.php">Sessions</a>
            <a href="unetworking.php">Networking</a>
            <a href="logout.php" style="float: right;">Logout</a>
        </nav>
    </header>

    <!-- Main Content Section -->
    <main class="main-content">
        <div class="card">
            <h2>Sessions Available</h2>
            <?php if (empty($sessions)): ?>
                <p>No sessions are currently available. Please check back later.</p>
            <?php else: ?>
                <?php foreach ($sessions as $session): ?>
                    <p><strong>Session:</strong> <?php echo htmlspecialchars($session['session_name']); ?><br>
                    <strong>Description:</strong> <?php echo htmlspecialchars($session['session_description']); ?></p>
                <?php endforeach; ?>
                <button>Register for a Session</button>
            <?php endif; ?>
        </div>
    </main>

    <!-- Footer Section -->
    <footer>
        &copy; 2024 International Research Conference. All rights reserved.
    </footer>
</body>
</html>
<?php $conn->close(); ?>
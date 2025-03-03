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

// Fetch all sessions from the database
$sessions_query = "SELECT * FROM sessions ORDER BY start_time ASC";
$sessions_result = $conn->query($sessions_query);

// Check if sessions exist
if (!$sessions_result) {
    die("Error fetching sessions: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conference Schedule</title>
    <link href="uschedule.css" rel="stylesheet">
</head>
<body>
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

    <main class="main-content">
        <div class="card">
            <h2>Conference Schedule</h2>
            <p>Welcome, <?php echo htmlspecialchars($user['name_with_initials']); ?></p>
            
            <?php if ($sessions_result->num_rows > 0): ?>
                <?php while ($session = $sessions_result->fetch_assoc()): ?>
                    <div class="session-item">
                        <p>
                            <strong>Session:</strong> <?php echo htmlspecialchars($session['title']); ?><br>
                            <strong>Time:</strong> <?php echo htmlspecialchars($session['start_time']); ?> - 
                                                 <?php echo htmlspecialchars($session['end_time']); ?><br>
                            <strong>Location:</strong> <?php echo htmlspecialchars($session['location'] ?? 'TBA'); ?><br>
                            <strong>Speaker:</strong> <?php echo htmlspecialchars($session['speaker'] ?? 'TBA'); ?>
                        </p>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No sessions are currently scheduled.</p>
            <?php endif; ?>
        </div>
    </main>

    <footer>
        &copy; <?php echo date("Y"); ?> International Research Conference. All rights reserved.
    </footer>
</body>
</html>
<?php 
$stmt->close();
$conn->close(); 
?>
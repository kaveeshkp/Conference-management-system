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

$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>International Research Conference Dashboard</title>
    <link href="userdash.css" rel="stylesheet">
</head>
<body>
    <header>
        <div class="logo">International Research Conference</div>
        <nav>
            <a href="userdash.php">Home</a>
            <a href="uschedule.php">Schedule</a>
            <a href="usessions.php">Sessions</a>
            <a href="unetworking.php">Networking</a>
            <a href="ulogout.php" style="float: right;">Logout</a>
        </nav>
    </header>

    <aside class="sidebar">
        <a href="userdash.php">Dashboard</a>
        <a href="uprofile.php">My Profile</a>
        <a href="usubmission.php">Submissions</a>
        <a href="uresources.php">Resources</a>
        <a href="ufeedback.php">Feedback</a>
    </aside>

    <main class="main-content">
        <div class="card">
            <h2>Welcome, <?php echo htmlspecialchars($user['name_with_initials']); ?></h2>
            <p>Get ready to explore the sessions, network with peers, and enhance your research knowledge.</p>
            <p>Category: <?php echo htmlspecialchars($user['participation_category']); ?></p>
        </div>

        <div class="card">
            <h2>Your Next Session</h2>
            <p><strong>Topic:</strong> Advances in Machine Learning<br>
            <strong>Time:</strong> 10:00 AM - 11:00 AM</p>
        </div>

        <div class="card">
            <h2>Important Announcement</h2>
            <p>Submit your feedback for sessions to receive your participation certificate.</p>
        </div>
    </main>

    <footer>
        &copy; <?php echo date("Y"); ?> International Research Conference. All rights reserved.
    </footer>
</body>
</html>
<?php $conn->close(); ?>
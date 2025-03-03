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
    <title>Feedback</title>
    <link href="ufeedback.css" rel="stylesheet">
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
            <h2>Feedback</h2>
            <p>Welcome, <?php echo htmlspecialchars($user['name_with_initials']); ?></p>
            <p>Share your feedback about the conference sessions to help improve future events and receive your participation certificate.</p>
            <form action="submit_feedback.php" method="POST">
                <textarea name="feedback" required placeholder="Enter your feedback here"></textarea>
                <button type="submit">Submit Feedback</button>
            </form>
        </div>
    </main>

    <footer>
        &copy; <?php echo date("Y"); ?> International Research Conference. All rights reserved.
    </footer>
</body>
</html>
<?php $conn->close(); ?>
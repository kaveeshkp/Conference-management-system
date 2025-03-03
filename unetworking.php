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
    <title>Networking</title>
    <link href="unetworking.css" rel="stylesheet">
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
    </header>

    <main class="main-content">
        <div class="card">
            <h2>Networking</h2>
            <p>Welcome, <?php echo htmlspecialchars($user['name_with_initials']); ?></p>
            <p>Connect with other participants, speakers, and industry professionals through the conference networking platform.</p>
            <form action="join_networking.php" method="POST">
                <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
                <button type="submit">Join Networking Lounge</button>
            </form>
        </div>
    </main>

    <footer>
        &copy; <?php echo date("Y"); ?> International Research Conference. All rights reserved.
    </footer>
</body>
</html>
<?php $conn->close(); ?>
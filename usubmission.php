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
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Initialize variables to prevent undefined variable errors
$name = $user['name_with_initials'] ?? '';
$email = $user['email_address'] ?? '';
$paper_title = '';
$abstract = '';
$message = '';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $paper_title = $_POST['paper_title'] ?? '';
    $abstract = $_POST['abstract'] ?? '';
    
    // Add submission to database
    $submit_stmt = $conn->prepare("INSERT INTO submissions (user_id, paper_title, abstract) VALUES (?, ?, ?)");
    $submit_stmt->bind_param("iss", $user_id, $paper_title, $abstract);
    
    if ($submit_stmt->execute()) {
        $message = "Submission successful!";
    } else {
        $message = "Error submitting paper. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submissions</title>
    <link href="usubmissions.css" rel="stylesheet">
</head>
<body>
    <header>
        <div class="logo">International Research Conference</div>
        <nav>
            <a href="userdash.php">Home</a>
            <a href="dashboard.php">Dashboard</a>
            <a href="schedule.php">Schedule</a>
            <a href="sessions.php">Sessions</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <main class="main-content">
        <div class="card">
            <h2>Submissions</h2>
            <p>Submit your research papers, posters, and abstracts to the conference for review and approval.</p>

            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                <label for="name">Full Name:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" readonly><br><br>

                <label for="email">Email Address:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" readonly><br><br>

                <label for="paper_title">Paper Title:</label>
                <input type="text" id="paper_title" name="paper_title" value="<?php echo htmlspecialchars($paper_title); ?>" required><br><br>

                <label for="abstract">Abstract:</label><br>
                <textarea id="abstract" name="abstract" rows="4" cols="50" required><?php echo htmlspecialchars($abstract); ?></textarea><br><br>

                <button type="submit">Submit Your Paper</button>
            </form>

            <?php if (!empty($message)): ?>
                <div class="message"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>
        </div>
    </main>

    <footer>
        &copy; <?php echo date("Y"); ?> International Research Conference. All rights reserved.
    </footer>
</body>
</html>
<?php $conn->close(); ?>
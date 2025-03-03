<!-- announcements.php -->
<?php
session_start();
require_once 'config.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: log.php');
    exit();
}

// Handle new announcement submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];
    
    $sql = "INSERT INTO announcements (title, content, created_at) VALUES (?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$title, $content]);
    
    header('Location: admannouncement.php');
    exit();
}

// Fetch all announcements
$sql = "SELECT * FROM announcements ORDER BY created_at DESC";
$announcements = $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcements - International Research Conference</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Manage Announcements</h1>
        
        <div class="announcement-form">
            <h2>Create New Announcement</h2>
            <form method="POST">
                <div class="form-group">
                    <label for="title">Title:</label>
                    <input type="text" id="title" name="title" required>
                </div>
                
                <div class="form-group">
                    <label for="content">Content:</label>
                    <textarea id="content" name="content" required></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary">Post Announcement</button>
            </form>
        </div>
        
        <div class="announcements-list">
            <h2>Current Announcements</h2>
            <?php foreach ($announcements as $announcement): ?>
            <div class="announcement-card">
                <h3><?php echo htmlspecialchars($announcement['title']); ?></h3>
                <p class="date">Posted: <?php echo date('F j, Y', strtotime($announcement['created_at'])); ?></p>
                <p class="content"><?php echo nl2br(htmlspecialchars($announcement['content'])); ?></p>
                <div class="actions">
                    <a href="edit_announcement.php?id=<?php echo $announcement['id']; ?>" class="btn btn-edit">Edit</a>
                    <a href="delete_announcement.php?id=<?php echo $announcement['id']; ?>" 
                       class="btn btn-delete" 
                       onclick="return confirm('Are you sure you want to delete this announcement?')">Delete</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
<?php
// config.php
define('DB_HOST', 'localhost');
define('DB_NAME', 'IRC');
define('DB_USER', 'root');  // Update with your database username
define('DB_PASS', '');      // Update with your database password

try {
    $conn = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
        DB_USER,
        DB_PASS
    );
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>

<?php
// admsubmission.php
session_start();
include 'config.php';

// Initialize $submissions as an empty array
$submissions = [];

try {
    // Fetch all submissions with user information
    $sql = "SELECT s.*, u.name as author_name 
            FROM submissions s 
            JOIN users u ON s.user_id = u.id 
            ORDER BY s.submitted_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $submissions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Handle submission actions (approve/reject)
    if (isset($_POST['action']) && isset($_POST['submission_id'])) {
        $action = $_POST['action'];
        $submission_id = $_POST['submission_id'];
        
        $status = ($action === 'approve') ? 'approved' : 'rejected';
        $update_sql = "UPDATE submissions SET status = :status WHERE id = :id";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bindParam(':status', $status);
        $update_stmt->bindParam(':id', $submission_id);
        $update_stmt->execute();

        // Redirect to refresh the page after update
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
    $submissions = []; // Ensure $submissions is an array even if query fails
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Submissions - International Research Conference</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }

        .submission-card {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
            background-color: #fff;
        }

        .submission-card h3 {
            color: #2c3e50;
            margin-top: 0;
        }

        .submission-info {
            margin: 10px 0;
            color: #666;
        }

        .status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 3px;
            font-weight: bold;
        }

        .status-pending {
            background-color: #ffd700;
            color: #000;
        }

        .status-approved {
            background-color: #2ecc71;
            color: #fff;
        }

        .status-rejected {
            background-color: #e74c3c;
            color: #fff;
        }

        .actions {
            margin-top: 15px;
        }

        .btn {
            display: inline-block;
            padding: 8px 15px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            text-decoration: none;
            color: #fff;
            font-size: 14px;
            margin-right: 10px;
        }

        .btn-view {
            background-color: #3498db;
        }

        .btn-approve {
            background-color: #2ecc71;
        }

        .btn-reject {
            background-color: #e74c3c;
        }

        .btn:hover {
            opacity: 0.9;
        }

        .abstract {
            margin: 10px 0;
            color: #666;
            line-height: 1.6;
        }

        .file-link {
            color: #3498db;
            text-decoration: none;
        }

        .file-link:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }

            .btn {
                display: block;
                width: 100%;
                margin-bottom: 5px;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Manage Submissions</h1>
        
        <div class="submissions-list">
            <?php if (!empty($submissions)): ?>
                <?php foreach ($submissions as $submission): ?>
                    <div class="submission-card">
                        <h3><?php echo htmlspecialchars($submission['title'] ?? 'Untitled'); ?></h3>
                        
                        <div class="submission-info">
                            <p><strong>Author:</strong> <?php echo htmlspecialchars($submission['author_name'] ?? 'Unknown'); ?></p>
                            <p><strong>Submitted:</strong> <?php echo isset($submission['submitted_at']) ? date('F j, Y', strtotime($submission['submitted_at'])) : 'Date not available'; ?></p>
                            <p><strong>Status:</strong> 
                                <span class="status status-<?php echo htmlspecialchars($submission['status'] ?? 'pending'); ?>">
                                    <?php echo ucfirst(htmlspecialchars($submission['status'] ?? 'pending')); ?>
                                </span>
                            </p>
                        </div>

                        <?php if (isset($submission['abstract'])): ?>
                            <div class="abstract">
                                <strong>Abstract:</strong><br>
                                <?php echo nl2br(htmlspecialchars($submission['abstract'])); ?>
                            </div>
                        <?php endif; ?>

                        <?php if (isset($submission['file_path'])): ?>
                            <p><a href="<?php echo htmlspecialchars($submission['file_path']); ?>" class="file-link" download>Download Submission</a></p>
                        <?php endif; ?>

                        <div class="actions">
                            <a href="view_submission.php?id=<?php echo htmlspecialchars($submission['id'] ?? ''); ?>" class="btn btn-view">View Details</a>
                            
                            <?php if (isset($submission['status']) && $submission['status'] === 'pending'): ?>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="submission_id" value="<?php echo htmlspecialchars($submission['id']); ?>">
                                    <button type="submit" name="action" value="approve" class="btn btn-approve">Approve</button>
                                    <button type="submit" name="action" value="reject" class="btn btn-reject">Reject</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No submissions found.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
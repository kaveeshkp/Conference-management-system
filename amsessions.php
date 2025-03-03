<?php
// Include database connection
include('db.php');

// Handle deletion of a session
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM sessions WHERE id = $id");
    header("Location: amsessions.php");
    exit();
}

// Handle form submission for adding or editing sessions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $conn->real_escape_string($_POST['title']);
    $start_time = $conn->real_escape_string($_POST['start_time']);
    $end_time = $conn->real_escape_string($_POST['end_time']);
    $location = $conn->real_escape_string($_POST['location']);
    $speaker = $conn->real_escape_string($_POST['speaker']);
    $description = $conn->real_escape_string($_POST['description']);

    if (isset($_POST['id']) && $_POST['id'] != '') {
        // Update existing session
        $id = intval($_POST['id']);
        $conn->query("UPDATE sessions SET title = '$title', start_time = '$start_time', end_time = '$end_time', location = '$location', speaker = '$speaker', description = '$description' WHERE id = $id");
    } else {
        // Add new session
        $created_at = date('Y-m-d H:i:s');
        $conn->query("INSERT INTO sessions (title, start_time, end_time, location, speaker, description, created_at) VALUES ('$title', '$start_time', '$end_time', '$location', '$speaker', '$description', '$created_at')");
    }

    header("Location: amsessions.php");
    exit();
}

// Fetch all sessions
$result = $conn->query("SELECT * FROM sessions");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Sessions</title>
    <link rel="stylesheet" href="styles.css"> <!-- Add your CSS file here -->
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        .btn {
            padding: 5px 10px;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn-edit {
            background-color: #4CAF50;
        }
        .btn-delete {
            background-color: #f44336;
        }
        .btn:hover {
            opacity: 0.8;
        }
        .form-container {
            margin: 20px 0;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #f9f9f9;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input, .form-group textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <h1>Manage Sessions</h1>

    <!-- Form for Adding/Editing a Session -->
    <div class="form-container">
        <form method="POST" action="amsessions.php">
            <input type="hidden" name="id" id="session_id">
            <div class="form-group">
                <label for="title">Session Title</label>
                <input type="text" id="title" name="title" required>
            </div>
            <div class="form-group">
                <label for="start_time">Start Time</label>
                <input type="datetime-local" id="start_time" name="start_time" required>
            </div>
            <div class="form-group">
                <label for="end_time">End Time</label>
                <input type="datetime-local" id="end_time" name="end_time" required>
            </div>
            <div class="form-group">
                <label for="location">Location</label>
                <input type="text" id="location" name="location" required>
            </div>
            <div class="form-group">
                <label for="speaker">Speaker</label>
                <input type="text" id="speaker" name="speaker" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="4" required></textarea>
            </div>
            <button class="btn btn-edit" type="submit">Save Session</button>
        </form>
    </div>

    <!-- Table for Displaying Sessions -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Location</th>
                <th>Speaker</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['title']) ?></td>
                    <td><?= htmlspecialchars($row['start_time']) ?></td>
                    <td><?= htmlspecialchars($row['end_time']) ?></td>
                    <td><?= htmlspecialchars($row['location']) ?></td>
                    <td><?= htmlspecialchars($row['speaker']) ?></td>
                    <td><?= htmlspecialchars($row['description']) ?></td>
                    <td>
                        <button class="btn btn-edit" onclick="editSession(<?= htmlspecialchars(json_encode($row)) ?>)">Edit</button>
                        <a class="btn btn-delete" href="amsessions.php?delete=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to delete this session?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <script>
        function editSession(session) {
            document.getElementById('session_id').value = session.id;
            document.getElementById('title').value = session.title;
            document.getElementById('start_time').value = session.start_time.replace(" ", "T");
            document.getElementById('end_time').value = session.end_time.replace(" ", "T");
            document.getElementById('location').value = session.location;
            document.getElementById('speaker').value = session.speaker;
            document.getElementById('description').value = session.description;
        }
    </script>
</body>
</html>

<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "IRC";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = htmlspecialchars($_POST['email_address']);
    $password = $_POST['password'];
    $user_role = $_POST['user_role'];  // Get the selected role

    // Retrieve user data from the 'users' table
    $sql = "SELECT * FROM users WHERE email_address=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            // Start session to store user information
            session_start();
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['email'] = $row['email_address'];
            $_SESSION['user_role'] = $user_role;

            // Check user role and redirect accordingly
            if ($user_role === "Participant") {
                header("Location: userdash.php");
                exit();
            } elseif ($user_role === "Admin") {
                header("Location: admindash.php");
                exit();
            }
        } else {
            // Invalid password - Show alert and redirect
            echo "<script>
                alert('Wrong password! Please try again.');
                window.location.href = 'log.html';
            </script>";
            exit();
        }
    } else {
        // No user found - Show alert and redirect
        echo "<script>
            alert('Email not found! Please check your email address.');
            window.location.href = 'log.html';
        </script>";
        exit();
    }

    $stmt->close();
}
$conn->close();
?>
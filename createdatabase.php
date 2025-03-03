<?php
// Database connection details
$host = 'localhost'; // Database host (usually localhost)
$username = 'root';  // Database username
$password = '';      // Database password (empty for default setup)
$database = 'IRC'; // Database name (this can be any name)

// Create connection
$conn = new mysqli($host, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create the database if it doesn't exist
$sql_create_db = "CREATE DATABASE IF NOT EXISTS $database";
if ($conn->query($sql_create_db) === TRUE) {
    echo "Database '$database' created or already exists successfully.<br>";
} else {
    echo "Error creating database '$database': " . $conn->error . "<br>";
}

// Select the database
$conn->select_db($database);

// Create the users table
$sql_create_users_table = "
    CREATE TABLE IF NOT EXISTS users ( 
        id INT AUTO_INCREMENT PRIMARY KEY,
        name_with_initials VARCHAR(255) NOT NULL,
        participation_category VARCHAR(50) NOT NULL,
        email_address VARCHAR(255) NOT NULL,
        nic_passport VARCHAR(100) NOT NULL,
        mobile_number VARCHAR(20) NOT NULL,
        country VARCHAR(100) NOT NULL,
        username VARCHAR(100) NOT NULL,
        password VARCHAR(255) NOT NULL
    );
";

if ($conn->query($sql_create_users_table) === TRUE) {
    echo "Table 'users' created successfully.<br>";
} else {
    echo "Error creating table 'users': " . $conn->error . "<br>";
}





// Close the connection
$conn->close();
?>
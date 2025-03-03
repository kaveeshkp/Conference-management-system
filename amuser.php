<?php
// Database connection
include('db.php');

// Handle deletion
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $query = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    header("Location: ?page=manage_users");
    exit();
}

// Handle form submission for adding/editing users
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $name_with_initials = $_POST['name_with_initials'];
    $participation_category = $_POST['participation_category'];
    $email_address = $_POST['email_address'];
    $nic_passport = $_POST['nic_passport'];
    $mobile_number = $_POST['mobile_number'];
    $country = $_POST['country'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    if ($id) {
        // Update existing user
        $query = "UPDATE users SET name_with_initials=?, participation_category=?, email_address=?, nic_passport=?, mobile_number=?, country=?, username=?, password=? WHERE id=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssssssi", $name_with_initials, $participation_category, $email_address, $nic_passport, $mobile_number, $country, $username, $password, $id);
    } else {
        // Add new user
        $query = "INSERT INTO users (name_with_initials, participation_category, email_address, nic_passport, mobile_number, country, username, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssssss", $name_with_initials, $participation_category, $email_address, $nic_passport, $mobile_number, $country, $username, $password);
    }

    $stmt->execute();
    header("Location: ?page=manage_users");
    exit();
}

// Fetch all users
$query = "SELECT * FROM users";
$result = $conn->query($query);
$users = $result->fetch_all(MYSQLI_ASSOC);
?>

<div class="main-content">
    <h2>Manage Users</h2>

    <!-- Add User Form -->
    <h3>Add/Edit User</h3>
    <form method="post" action="?page=manage_users">
        <input type="hidden" name="id" id="id">
        <label for="name_with_initials">Name:</label>
        <input type="text" name="name_with_initials" id="name_with_initials" required>
        <label for="participation_category">Category:</label>
        <input type="text" name="participation_category" id="participation_category" required>
        <label for="email_address">Email:</label>
        <input type="email" name="email_address" id="email_address" required>
        <label for="nic_passport">NIC/Passport:</label>
        <input type="text" name="nic_passport" id="nic_passport" required>
        <label for="mobile_number">Mobile:</label>
        <input type="text" name="mobile_number" id="mobile_number" required>
        <label for="country">Country:</label>
        <input type="text" name="country" id="country" required>
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required>
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>
        <button type="submit">Submit</button>
    </form>

    <!-- Users Table -->
    <h3>All Users</h3>
    <table>
        <thead>
        <link rel="stylesheet" href="amuser.css">

            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Category</th>
                <th>Email</th>
                <th>NIC/Passport</th>
                <th>Mobile</th>
                <th>Country</th>
                <th>Username</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['id']); ?></td>
                    <td><?php echo htmlspecialchars($user['name_with_initials']); ?></td>
                    <td><?php echo htmlspecialchars($user['participation_category']); ?></td>
                    <td><?php echo htmlspecialchars($user['email_address']); ?></td>
                    <td><?php echo htmlspecialchars($user['nic_passport']); ?></td>
                    <td><?php echo htmlspecialchars($user['mobile_number']); ?></td>
                    <td><?php echo htmlspecialchars($user['country']); ?></td>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                    <td>
                        <a href="javascript:void(0);" onclick="editUser(<?php echo htmlspecialchars(json_encode($user)); ?>)">Edit</a>
                        <a href="?page=manage_users&delete_id=<?php echo $user['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
    function editUser(user) {
        document.getElementById('id').value = user.id;
        document.getElementById('name_with_initials').value = user.name_with_initials;
        document.getElementById('participation_category').value = user.participation_category;
        document.getElementById('email_address').value = user.email_address;
        document.getElementById('nic_passport').value = user.nic_passport;
        document.getElementById('mobile_number').value = user.mobile_number;
        document.getElementById('country').value = user.country;
        document.getElementById('username').value = user.username;
        document.getElementById('password').value = '';
    }
</script>

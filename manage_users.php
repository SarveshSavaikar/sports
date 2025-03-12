<?php

include 'db_connection.php';


// Add or Update User
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $prize_charged = isset($_POST['prize_charged']) ? $_POST['prize_charged'] : null;
    $id = isset($_POST['id']) ? $_POST['id'] : '';

    if ($id) {
        // Update existing user
        $query = "UPDATE users SET name='$name', email='$email', role='$role', prize_charged='$prize_charged' WHERE id='$id'";
    } else {
        // Add new user
        $password = password_hash('default123', PASSWORD_BCRYPT); // Default password
        $query = "INSERT INTO users (name, email, role, prize_charged, password) VALUES ('$name', '$email', '$role', '$prize_charged', '$password')";
    }

    if ($conn->query($query) === TRUE) {
        $message = $id ? "User updated successfully." : "User added successfully.";
    } else {
        $message = "Error: " . $conn->error;
    }
}

// Delete User
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_query = "DELETE FROM users WHERE id='$delete_id'";
    if ($conn->query($delete_query) === TRUE) {
        $message = "User deleted successfully.";
    } else {
        $message = "Error deleting user: " . $conn->error;
    }
}

// Fetch all users
$users_query = "SELECT * FROM users";
$users_result = $conn->query($users_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        form {
            margin: 20px 0;
        }
        form input, form select {
            padding: 8px;
            margin: 5px;
        }
        form button {
            padding: 8px 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        form button:hover {
            background-color: #45a049;
        }
        .message {
            color: green;
            margin: 10px 0;
        }
        .error {
            color: red;
        }
    </style>
</head>
<!-- Back Button -->
<button onclick="history.back()">Go Back</button>
<body>

<h1>Manage Users</h1>

<?php if (isset($message)): ?>
    <p class="message"><?= $message; ?></p>
<?php endif; ?>

<!-- Add or Update User Form -->
<form action="manage_users.php" method="post">
    <input type="hidden" name="id" id="user_id" value="">
    <input type="text" name="name" id="name" placeholder="Name" required>
    <input type="email" name="email" id="email" placeholder="Email" required>
    <select name="role" id="role" required>
        <option value="" disabled selected>Select Role</option>
        <option value="coach">Coach</option>
        <option value="physiotherapist">Physiotherapist</option>
        <option value="nutritionist">Nutritionist</option>
        <option value="admin">Admin</option>
    </select>
    <input type="number" name="prize_charged" id="prize_charged" placeholder="Prize Charged (if applicable)">
    <button type="submit">Save</button>
</form>

<!-- Users Table -->
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Prize Charged</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($user = $users_result->fetch_assoc()): ?>
            <tr>
                <td><?= $user['id']; ?></td>
                <td><?= htmlspecialchars($user['name']); ?></td>
                <td><?= htmlspecialchars($user['email']); ?></td>
                <td><?= htmlspecialchars($user['role']); ?></td>
                <td><?= htmlspecialchars($user['prize_charged']); ?></td>
                <td>
                    <button onclick="editUser(<?= htmlspecialchars(json_encode($user)); ?>)">Edit</button>
                    <a href="manage_users.php?delete_id=<?= $user['id']; ?>" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<script>
    function editUser(user) {
        document.getElementById('user_id').value = user.id;
        document.getElementById('name').value = user.name;
        document.getElementById('email').value = user.email;
        document.getElementById('role').value = user.role;
        document.getElementById('prize_charged').value = user.prize_charged;
    }
</script>

</body>
</html>

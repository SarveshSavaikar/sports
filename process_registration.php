<?php
// Database connection
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'goa_scores';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user input
$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];
$role = $_POST['role'];

// Check if email already exists
$query = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Email already exists, show error message
    header("Location: register.php?status=email_exists");
    exit();
}

// Prepare the SQL query to insert the new user
$query = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("ssss", $name, $email, $password, $role);

// Execute the query
if ($stmt->execute()) {
    // Redirect to the register page with a success message
    header("Location: register.php?status=success");
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>

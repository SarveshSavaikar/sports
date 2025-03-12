<?php
include('db.php');

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Check if the token is valid and not expired
    $sql = "SELECT * FROM users WHERE reset_token = '$token' AND reset_expiry > NOW()";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $new_password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];

            if ($new_password === $confirm_password) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                // Update the password and clear the token
                $sql = "UPDATE users SET password = '$hashed_password', reset_token = NULL, reset_expiry = NULL WHERE reset_token = '$token'";
                if ($conn->query($sql)) {
                    echo "Password has been reset successfully!";
                } else {
                    echo "Failed to reset password.";
                }
            } else {
                echo "Passwords do not match.";
            }
        }
    } else {
        echo "Invalid or expired token.";
        exit;
    }
} else {
    echo "No token provided.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 400px;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        form label {
            display: block;
            margin: 10px 0 5px;
        }
        form input, form button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        form button {
            background: #007bff;
            color: #fff;
            border: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Reset Password</h1>
        <form method="POST">
            <label for="password">New Password:</label>
            <input type="password" name="password" required>
            
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" name="confirm_password" required>

            <button type="submit">Reset Password</button>
        </form>
    </div>
</body>
</html>

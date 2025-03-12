<?php
include('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    // Check if the email exists in the database
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $token = bin2hex(random_bytes(50)); // Generate a random token
        $expiry = date("Y-m-d H:i:s", strtotime('+1 hour')); // Token valid for 1 hour

        // Store the token and expiry in the database
        $sql = "UPDATE users SET reset_token = '$token', reset_expiry = '$expiry' WHERE email = '$email'";
        if ($conn->query($sql)) {
            // Send reset email
            $reset_link = "http://localhost/sports/reset_password.php?token=$token";
            $subject = "Password Reset Request";
            $message = "Click the link to reset your password: $reset_link";
            $headers = "From: no-reply@sports.com";

            if (mail($email, $subject, $message, $headers)) {
                echo "A password reset link has been sent to your email.";
            } else {
                echo "Failed to send email.";
            }
        } else {
            echo "Error updating token.";
        }
    } else {
        echo "Email not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
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
        <h1>Forgot Password</h1>
        <form method="POST">
            <label for="email">Enter your email:</label>
            <input type="email" name="email" required>
            <button type="submit">Submit</button>
        </form>
    </div>
</body>
</html>

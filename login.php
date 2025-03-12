<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: url('uploads/photos/W.png') no-repeat center center/cover;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        header {
            text-align: center;
            color: white;
            font-size: 24px;
            margin-bottom: 20px;
            background: rgba(0, 0, 0, 0.7);
            padding: 15px;
            width: 100%;
        }
        form {
            background: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 400px;
            display: flex;
            flex-direction: column;
        }
        label {
            font-weight: bold;
            margin-top: 10px;
        }
        input, select {
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            margin-top: 15px;
            padding: 10px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }
        button:hover {
            background: #0056b3;
        }
        .back-btn {
            position: absolute;
            top: 20px;
            left: 20px;
            padding: 8px 15px;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }
        .back-btn:hover {
            background: #218838;
        }
    </style>
</head>
<body>

<!-- Back Button -->
<button class="back-btn" onclick="history.back()">Go Back</button>

<header>
    <h1>Login</h1>
</header>

<form action="process_login.php" method="post">
    <label for="role">Login As:</label>
    <select id="role" name="role" required>
        <option value="admin">Admin</option>
        <option value="coach">Coach</option>
        <option value="physiotherapist">Physiotherapist</option>
        <option value="nutritionist">Nutritionist</option>
        <option value="groundmanager">Ground Manager</option>
        <option value="customer">Customer</option>
    </select>
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>
    <button type="submit">Login</button>
</form>

</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GOA SCORES</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: url('uploads/photos/banner.png') no-repeat center center/cover;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        header {
            color: white;
            text-align: center;
            padding: 40px 10px;
            background: rgba(0, 0, 0, 0.6);
        }
        nav {
            display: flex;
            justify-content: center;
            background: rgba(0, 0, 0, 0.7);
            padding: 11px;
        }
        nav a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            font-size: 18px;
            transition: 0.3s;
        }
        nav a:hover {
            color: #f4a261;
        }
        .content {
            flex-grow: 1;
            text-align: center;
            padding: 2px;
            color: white;
            background: rgba(0, 0, 0, 0.66);
            margin: 20px;
            border-radius: 10px;
        }
        footer {
            background: rgba(0, 0, 0, 0.8);
            color: white;
            text-align: center;
            padding: 10px;
            margin-top: auto;
        }
    </style>
</head>
<body>
    <header>
        <h1>Welcome to GOA SCORES</h1>
    </header>
    
    <nav>
        <a href="register.php">Register</a>
        <a href="login.php">Login</a>
        <!-- <a href="club-details.php">View Club Details</a> -->
        <a href="Coaches.php">Coaches</a>
        <a href="Physiotherapist.php">Physiotherapist</a>
        <a href="Nutritionist.php">Nutritionist</a>
    </nav>
    
    <div class="content">
        <p>Explore the best sports clubs, coaches, physiotherapists, and nutritionists in Goa.</p>
    </div>
    
    <footer>
        <p>&copy; 2025 GOA SCORES</p>
    </footer>
</body>
</html>

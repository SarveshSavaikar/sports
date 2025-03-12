<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - GOA SCORES</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="login.php">Login</a></li>
                <li><a href="register.php">Register</a></li>
            </ul>
        </nav>
    </header>
    
    <main>
        <section class="intro">
            <h1>Welcome to GOA SCORES</h1>
            <p>Your go-to platform for booking appointments with Coaches, Physiotherapists, Nutritionists, and booking grounds and equipment for various sports activities.</p>
        </section>

        <section class="registration-links">
            <h2>Quick Links</h2>
            <div class="links">
                <a href="register.php?role=coach">Register as Coach</a>
                <a href="register.php?role=physiotherapist">Register as Physiotherapist</a>
                <a href="register.php?role=nutritionist">Register as Nutritionist</a>
                <a href="register.php?role=customer">Register as Customer</a>
            </div>
        </section>

        <section class="club-details">
            <h2>View Club Details</h2>
            <form action="" method="GET" class="search-form">
                <input type="text" name="search" placeholder="Search club or sport" value="">
                <button type="submit">Search</button>
            </form>
            <div class="club-list">
                <!-- Here, you can dynamically fetch and display clubs or sports based on the search -->
                <div class="club-item">
                    <h3>Club Name</h3>
                    <p>Details about the club or sports offered.</p>
                </div>
                <div class="club-item">
                    <h3>Another Club</h3>
                    <p>Details about the club or sports offered.</p>
                </div>
                <!-- Add more clubs as necessary -->
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2025 GOA SCORES. All rights reserved.</p>
    </footer>

</body>
</html>

<?php
session_start();
include 'db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['user']['id'])) {
    echo "<script>alert('Please log in to book an appointment.'); window.location.href='login.php';</script>";
    exit();
}

$user_id = $_SESSION['user']['id']; // Get logged-in user ID

// Initialize variables
$role = '';
$professionals = [];

// Handle form submission for selecting the professional role
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['role'])) {
        // Get the selected role (coach, physiotherapist, nutritionist)
        $role = $_POST['role'];

        // Fetch professionals based on selected role, including the prize_charged
        $query = "SELECT id, name, post, prize_charged FROM users WHERE role = '$role'";
        $result = $conn->query($query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $professionals[] = $row;
            }
        }
    } elseif (isset($_POST['professional_id'])) {
        // Handle appointment booking
        $professional_id = $_POST['professional_id'];
        $customer_name = $_POST['customer_name'];
        $appointment_date = $_POST['appointment_date'];
        $appointment_time = $_POST['appointment_time'];
        $currentdateandtime = date("Y-m-d H:i:s");
        

        // Insert appointment into the database
        $insert_query = "INSERT INTO appointments (id, customer_name, professional_id, appointment_date, appointment_time, status , adminstatus) 
                         VALUES ('$user_id', '$customer_name', '$professional_id', '$appointment_date', '$appointment_time', 'pending' , 'pending')";
        if ($conn->query($insert_query) === TRUE) {
            $appointment_id = $conn->insert_id; // Get the ID of the newly inserted appointment
            // Redirect to payment page
            header("Location: pay.php?appointment_id=$appointment_id");
            exit();
        } else {
            echo "<script>alert('Error booking appointment. Please try again later.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('uploads/photos/l.png') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            color: #333;
            margin: 20px 0;
        }

        .form-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .form-container label {
            display: block;
            margin: 10px 0 5px;
        }

        .form-container input, .form-container select {
            width: 100%;
            padding: 10px;
            margin: 5px 0 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .form-container button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .form-container button:hover {
            background-color: #45a049;
        }

        .form-container .professional-list {
            margin-bottom: 20px;
        }

        .form-container .professional-item {
            margin-bottom: 10px;
        }

        .professional-item span {
            color: #555;
            font-size: 14px;
        }
    </style>
</head>
<body>

<!-- Back Button -->
<button onclick="history.back()">Go Back</button>

<h1>Book Appointment</h1>

<div class="form-container">
    <?php if (empty($role)): ?>
        <!-- Step 1: Ask for the professional role --> 
        <form action="book_appointment.php" method="post">
            <label for="role">Choose Role:</label>
            <select id="role" name="role" required>
                <option value="" disabled selected>Select a role</option>
                <option value="coach">Coach</option>
                <option value="physiotherapist">Physiotherapist</option>
                <option value="nutritionist">Nutritionist</option>
            </select>
            <button type="submit">Next</button>
        </form>
    <?php else: ?>
        <!-- Step 2: Show professionals based on selected role -->
        <form action="book_appointment.php" method="post">
            <label for="professional_id">Select a Professional:</label>
            <select id="professional_id" name="professional_id" required>
                <option value="" disabled selected>Select a professional</option>
                <?php foreach ($professionals as $professional): ?>
                    <option value="<?= $professional['id']; ?>">
                        <?= htmlspecialchars($professional['name']); ?> (<?= htmlspecialchars($professional['post']); ?>) 
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="customer_name">Your Name:</label>
            <input type="text" id="customer_name" name="customer_name" required>

            <label for="appointment_date">Select Date:</label>
            <input type="date" id="appointment_date" name="appointment_date" required>

            <label for="appointment_time">Select Time:</label>
            <input type="time" id="appointment_time" name="appointment_time" required>

            <button type="submit">Book Appointment</button>
        </form>
    <?php endif; ?>
</div>

</body>
</html>

<?php
session_start();

// Check if user is logged in and has the 'admin' role
if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] != 'groundmanager' && $_SESSION['user']['role'] != 'admin')) {
    header("Location: login.php");
    exit();
}

include 'db_connection.php';

// Check if the admin is accepting or rejecting a booking

if (isset($_GET['action']) && isset($_GET['bookings_id'])) {
    $action = $_GET['action'];
    $booking_id = $_GET['bookings_id'];
    $current_user_role = $_SESSION['user']['role'] ?? 'guest';
    if($current_user_role == 'admin'){
        
        if ($action == 'approve' || $action == 'deny') {
            $status = ($action == 'approve') ? 'accepted' : 'rejected';
    
            // Update the status in the database
            $query = "UPDATE bookings SET adminstatus = ? WHERE bookings_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("si", $status, $booking_id);
            $stmt->execute();
            header("Location: manage_bookings.php");
            exit();
        }
    }

    // Only accept or reject if action is valid
    elseif ($action == 'accept' || $action == 'reject') {

        echo "as groundmanager";


        $status = ($action == 'accept') ? 'accepted' : 'rejected';

        // Update the status in the database
        $query = "UPDATE bookings SET status = ? WHERE bookings_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $status, $booking_id);
        $stmt->execute();
        header("Location: manage_bookings.php");
        exit();
    }
}

// Check if the admin wants to edit the status of an accepted/rejected booking
if (isset($_GET['edit_action']) && isset($_GET['bookings_id'])) {
    $edit_action = $_GET['edit_action'];
    $bookings_id = $_GET['bookings_id'];

    if ($edit_action == 'reverse') {
        // Reverse the status of the booking (accepted to pending or rejected to pending)
        if($_SESSION['user']['role'] == 'admin'){
            $query = "UPDATE bookings SET adminstatus = 'pending' WHERE bookings_id = ?";
        }
        else{
            $query = "UPDATE bookings SET status = 'pending' WHERE bookings_id = ?";
        }
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $bookings_id);
        $stmt->execute();
        header("Location: manage_bookings.php");
    }
}

// Fetch all bookings with customer details and additional info
$query = "SELECT bookings.id AS booking_id, users.name AS customer_name, bookings.ground_id, 
                 bookings.start_date, bookings.end_date, bookings.start_time, bookings.end_time, 
                 bookings.total_time, bookings.total_price, bookings.status , bookings.adminstatus , bookings_id
          FROM bookings
          JOIN users ON bookings.id = users.id"; // Fixed the join to correctly reference user_id
$result = $conn->query($query);

if($_SESSION['user']['role'] == 'admin'){
    $query = "SELECT bookings.id AS booking_id, users.name AS customer_name, bookings.ground_id, 
                 bookings.start_date, bookings.end_date, bookings.start_time, bookings.end_time, 
                 bookings.total_time, bookings.total_price, bookings.status ,bookings.adminstatus , bookings.bookings_id
          FROM bookings
          JOIN users ON bookings.id = users.id
          WHERE bookings.status = 'accepted' AND ( bookings.adminstatus = 'pending' or bookings.adminstatus = 'accepted' or bookings.adminstatus = 'rejected')"; // Fixed the join to correctly reference user_id
    $result = $conn->query($query);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bookings</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            color: #333;
            margin: 20px 0;
        }

        .table-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        table th, table td {
            padding: 15px;
            text-align: left;
            font-size: 16px;
        }

        table th {
            background-color: #4CAF50;
            color: white;
        }

        table td {
            background-color: #f9f9f9;
            border-bottom: 1px solid #ddd;
        }

        table td a {
            color: #4CAF50;
            text-decoration: none;
            margin-right: 15px;
        }

        table td a:hover {
            color: #45a049;
        }

        table td span {
            color: #e53935;
            font-weight: bold;
        }

        .actions {
            display: flex;
            gap: 10px;
        }

        .actions a, .actions span {
            padding: 8px 15px;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
        }

        .actions a.accept {
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
        }

        .actions a.reject {
            background-color: #f44336;
            color: white;
            text-decoration: none;
        }

        .actions a.accept:hover {
            background-color: #45a049;
        }

        .actions a.reject:hover {
            background-color: #e53935;
        }

        .reverse {
            background-color: #f0ad4e;
            color: white;
            text-decoration: none;
        }

        .reverse:hover {
            background-color: #ec971f;
        }
    </style>
</head><!-- Back Button -->
<button onclick="history.back()">Go Back</button>
<?php
// Assume you have a way to determine the current user role
$current_user_role = $_SESSION['user']['role'] ?? 'guest'; // Example: "groundmanager" or "admin"
echo $current_user_role;
?>
<body>
    <h1>Manage Bookings</h1>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Customer Name</th>
                    <th>Ground ID</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Total Time</th>
                    <th>Total Price</th>
                    <th>Status</th>
                    <th>Admin Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                
                <?php while ($booking = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $booking['booking_id']; ?></td>
                        <td><?= htmlspecialchars($booking['customer_name']); ?></td>
                        <td><?= $booking['ground_id']; ?></td>
                        <td><?= htmlspecialchars($booking['start_date']); ?></td>
                        <td><?= htmlspecialchars($booking['end_date']); ?></td>
                        <td><?= htmlspecialchars($booking['start_time']); ?></td>
                        <td><?= htmlspecialchars($booking['end_time']); ?></td>
                        <td><?= htmlspecialchars($booking['total_time']); ?> hours</td>
                        <td>₹<?= htmlspecialchars($booking['total_price']); ?></td>
                        <td><?= ucfirst($booking['status']); ?></td>
                        <td><?= ucfirst($booking['adminstatus'] ?? 'N/A'); ?></td>
                        <td class="actions">
                        <?php if ($current_user_role === 'groundmanager' && $booking['status'] == 'pending'): ?>
                                <a class="accept" href="?action=accept&bookings_id=<?= $booking['bookings_id']; ?>">Accept</a>
                                <a class="reject" href="?action=reject&bookings_id=<?= $booking['bookings_id']; ?>">Reject</a>
                            <?php elseif ($current_user_role === 'admin' && ($booking['adminstatus'] == 'pending' || $booking['adminstatus'] == NULL && $booking['status'] != 'pending')): ?>
                                <a class="accept" href="?action=approve&bookings_id=<?= $booking['bookings_id']; ?>">Approve</a>
                                <a class="reject" href="?action=deny&bookings_id=<?= $booking['bookings_id']; ?>">Deny</a>
                            <?php elseif ($booking['status'] == 'accepted' || $booking['status'] == 'rejected' || $booking['adminstatus'] == 'approved' || $booking['adminstatus'] == 'denied'): ?>
                                <a class="reverse" href="?edit_action=reverse&bookings_id=<?= $booking['bookings_id']; ?>">Reverse Status</a>
                            <?php else: ?>
                                <span>Action Not Available</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</body>
</html>

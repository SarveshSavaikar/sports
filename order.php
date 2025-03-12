<?php
session_start();

// Check if user is logged in and has the 'admin' role
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

include 'db_connection.php';

// Fetch all equipment data
$query = "SELECT * FROM equipment";
$result = $conn->query($query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Equipment</title>
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

        .equipment-photo {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <h1>Manage Equipment</h1>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Equipment ID</th>
                    <th>Name</th>
                    <th>Photo</th>
                    <th>Location</th>
                    <th>Contact</th>
                    <th>Price</th>
                    <th>Availability</th>
                    <th>Sports Category</th>
                    <th>Quantity</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($equipment = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $equipment['id']; ?></td>
                        <td><?= htmlspecialchars($equipment['name']); ?></td>
                        <td><img src="<?= htmlspecialchars($equipment['photo']); ?>" alt="Equipment Photo" class="equipment-photo"></td>
                        <td><?= htmlspecialchars($equipment['location']); ?></td>
                        <td><?= htmlspecialchars($equipment['contact']); ?></td>
                        <td>₹<?= htmlspecialchars($equipment['price']); ?></td>
                        <td><?= htmlspecialchars($equipment['availability']); ?></td>
                        <td><?= htmlspecialchars($equipment['sports_category']); ?></td>
                        <td><?= htmlspecialchars($equipment['quantity']); ?></td>
                        <td class="actions">
                            <!-- You can add edit or delete actions if necessary -->
                            <a class="accept" href="edit_equipment.php?id=<?= $equipment['id']; ?>">Edit</a>
                            <a class="reject" href="delete_equipment.php?id=<?= $equipment['id']; ?>">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</body>
</html>

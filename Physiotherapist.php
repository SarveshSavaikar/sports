<?php
include 'db_connection.php';

// Fetch all users with the role 'physiotherapist' from the database
$query = "SELECT id, name, email, mobile_number, address, post, cv, photo, dob, prize_charged 
          FROM users WHERE role = 'physiotherapist'";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registered Physiotherapists</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right, #4CAF50, #2E8B57);
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        h1 {
            color: white;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3);
            margin-top: 20px;
        }

        .table-container {
            max-width: 90%;
            margin: 30px auto;
            padding: 20px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            padding: 12px;
            text-align: left;
            font-size: 15px;
        }

        table th {
            background-color: #2E8B57;
            color: white;
            text-transform: uppercase;
            font-size: 14px;
        }

        table td {
            background-color: #f9f9f9;
            border-bottom: 1px solid #ddd;
        }

        .no-data {
            text-align: center;
            color: white;
            font-size: 18px;
        }

        a {
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .profile-img {
            width: 80px;
            height: 80px;
            border-radius: 10px;
            object-fit: cover;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out;
        }

        .profile-img:hover {
            transform: scale(1.1);
        }
    </style>
</head>
<body>
    <h1>Registered Physiotherapists</h1>

    <div class="table-container">
        <?php if ($result && $result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Mobile Number</th>
                        <th>Address</th>
                        <th>Post</th>
                        <th>CV</th>
                        <th>Photo</th>
                        <th>Date of Birth</th>
                        <th>Prize Charged</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($physio = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $physio['id']; ?></td>
                            <td><?= htmlspecialchars($physio['name']); ?></td>
                            <td><?= htmlspecialchars($physio['email']); ?></td>
                            <td><?= htmlspecialchars($physio['mobile_number']); ?></td>
                            <td><?= htmlspecialchars($physio['address']); ?></td>
                            <td><?= htmlspecialchars($physio['post']); ?></td>
                            <td>
                                <?php if (!empty($physio['cv'])): ?>
                                    <a href="<?= htmlspecialchars($physio['cv']); ?>" target="_blank">View CV</a>
                                <?php else: ?>
                                    N/A
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!empty($physio['photo'])): ?>
                                    <img class="profile-img" src="<?= htmlspecialchars($physio['photo']); ?>" alt="Physiotherapist Photo">
                                <?php else: ?>
                                    N/A
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($physio['dob']); ?></td>
                            <td>₹<?= htmlspecialchars($physio['prize_charged']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-data">No physiotherapists registered yet.</p>
        <?php endif; ?>
    </div>
</body>
</html>

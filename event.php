<?php
include('db.php');

$query = "SELECT * FROM events";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Goa Scores - Upcoming Events</h1>
    </header>

    <div class="events-container">
        <h2>Events</h2>
        <table>
            <tr>
                <th>Event Name</th>
                <th>Event Date</th>
                <th>Club</th>
            </tr>
            <?php while ($event = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo $event['event_name']; ?></td>
                <td><?php echo $event['event_date']; ?></td>
                <td><?php echo $event['club_id']; ?></td>
            </tr>
            <?php } ?>
        </table>
    </div>

    <footer>
        <p>&copy; 2025 Goa Scores</p>
    </footer>
</body>
</html>

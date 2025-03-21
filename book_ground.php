<?php
session_start();

// Ensure the user is logged in and is a customer
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'customer') {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user']; // Get customer data from session

// Include DB connection
include 'db_connection.php';

// Fetch available grounds
$grounds_result = $conn->query("SELECT * FROM grounds WHERE availability = 1");

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Get form data

    $ground_id = $_POST['ground_id'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

    // Combine the start date and start time, and end date and end time
    $start_datetime = strtotime($start_date . ' ' . $start_time);
    $end_datetime = strtotime($end_date . ' ' . $end_time);

    function calculate_working_hours($start, $end)
    {
        $working_hours = 0;
        $current_time = $start;

        while ($current_time < $end) {
            $current_date = date('Y-m-d', $current_time);
            $start_working_time = strtotime($current_date . ' 09:00:00');
            $end_working_time = strtotime($current_date . ' 18:00:00');

            // If the booking starts before 9 AM, adjust start time to 9 AM
            if ($current_time < $start_working_time) {
                $current_time = $start_working_time;
            }

            // If the booking starts after 6 PM, skip to the next day 9 AM
            if ($current_time >= $end_working_time) {
                $current_time = strtotime('+1 day', $start_working_time);
                continue;
            }

            // Calculate hours for the current day
            $end_of_day = min($end_working_time, $end);
            $working_hours += ($end_of_day - $current_time) / 3600;

            // Move to the next day
            $current_time = strtotime('+1 day', $start_working_time);
        }

        return $working_hours;
    }

    // TO get the booking_slots data and convert it into a json object
    $booking_slots = [];
    if(isset($_POST['number_of_dates'])){
        $date_start = [$_POST['date1'], $_POST['starttime1']];
    }
    $duration = 0;
    for ($i = 1; $i <= $_POST['number_of_dates']; $i++) {
        $date = $_POST['date' . $i];
        $start_time = $_POST['starttime' . $i];
        $end_time = $_POST['enddate' . $i];
        echo "<script>console.log('$start_time')</script>";
        $booking_slots["$date"] = [$starting_time , $end_time];
        $date_end = [$_POST['date'.$i], $_POST['endtime'.$i]];
        $duration += calculate_working_hours(strtotime($date . ' ' . $start_time), strtotime($date. ' ' . $end_time));
    }
    $JSON = json_encode($booking_slots);
    // $duration = calculate_working_hours(strtotime($date_start[0] . ' ' . $date_start[1]), strtotime($date_end[0] . ' ' . $date_end[1]));

    // Calculate the actual working hours
    // $duration = calculate_working_hours($start_datetime, $end_datetime);


    // // Calculate the booking duration in hours (difference between start and end datetime)
    // $duration = ($end_datetime - $start_datetime) / 3600; // Duration in hours

    // Get the price per hour for the selected ground
    $query = "SELECT price FROM grounds WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $ground_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $ground = $result->fetch_assoc();
    $price_per_hour = $ground['price'];

    // Calculate total price
    $total_price = $duration * $price_per_hour;

    // Redirect to confirmation page with details
    header("Location: confirm_booking.php?ground_id=$ground_id&start_date=$date_start[0]&end_date=$date_end[0]&start_time=$date_start[1]&end_time=$date_end[1]&total_time=$duration&total_price=$total_price&JSON=$JSON&number_of_dates=$_POST[number_of_dates]");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Ground</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .day-box {
            border-radius: 20px;
            border-width: 1px;
            border-color: black;
            border-style: solid;
            padding: 20px;
            padding-top: 0px;
            padding-bottom: 0px;
            /* background-color: red; */
            width: 350px;
            margin-top: 20px;
            margin-bottom: 20px;
        }



        .booking-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .date-box input {
            /* margin-top: 20px; */
            width: 110px;
        }

        .timing-box {
            /* margin: 40px; */
            /* width: 20px; */
            height: 10px;

        }

        .timing-box input {
            width: 65px;
        }
    </style>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById('number_of_dates').addEventListener('input', updatenumberofdates);
        });

        function updatePrice() {
            const groundSelect = document.getElementById('ground_id');
            const selectedOption = groundSelect.options[groundSelect.selectedIndex];
            const pricePerHour = selectedOption.getAttribute('data-price');
            document.getElementById('price_per_hour').innerText = pricePerHour ? `₹${pricePerHour}/hour` : 'Not available';
        }

        function updatenumberofdates() {
            let num = document.getElementById('number_of_dates').value;
            let parent = document.getElementById('booking-date-timing');
            parent.innerHTML = '';

            for (let i = 1; i <= num; i++) {
                let date = document.createElement('input');
                date.type = 'date';
                date.name = 'date' + i;
                date.id = 'date' + i;
                date.required = true;
                document.body.appendChild(date);

                parent.insertAdjacentHTML('beforeend', `
                        <div class="day-box">
                        <h3>Day ${i}:-</h3>
                        <div class="booking-row">
                            <div class="date-box">
                                <label for="date${i}">Enter the date </label>
                                <input type="date" name="date${i}" id="date${i}" required>
                            </div>
                            <div class="timing-box">
                                <label for="date${i}">timing </label>
                                <input type="time" name="starttime${i}" id="starttime${i}" required min="09:00" max="18:00">
                                -
                                <input type="time" name="enddate${i}" id="endtime${i}" required min="09:00" max="18:00">
                               
                            </div>
                        </div>
                    </div>`);

            }
            attachTimeValidation();

        }

        function attachTimeValidation() {
    document.querySelectorAll('input[type="time"]').forEach(input => {
        input.addEventListener('input', function () {
            let minTime = "09:00";
            let maxTime = "18:00";

            if (this.value < minTime) {
                this.value = minTime;
            }
            if (this.value > maxTime) {
                this.value = maxTime;
            }
        });
    });
}

document.addEventListener("DOMContentLoaded", function () {
    attachTimeValidation();
});
    </script>
</head><!-- Back Button -->
<button onclick="history.back()">Go Back</button>

<body>
    <header>
        <h1>Welcome, <?= htmlspecialchars($user['name']); ?></h1>
        <p>Here are the available grounds for booking.</p>
        <a href="logout.php">Logout</a>
    </header>

    <div class="container">
        <h2 style="margin-left: 750px">Select Ground and Booking Details</h2>
        <?php if ($grounds_result->num_rows > 0): ?>
            <form method="POST" action="">
                <label for="ground_id">Select Ground:</label>
                <select name="ground_id" id="ground_id" required onchange="updatePrice()">
                    <option value="" disabled selected>Select a ground</option>
                    <?php while ($row = $grounds_result->fetch_assoc()): ?>
                        <option value="<?= $row['id'] ?>" data-price="<?= $row['price'] ?>">
                            <?= htmlspecialchars($row['name']) ?> - ₹<?= htmlspecialchars($row['price']) ?>/hour
                        </option>
                    <?php endwhile; ?>
                </select>
                <p id="price_per_hour">Select a ground to view the price</p>
                <br><br>

                <label for="number_of_dates">Enter the Number of days you booking the Ground :- </label>
                <input type="number" name="number_of_dates" id="number_of_dates" required value="1" oninput="updatenumberofdates()"><br><br>

                <div id="booking-date-timing">
                    <div class="day-box">
                        <h3>Day 1:-</h3>
                        <div class="booking-row">
                            <div class="date-box">
                                <label for="date1">Enter the date </label>
                                <input type="date" name="date1" id="date1" required>
                            </div>
                            <div class="timing-box">
                                <label for="date1">timing </label>
                                <input type="time" name="starttime1" id="starttime1" required min="09:00" max="18:00">
                                -
                                <input type="time" name="enddate1" id="endtime1" required min="09:00" max="18:00">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- <label for="start_date">Start Date:</label>
                <input type="date" name="start_date" id="start_date" required><br><br>

                <label for="end_date">End Date:</label>
                <input type="date" name="end_date" id="end_date" required><br><br>

                <label for="start_time">Start Time:</label>
                <input type="time" name="start_time" id="start_time" required><br><br>

                <label for="end_time">End Time:</label> 
                <input type="time" name="end_time" id="end_time" required><br><br> -->

                <h5>Working hours :- 9am to 6pm</h2>
                    <br>

                    <input type="submit" value="Calculate Price and Book" class="button">
            </form>
        <?php else: ?>
            <p class="no-grounds">No available grounds for booking at the moment.</p>
        <?php endif; ?>
    </div>
</body>

</html>
<?php
session_start();

if (!isset($_SESSION['username'])) {
    echo "<script>alert('You must be logged in to make a reservation.'); window.location.href='Login.php';</script>";
    exit();
}

$username = $_SESSION['username'];

$conn = new mysqli('localhost', 'root', '', 'bella_vita_italian_restaurant');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql_customer = "SELECT customer_id, customer_name FROM customers WHERE username = '$username'";
$result_customer = $conn->query($sql_customer);

if ($result_customer->num_rows > 0) {
    $row_customer = $result_customer->fetch_assoc();
    $customer_id = $row_customer['customer_id'];
    $customer_name = $row_customer['customer_name'];
} else {
    echo "Error: Customer not found.";
    exit();
}

$sql_points = "SELECT loyalty_points FROM customers WHERE customer_id = '$customer_id'";
$result_points = $conn->query($sql_points);
$row_points = $result_points->fetch_assoc();
$loyalty_points = $row_points['loyalty_points'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation</title>
    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="stylesheet" href="assets/css/responsive.css" />
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&family=Playfair+Display&display=swap" rel="stylesheet">
    <script>
        function validateForm() {
            const numGuests = document.getElementById('number_of_guests').value;
            if (numGuests < 1 || numGuests > 20) {
                alert("Number of guests must be between 1 and 20.");
                return false;
            }

            const reservationDate = document.getElementById('reservation_date').value;
            const today = new Date().toISOString().split('T')[0];

            if (reservationDate < today) {
                alert("Reservation date cannot be in the past.");
                return false;
            }

            return true;
        }

        function updateTableImage() {
            var tableSelect = document.getElementById("table_type");
            var tableImage = document.getElementById("table_image");
            var selectedOption = tableSelect.options[tableSelect.selectedIndex];
            tableImage.src = selectedOption.getAttribute("data-image");
        }

        function updateRoomImage() {
            var roomSelect = document.getElementById("room_type");
            var roomImage = document.getElementById("room_image");
            var selectedOption = roomSelect.options[roomSelect.selectedIndex];
            roomImage.src = selectedOption.getAttribute("data-image");
        }
    </script>
</head>

<body>
    <header>
        <div class="nav-container">
            <div class="logo">
                <a href="Home.php"><img src="Bella Vita Italian Restaurant Logo.png" width="50px"></a>
            </div>
            <nav>
                <ul class="nav-links">
                    <li><a href="Home.php#about">About Us</a></li>
                    <li><a href="Home.php#menu">Our Menu</a></li>
                    <li><a href="Home.php#tables">Tables</a></li>
                    <li><a href="Home.php#rooms">Rooms</a></li>
                    <li><a href="Home.php#reserve">Reservations</a></li>
                    <li><a href="Home.php#contact">Contact Us</a></li>
                    <li><a href="Register.php">Register</a></li>
                    <li><a href="Login.php">Login</a></li>
                </ul>
                <div class="menu-toggle" onclick="toggleMenu()">&#9776;</div>
            </nav>
        </div>
    </header>

    <h3 style="text-align:center; margin-top: 20px">Your Loyalty Points: <strong><?php echo $loyalty_points; ?></strong></h3>

    <div class="reservation-container">
        <h2>Reservation</h2>
        <form method="POST" action="reservation.php" onsubmit="return validateForm()">
            <div class="form-columns">
                <div class="left-column">
                    <p>
                        <label for="full_name">Name:</label>
                        <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($customer_name); ?>" readonly>
                    </p>
                    <label for="reservation_date">Reservation Date:</label>
                    <input type="date" id="reservation_date" name="reservation_date" required min="<?php echo date('Y-m-d'); ?>">
                    <label for="reservation_time">Reservation Time:</label>
                    <input type="time" id="reservation_time" name="reservation_time" required><br><br>
                    <label for="number_of_guests">Number of Guests:</label>
                    <input type="number" id="number_of_guests" name="number_of_guests" min="1" max="20" required><br><br>
                    <label for="table_type">Table Type:</label>
                    <select id="table_type" name="table_type" required onchange="updateTableImage()">
                        <option value="Standard" data-image="standard-table.jpg">Standard</option>
                        <option value="VIP" data-image="vip-table.jpg">VIP</option>
                        <option value="Outdoor" data-image="outdoor-table.jpg">Outdoor</option>
                    </select>
                    <img id="table_image" src="standard-table.jpg" alt="Table Type" width="300px" style="display:block; margin-top:10px;"><br><br>
                    <label for="room_type">Room Type:</label>
                    <select id="room_type" name="room_type" required onchange="updateRoomImage()">
                        <option value="Chef's Counter" data-image="chefs-counter.jpg">Chef's Counter</option>
                        <option value="Main Dining Area" data-image="main-dining-area.jpg">Main Dining Area</option>
                        <option value="Private Dining Room" data-image="private-dining-room.jpg">Private Dining Room</option>
                        <option value="Terrace Lounge" data-image="terrace-lounge.jpg">Terrace Lounge</option>
                    </select>
                    <img id="room_image" src="chefs-counter.jpg" alt="Room Type" width="300px" style="display:block; margin-top:10px;"><br><br>
                    <br><br>
                </div>

                <div class="right-column">
                    <label for="special_requests">Special Requests:</label>
                    <textarea id="special_requests" name="special_requests" rows="10"></textarea><br><br>

                    <input type="submit" value="Reserve">
                </div>
            </div>
        </form>
    </div>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $reservation_date = $_POST['reservation_date'];
        $reservation_time = $_POST['reservation_time'];
        $number_of_guests = $_POST['number_of_guests'];
        $table_type = $_POST['table_type'];
        $room_type = $_POST['room_type'];
        $special_requests = $_POST['special_requests'];

        $sql_reservation_count = "SELECT COUNT(*) AS total_reservations FROM reservations WHERE customer_id = '$customer_id' AND status != 'canceled'";
        $result_reservation_count = $conn->query($sql_reservation_count);
        $row_reservation_count = $result_reservation_count->fetch_assoc();
        $total_reservations = $row_reservation_count['total_reservations'];

        $is_free_reservation = ($total_reservations % 10 == 9) ? 1 : 0;

        $sql_table = "SELECT table_id FROM tables WHERE table_type = '$table_type' AND status = 'available' LIMIT 1";
        $result_table = $conn->query($sql_table);

        if ($result_table->num_rows > 0) {
            $row_table = $result_table->fetch_assoc();
            $table_id = $row_table['table_id'];
        } else {
            echo "<script>alert('No available tables of the selected type.');</script>";
            exit();
        }

        $sql_room = "SELECT room_id FROM rooms WHERE room_type = '$room_type' AND status = 'available' LIMIT 1";
        $result_room = $conn->query($sql_room);

        if ($result_room->num_rows > 0) {
            $row_room = $result_room->fetch_assoc();
            $room_id = $row_room['room_id'];
        } else {
            echo "<script>alert('No available rooms of the selected type.');</script>";
            exit();
        }

        $price = $is_free_reservation ? 0 : 5000;
        $sql_reservation = "INSERT INTO reservations (customer_id, table_id, room_id, reservation_date, reservation_time, number_of_guests, special_requests) 
                            VALUES ('$customer_id', '$table_id', '$room_id', '$reservation_date', '$reservation_time', '$number_of_guests', '$special_requests')";

        if ($conn->query($sql_reservation) === TRUE) {
            $conn->query("UPDATE tables SET status = 'reserved' WHERE table_id = '$table_id'");
            $conn->query("UPDATE rooms SET status = 'reserved' WHERE room_id = '$room_id'");

            if (!$is_free_reservation) {
                $loyalty_points_earned = 10;
                $sql_update_points = "UPDATE customers SET loyalty_points = loyalty_points + $loyalty_points_earned WHERE customer_id = '$customer_id'";
                $conn->query($sql_update_points);
            }

            $result_points = $conn->query("SELECT loyalty_points FROM customers WHERE customer_id = '$customer_id'");
            $row_points = $result_points->fetch_assoc();
            $updated_loyalty_points = $row_points['loyalty_points'];

            $popup_message = "Reservation successful!";

            if ($is_free_reservation) {
                $popup_message = "Congratulations! This reservation is FREE as a reward for completing 10 reservations.";
            } else {
                if ($updated_loyalty_points >= 200) {
                    $popup_message = "Reservation successful! You have earned a FREE DRINK with your next meal!";
                } else {
                    $popup_message = "Reservation successful!";
                }
            }

            echo "<script>alert('$popup_message'); window.location.href='Home.php';</script>";
        } else {
            echo "<script>alert('Error: " . $conn->error . "');</script>";
        }
    }


    $conn->close();
    ?>

    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h4>Contact Information</h4>
                <p>123 Albert Place<br>Dehiwala, Colombo<br>Tel: +94 76 013 9886 <br>Email: info@bellavita.com</p>
            </div>
            <div class="footer-section">
                <h4>Opening Hours</h4>
                <p>Mon-Fri: 11am - 11pm<br>Sat: 10am - 12am<br>Sun: 12pm - 10pm</p>
            </div>
            <div class="footer-section">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="Home.php#about">About Us</a></li>
                    <li><a href="Home.php#menu">Our Menu</a></li>
                    <li><a href="Home.php#tables">Tables</a></li>
                    <li><a href="Home.php#rooms">Rooms</a></li>
                    <li><a href="Home.php#reserve">Reservations</a></li>
                    <li><a href="Home.php#contact">Contact Us</a></li>
                </ul>
            </div>
        </div>
        <div class="copyright">
            <p>Copyright &copy; 2022 Bella Vita Italian Restaurant. All rights reserved.</p>
        </div>
    </footer>
</body>

</html>
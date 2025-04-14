<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'bella_vita_italian_restaurant');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_reservation'])) {
    $customer_id = $_POST['customer_id'];
    $table_id = $_POST['table_id'];
    $room_id = $_POST['room_id'];
    $date = $_POST['reservation_date'];
    $time = $_POST['reservation_time'];
    $guests = $_POST['number_of_guests'];
    $requests = $_POST['special_requests'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("INSERT INTO reservations (customer_id, table_id, room_id, reservation_date, reservation_time, number_of_guests, special_requests, status) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iiississ", $customer_id, $table_id, $room_id, $date, $time, $guests, $requests, $status);

    if ($stmt->execute()) {
        echo "<script>alert('Reservation added successfully!');</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_reservation'])) {
    $reservation_id = $_POST['reservation_id'];
    $customer_id = $_POST['customer_id'];
    $table_id = $_POST['table_id'];
    $room_id = $_POST['room_id'];
    $date = $_POST['reservation_date'];
    $time = $_POST['reservation_time'];
    $guests = $_POST['number_of_guests'];
    $requests = $_POST['special_requests'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE reservations SET customer_id=?, table_id=?, room_id=?, reservation_date=?, reservation_time=?, number_of_guests=?, 
    special_requests=?, status=? WHERE reservation_id=?");
    $stmt->bind_param("iiississi", $customer_id, $table_id, $room_id, $date, $time, $guests, $requests, $status, $reservation_id);

    if ($stmt->execute()) {
        echo "<script>alert('Reservation modified successfully!');</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_reservation'])) {
    $reservation_id = $_POST['reservation_id'];

    $stmt = $conn->prepare("DELETE FROM reservations WHERE reservation_id = ?");
    $stmt->bind_param("i", $reservation_id);

    if ($stmt->execute()) {
        echo "<script>alert('Reservation cancelled successfully!');</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}

$reservations = $conn->query("
    SELECT 
        r.reservation_id,
        r.customer_id,
        c.customer_name,
        r.table_id,
        t.table_type,
        r.room_id,
        rm.room_type,
        r.reservation_date,
        r.reservation_time,
        r.number_of_guests,
        r.special_requests,
        r.status
    FROM 
        reservations r
    LEFT JOIN 
        customers c ON r.customer_id = c.customer_id
    LEFT JOIN 
        tables t ON r.table_id = t.table_id
    LEFT JOIN 
        rooms rm ON r.room_id = rm.room_id
");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Reservations</title>
    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="stylesheet" href="assets/css/responsive.css" />
    <link rel="stylesheet" href="assets/css/manage.css" />
    <script src="assets/js/script.js" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&family=Playfair+Display&display=swap"
        rel="stylesheet">
</head>

<body>
    <header>
        <div class="nav-container">
            <div class="logo">
                <a href="Admin Dashboard.php"><img src="Bella Vita Italian Restaurant Logo.png" width="50px"></a>
            </div>
            <nav>
                <ul class="nav-links">
                    <li><a href="Admin Dashboard.php">Dashboard</a></li>
                    <li><a href="Manage Users.php">Manage Users</a></li>
                    <li><a href="Manage Menu.php">Manage Menu</a></li>
                    <li><a href="Manage Reservations.php">Manage Reservations</a></li>
                    <li><a href="Manage Tables.php">Manage Tables</a></li>
                    <li><a href="Customer Queries.php">Customer Queries</a></li>
                    <li><a href="Logout.php">Logout</a></li>
                </ul>
                <div class="menu-toggle" onclick="toggleMenu()">&#9776;</div>
            </nav>
        </div>
    </header>

    <div class="Manage">
        <h2 class="Manage">Manage Reservations</h2>
    </div>

    <div class="filter-container">
        <input type="text" id="searchInput" placeholder="Search by name, email, phone, table type, or room type" onkeyup="filterTable()">
        <select id="statusFilter" onchange="filterTable()">
            <option value="">All Statuses</option>
            <option value="Pending">Pending</option>
            <option value="Confirmed">Confirmed</option>
            <option value="Cancelled">Cancelled</option>
        </select>
    </div>

    <table>
        <tr>
            <th>Reservation ID</th>
            <th>Customer ID</th>
            <th>Customer Name</th>
            <th>Table ID</th>
            <th>Table Type</th>
            <th>Room ID</th>
            <th>Room Type</th>
            <th>Reservation Date</th>
            <th>Reservation Time</th>
            <th>Number of Guests</th>
            <th>Special Requests</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $reservations->fetch_assoc()): ?>
            <tr>
                <td><?= $row['reservation_id'] ?></td>
                <td><?= htmlspecialchars($row['customer_id']) ?></td>
                <td><?= htmlspecialchars($row['customer_name']) ?></td>
                <td><?= htmlspecialchars($row['table_id']) ?></td>
                <td><?= htmlspecialchars($row['table_type']) ?></td>
                <td><?= htmlspecialchars($row['room_id']) ?></td>
                <td><?= htmlspecialchars($row['room_type']) ?></td>
                <td><?= htmlspecialchars($row['reservation_date']) ?></td>
                <td><?= htmlspecialchars($row['reservation_time']) ?></td>
                <td><?= htmlspecialchars($row['number_of_guests']) ?></td>
                <td><?= htmlspecialchars($row['special_requests']) ?></td>
                <td><?= htmlspecialchars($row['status']) ?></td>
                <td style="display: flex;">
                    <button style="margin: 0 5px;" onclick="editReservation(
                        '<?= $row['reservation_id'] ?>', 
                        '<?= htmlspecialchars($row['customer_id']) ?>', 
                        '<?= htmlspecialchars($row['table_id']) ?>', 
                        '<?= htmlspecialchars($row['room_id']) ?>', 
                        '<?= htmlspecialchars($row['reservation_date']) ?>', 
                        '<?= htmlspecialchars($row['reservation_time']) ?>', 
                        '<?= htmlspecialchars($row['number_of_guests']) ?>', 
                        '<?= htmlspecialchars($row['special_requests']) ?>', 
                        '<?= htmlspecialchars($row['status']) ?>'
                    )">Modify</button>
                    <form method="post" action="" style="display:inline;">
                        <input type="hidden" name="reservation_id" value="<?= $row['reservation_id'] ?>">
                        <button type="submit" name="delete_reservation" onclick="return confirm('Are you sure you want to cancel this reservation?')">Cancel</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <button id="addReservationButton" onclick="document.getElementById('addReservationForm').style.display='block'">Add New Reservation</button>

    <div id="addReservationForm" style="display:none;">
        <button id="closeAddForm" onclick="closeForm('addReservationForm')">X</button>
        <h2>Add Reservation</h2>
        <form method="post" action="">
            <input type="hidden" name="add_reservation" value="1">
            <label>Customer:</label>
            <select name="customer_id" required>
                <?php
                $customers = $conn->query("SELECT customer_id, customer_name FROM customers");
                while ($customer = $customers->fetch_assoc()):
                ?>
                    <option value="<?= $customer['customer_id'] ?>"><?= htmlspecialchars($customer['customer_name']) ?></option>
                <?php endwhile; ?>
            </select>
            <label>Table:</label>
            <select name="table_id">
                <option value="">None</option>
                <?php
                $tables = $conn->query("SELECT table_id, table_type FROM tables");
                while ($table = $tables->fetch_assoc()):
                ?>
                    <option value="<?= $table['table_id'] ?>"><?= htmlspecialchars($table['table_type']) ?></option>
                <?php endwhile; ?>
            </select>
            <label>Room:</label>
            <select name="room_id">
                <option value="">None</option>
                <?php
                $rooms = $conn->query("SELECT room_id, room_type FROM rooms");
                while ($room = $rooms->fetch_assoc()):
                ?>
                    <option value="<?= $room['room_id'] ?>"><?= htmlspecialchars($room['room_type']) ?></option>
                <?php endwhile; ?>
            </select>
            <label>Date:</label>
            <input type="date" name="reservation_date" required>
            <label>Time:</label>
            <input type="time" name="reservation_time" required>
            <label>Guests:</label>
            <input type="number" name="number_of_guests" required>
            <label>Special Requests:</label>
            <textarea name="special_requests"></textarea>
            <label>Status:</label>
            <select name="status">
                <option value="Pending">Pending</option>
                <option value="Confirmed">Confirmed</option>
                <option value="Cancelled">Cancelled</option>
            </select>
            <button type="submit">Add Reservation</button>
        </form>
    </div>

    <div id="editReservationForm" style="display: none;">
        <button id="closeEditForm" onclick="closeForm('editReservationForm')">X</button>
        <h2>Edit Reservation</h2>
        <form id="editReservation" method="post" action="">
            <input type="hidden" name="edit_reservation" value="1">
            <input type="hidden" id="reservation_id" name="reservation_id">
            <label>Customer:</label>
            <select id="customer_id" name="customer_id" required>
                <?php
                $customers = $conn->query("SELECT customer_id, customer_name FROM customers");
                while ($customer = $customers->fetch_assoc()):
                ?>
                    <option value="<?= $customer['customer_id'] ?>"><?= htmlspecialchars($customer['customer_name']) ?></option>
                <?php endwhile; ?>
            </select>
            <label>Table:</label>
            <select id="table_id" name="table_id">
                <option value="">None</option>
                <?php
                $tables = $conn->query("SELECT table_id, table_type FROM tables");
                while ($table = $tables->fetch_assoc()):
                ?>
                    <option value="<?= $table['table_id'] ?>"><?= htmlspecialchars($table['table_type']) ?></option>
                <?php endwhile; ?>
            </select>
            <label>Room:</label>
            <select id="room_id" name="room_id">
                <option value="">None</option>
                <?php
                $rooms = $conn->query("SELECT room_id, room_type FROM rooms");
                while ($room = $rooms->fetch_assoc()):
                ?>
                    <option value="<?= $room['room_id'] ?>"><?= htmlspecialchars($room['room_type']) ?></option>
                <?php endwhile; ?>
            </select>
            <label>Reservation Date:</label>
            <input type="date" id="reservation_date" name="reservation_date" required>
            <label>Reservation Time:</label>
            <input type="time" id="reservation_time" name="reservation_time" required>
            <label>Number of Guests:</label>
            <input type="number" id="number_of_guests" name="number_of_guests" required>
            <label>Special Requests:</label>
            <textarea id="special_requests" name="special_requests"></textarea>
            <label>Status:</label>
            <select id="status" name="status" required>
                <option value="Pending">Pending</option>
                <option value="Confirmed">Confirmed</option>
                <option value="Cancelled">Cancelled</option>
            </select>
            <button type="submit">Modify</button>
        </form>
    </div>

    <script>
        function editReservation(id, customerId, tableId, roomId, date, time, guests, requests, status) {
            document.getElementById('reservation_id').value = id;
            document.getElementById('customer_id').value = customerId;
            document.getElementById('table_id').value = tableId;
            document.getElementById('room_id').value = roomId;
            document.getElementById('reservation_date').value = date;
            document.getElementById('reservation_time').value = time;
            document.getElementById('number_of_guests').value = guests;
            document.getElementById('special_requests').value = requests;
            document.getElementById('status').value = status;

            document.getElementById('editReservationForm').style.display = 'block';
        }

        function filterTable() {
            let searchInput = document.getElementById("searchInput").value.toLowerCase();
            let statusFilter = document.getElementById("statusFilter").value.toLowerCase();
            let table = document.querySelector("table");
            let rows = table.getElementsByTagName("tr");

            for (let i = 1; i < rows.length; i++) {
                let cells = rows[i].getElementsByTagName("td");
                if (cells.length > 0) {
                    let customerName = cells[2].textContent.toLowerCase();
                    let tableType = cells[4].textContent.toLowerCase();
                    let roomType = cells[6].textContent.toLowerCase();
                    let status = cells[11].textContent.toLowerCase();

                    let matchesSearch = customerName.includes(searchInput) ||
                        tableType.includes(searchInput) ||
                        roomType.includes(searchInput);
                    let matchesStatus = statusFilter === "" || status.includes(statusFilter);

                    if (matchesSearch && matchesStatus) {
                        rows[i].style.display = "";
                    } else {
                        rows[i].style.display = "none";
                    }
                }
            }
        }
    </script>

    <footer>
        <div class="copyright">
            <p>Copyright &copy; 2022 Bella Vita Italian Restaurant. All rights reserved.</p>
    </footer>
</body>

</html>
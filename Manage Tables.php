<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'bella_vita_italian_restaurant');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $table_id = $_POST['table_id'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE tables SET status=? WHERE table_id=?");
    $stmt->bind_param("si", $status, $table_id);

    if ($stmt->execute()) {
        echo "<script>alert('Status updated successfully!');</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}

$tables = $conn->query("SELECT * FROM tables");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Tables</title>
    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="stylesheet" href="assets/css/responsive.css" />
    <link rel="stylesheet" href="assets/css/manage.css" />
    <script src="assets/js/script.js" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&family=Playfair+Display&display=swap" rel="stylesheet">
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
        <h2 class="Manage">Manage Tables</h2>
    </div>

    <div class="filter-container">
        <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Search by Table Number">

        <select id="tableTypeFilter" onchange="filterTable()">
            <option value="">All Table Types</option>
            <option value="Standard">Standard</option>
            <option value="VIP">VIP</option>
            <option value="Outdoor">Outdoor</option>
        </select>

        <select id="statusFilter" onchange="filterTable()">
            <option value="">All Status</option>
            <option value="Available">Available</option>
            <option value="Occupied">Occupied</option>
            <option value="Reserved">Reserved</option>
        </select>
    </div>

    <table>
        <tr>
            <th>Table ID</th>
            <th>Table Number</th>
            <th>Table Type</th>
            <th>Capacity</th>
            <th>Status</th>
        </tr>
        <?php while ($row = $tables->fetch_assoc()): ?>
            <tr>
                <td><?= $row['table_id'] ?></td>
                <td><?= htmlspecialchars($row['table_number']) ?></td>
                <td><?= htmlspecialchars($row['table_type']) ?></td>
                <td><?= htmlspecialchars($row['capacity']) ?></td>
                <td>
                    <form method="post" action="" style="display:inline;">
                        <input type="hidden" name="table_id" value="<?= $row['table_id'] ?>">
                        <select name="status" onchange="this.form.submit()">
                            <option value="Available" <?= $row['status'] === 'Available' ? 'selected' : '' ?>>Available</option>
                            <option value="Occupied" <?= $row['status'] === 'Occupied' ? 'selected' : '' ?>>Occupied</option>
                            <option value="Reserved" <?= $row['status'] === 'Reserved' ? 'selected' : '' ?>>Reserved</option>
                        </select>
                        <input type="hidden" name="update_status" value="1">
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("searchInput").addEventListener("keyup", filterTable);
            document.getElementById("tableTypeFilter").addEventListener("change", filterTable);
            document.getElementById("statusFilter").addEventListener("change", filterTable);
        });

        function filterTable() {
            let searchInput = document.getElementById("searchInput").value.toLowerCase();
            let tableTypeFilter = document.getElementById("tableTypeFilter").value.toLowerCase();
            let statusFilter = document.getElementById("statusFilter").value.toLowerCase();
            let table = document.querySelector("table");
            let rows = table.getElementsByTagName("tr");

            for (let i = 1; i < rows.length; i++) {
                let cells = rows[i].getElementsByTagName("td");

                if (cells.length > 0) {
                    let tableNumber = cells[1].textContent.toLowerCase();
                    let tableType = cells[2].textContent.toLowerCase();

                    let statusSelect = cells[4].getElementsByTagName("select")[0];
                    let status = statusSelect ? statusSelect.value.toLowerCase() : "";

                    let matchesSearch = tableNumber.includes(searchInput);
                    let matchesTableType = tableTypeFilter === "" || tableType.includes(tableTypeFilter);
                    let matchesStatus = statusFilter === "" || status.includes(statusFilter);

                    if (matchesSearch && matchesTableType && matchesStatus) {
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
        </div>
    </footer>
</body>

</html>
<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'bella_vita_italian_restaurant');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['username'])) {
    echo "<script>alert('You need to log in to view your queries.'); window.location.href='Login.php';</script>";
    exit();
}

$username = $_SESSION['username'];

$queries = $conn->query("
    SELECT cm.* 
    FROM contact_messages cm
    JOIN customers c ON cm.email = c.email
    WHERE c.username = '$username'
");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Queries</title>
    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="stylesheet" href="assets/css/responsive.css" />
    <link rel="stylesheet" href="assets/css/manage.css" />
    <script src="assets/js/script.js" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&family=Playfair+Display&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Open+Sans:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <header class="dashboard-header">
        <div class="nav-container">
            <div class="logo">
                <a href="Customer Dashboard.php">
                    <img src="Bella Vita Italian Restaurant Logo.png" alt="Bella Vita Logo" width="60">
                </a>
            </div>
            <nav>
                <ul class="nav-links">
                    <li><a href="Customer Dashboard.php" class="active"><i class="fas fa-home"></i> Dashboard</a></li>
                    <li><a href="Manage Profile.php"><i class="fas fa-user-edit"></i> Manage Profile</a></li>
                    <li><a href="Manage My Reservations.php"><i class="fas fa-calendar-alt"></i>Manage Reservations</a></li>
                    <li><a href="My Queries.php"><i class="fas fa-comments"></i>My Queries</a></li>
                    <li><a href="Logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
                <div class="menu-toggle" onclick="toggleMenu()">&#9776;</div>
            </nav>
        </div>
    </header>

    <div class="Manage">
        <h2 class="Manage">My Queries</h2>
    </div>

    <div class="filter-container">
        <input type="text" id="searchInput" onkeyup="filterQueries()" placeholder="Search by Message or Response">

        <select id="statusFilter" onchange="filterQueries()">
            <option value="">All Status</option>
            <option value="Pending">Pending</option>
            <option value="Resolved">Resolved</option>
        </select>
    </div>

    <table>
        <tr>
            <th>ID</th>
            <th>Message</th>
            <th>Response</th>
            <th>Status</th>
            <th>Created At</th>
        </tr>
        <?php while ($row = $queries->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['message']) ?></td>
                <td><?= !empty($row['response']) ? htmlspecialchars($row['response']) : 'Not responded yet' ?></td>
                <td><?= htmlspecialchars($row['status']) ?></td>
                <td><?= htmlspecialchars($row['created_at']) ?></td>
            </tr>
        <?php endwhile; ?>
    </table>

    <script>
        function filterQueries() {
            let searchInput = document.getElementById("searchInput").value.toLowerCase();
            let statusFilter = document.getElementById("statusFilter").value.toLowerCase();
            let table = document.querySelector("table");
            let rows = table.getElementsByTagName("tr");

            for (let i = 1; i < rows.length; i++) {
                let cells = rows[i].getElementsByTagName("td");
                if (cells.length > 0) {
                    let message = cells[1].textContent.toLowerCase();
                    let response = cells[2].textContent.toLowerCase();
                    let status = cells[3].textContent.toLowerCase();

                    let matchesSearch = message.includes(searchInput) || response.includes(searchInput);
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

    <button id="addMenuButton" onclick="window.location.href='Home.php#contact'">Send A Query</button>

    <footer>
        <div class="copyright">
            <p>Copyright &copy; 2022 Bella Vita Italian Restaurant. All rights reserved.</p>
        </div>
    </footer>
</body>

</html>
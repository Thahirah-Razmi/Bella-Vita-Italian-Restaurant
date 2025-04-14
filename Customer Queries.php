<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'bella_vita_italian_restaurant');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['respond_to_query'])) {
    $id = $_POST['id'];
    $response = htmlspecialchars(trim($_POST['response']));

    $status = !empty($response) ? "Resolved" : "Pending";

    $stmt = $conn->prepare("UPDATE contact_messages SET response=?, status=? WHERE id=?");
    $stmt->bind_param("ssi", $response, $status, $id);

    if ($stmt->execute()) {
        echo "<script>alert('Response submitted successfully!'); window.location.href='Customer Queries.php';</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}

$queries = $conn->query("SELECT * FROM contact_messages");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Queries</title>
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
        <h2 class="Manage">Customer Queries</h2>
    </div>

    <div class="filter-container">
        <input type="text" id="searchInput" placeholder="Search by Name, Email, or Phone..." onkeyup="filterTable()">
        <select id="statusFilter" onchange="filterTable()">
            <option value="">All Status</option>
            <option value="Pending">Pending</option>
            <option value="Resolved">Resolved</option>
        </select>
    </div>

    <table>
        <tr>
            <th>Query ID</th>
            <th>Customer Name</th>
            <th>Email</th>
            <th>Phone Number</th>
            <th>Message</th>
            <th>Response</th>
            <th>Status</th>
            <th>Created At</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $queries->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['phone_number']) ?></td>
                <td><?= htmlspecialchars($row['message']) ?></td>
                <td><?= !empty($row['response']) ? htmlspecialchars($row['response']) : 'N/A' ?></td>
                <td><?= htmlspecialchars($row['status']) ?></td>
                <td><?= htmlspecialchars($row['created_at']) ?></td>
                <td>
                    <button onclick="openResponseForm(
                        '<?= $row['id'] ?>', 
                        '<?= $row['response'] !== null ? htmlspecialchars($row['response']) : '' ?>', 
                        '<?= htmlspecialchars($row['status']) ?>'
                    )">Respond</button>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <div id="responseForm" style="display:none;">
        <button id="closeResponseForm" onclick="closeForm('responseForm')">X</button>
        <h2>Respond to Query</h2>
        <form method="post" action="">
            <input type="hidden" name="respond_to_query" value="1">
            <input type="hidden" name="id" id="queryId">
            <label>Response:</label>
            <textarea id="response" name="response" required></textarea>
            <label>Status:</label>
            <select name="status" id="status" required>
                <option value="Pending">Pending</option>
                <option value="Resolved">Resolved</option>
            </select>
            <button type="submit">Submit Response</button>
        </form>
    </div>

    <script>
        function openResponseForm(id, response, status) {
            document.getElementById("queryId").value = id;
            document.getElementById("response").value = response || "";
            document.getElementById("status").value = status;
            document.getElementById("responseForm").style.display = "block";
        }

        function closeForm(formId) {
            document.getElementById(formId).style.display = "none";
        }

        function filterTable() {
            let searchInput = document.getElementById("searchInput").value.toLowerCase();
            let statusFilter = document.getElementById("statusFilter").value;

            let table = document.getElementsByTagName("table")[0];
            let rows = table.getElementsByTagName("tr");

            for (let i = 1; i < rows.length; i++) { 
                let cols = rows[i].getElementsByTagName("td");
                if (cols.length > 0) {
                    let name = cols[1].textContent.toLowerCase();
                    let email = cols[2].textContent.toLowerCase();
                    let phone = cols[3].textContent.toLowerCase();
                    let status = cols[6].textContent.trim();

                    let matchesSearch = name.includes(searchInput) || email.includes(searchInput) || phone.includes(searchInput);
                    let matchesStatus = statusFilter === "" || status === statusFilter;

                    rows[i].style.display = (matchesSearch && matchesStatus) ? "" : "none";
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
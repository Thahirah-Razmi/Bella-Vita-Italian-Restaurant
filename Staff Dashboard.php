<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard</title>
    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="stylesheet" href="assets/css/responsive.css" />
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&family=Playfair+Display&display=swap"
        rel="stylesheet">
</head>

<body>
    <header>
        <div class="nav-container">
            <div class="logo">
                <a href="Staff Dashboard.php"><img src="Bella Vita Italian Restaurant Logo.png" width="50px"></a>
            </div>
            <nav>
                <ul class="nav-links">
                    <li><a href="Staff Dashboard.php">Dashboard</a></li>
                    <li><a href="Manage Reservation.php">Manage Reservations</a></li>
                    <li><a href="Manage Table.php">Manage Tables</a></li>
                    <li><a href="Customer Query.php">Customer Queries</a></li>
                    <li><a href="Logout.php">Logout</a></li>
                </ul>
                <div class="menu-toggle" onclick="toggleMenu()">&#9776;</div>
            </nav>
    </header>

    <main class="Admin">
        <h1>Staff Dashboard</h1>
        <div class="role-options">
            <div class="role-card">
                <h2>Manage Reservations</h2>
                <p>Manage reservations, add new reservations, modify and cancel reservations.</p>
                <a href="Manage Reservation.php" class="btn">Manage Reservations</a>
            </div>

            <div class="role-card">
                <h2>Manage Tables</h2>
                <p>Manage tables by changing the table availability.</p>
                <a href="Manage Table.php" class="btn">Manage Tables</a>
            </div>

            <div class="role-card">
                <h2>Customer Queries</h2>
                <p>Respond to customer inquiries.</p>
                <a href="Customer Query.php" class="btn">View Queries</a>
            </div>
        </div>
    </main>

    <footer>
        <div class="copyright">
            <p>Copyright &copy; 2022 Bella Vita Italian Restaurant. All rights reserved.</p>
    </footer>
</body>

</html>
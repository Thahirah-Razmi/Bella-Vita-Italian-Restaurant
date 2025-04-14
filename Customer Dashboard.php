<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: Login.php");
    exit();
}

$username = $_SESSION['username'];
$conn = new mysqli('localhost', 'root', '', 'bella_vita_italian_restaurant');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT customer_id, customer_name, email, phone, loyalty_points FROM customers WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$customer = $result->fetch_assoc();
$stmt->close();

$customer_id = $customer['customer_id'];

$reservation_sql = "SELECT reservation_date FROM reservations WHERE customer_id = ? ORDER BY reservation_date DESC LIMIT 1";
$stmt = $conn->prepare($reservation_sql);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$reservation_result = $stmt->get_result();
$recent_reservation = $reservation_result->fetch_assoc();
$stmt->close();

$query_sql = "SELECT created_at FROM contact_messages WHERE email = ? ORDER BY created_at DESC LIMIT 1";
$stmt = $conn->prepare($query_sql);
$stmt->bind_param("s", $customer['email']);
$stmt->execute();
$query_result = $stmt->get_result();
$recent_query = $query_result->fetch_assoc();
$stmt->close();

$reservations_sql = "SELECT reservation_id, reservation_date, reservation_time, table_id, room_id, status 
                     FROM reservations WHERE customer_id = ? AND reservation_date >= CURDATE() ORDER BY reservation_date ASC";
$stmt = $conn->prepare($reservations_sql);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$reservations_result = $stmt->get_result();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/manage.css" />
    <link rel="stylesheet" href="assets/css/responsive.css">
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

    <main class="customer-dashboard">
        <h1 class="dashboard-title">Customer Dashboard</h1>

        <section class="profile-showcase">
            <div class="profile-header">
                <div class="profile-avatar">
                    <img src="default-avatar.png" alt="User Avatar" class="avatar-img">
                    <button class="avatar-edit-btn"><i class="fas fa-camera"></i></button>
                </div>
                <div class="profile-info">
                    <h2 class="profile-name"><?php echo htmlspecialchars($customer['customer_name']); ?></h2>
                    <div class="profile-stats">
                        <div class="stat-item"><i class="fas fa-heart"></i> <span>Loyalty Points:
                                <?php echo htmlspecialchars($customer['loyalty_points']); ?></span></div>
                    </div>
                </div>
            </div>

            <div class="profile-details-grid">
                <div class="detail-card">
                    <h3><i class="fas fa-user-circle"></i> Personal Information</h3>
                    <ul class="detail-list">
                        <li><strong>Email:</strong> <?php echo htmlspecialchars($customer['email']); ?></li>
                        <li><strong>Phone:</strong> <?php echo htmlspecialchars($customer['phone']); ?></li>
                    </ul>
                    <form action="Manage Profile.php" method="GET">
                        <button type="submit" class="dashboard-btn"><i class="fas fa-edit"></i> Update Information</button>
                    </form>
                </div>

                <div class="detail-card">
                    <h3><i class="fas fa-history"></i> Recent Activity</h3>
                    <ul class="activity-log">
                        <li><i class="fas fa-calendar-check"></i> Reservation made for <span class="activity-time">
                                <?php echo $recent_reservation ? $recent_reservation['reservation_date'] : 'N/A'; ?></span></li>
                        <li><i class="fas fa-comment"></i> New query submitted <span class="activity-time">
                                <?php echo $recent_query ? $recent_query['created_at'] : 'N/A'; ?></span></li>
                    </ul>
                </div>
            </div>
        </section>

        <div class="dashboard-grid">
            <div class="dashboard-column">
                <div class="dashboard-role-grid">
                    <div class="dashboard-role-card">
                        <h2><i class="fas fa-user-cog"></i> Manage Profile</h2>
                        <p>View and update your personal details</p>
                        <a href="Manage Profile.php" class="dashboard-btn">
                            <i class="fas fa-arrow-right"></i> Manage Profile
                        </a>
                    </div>

                    <div class="dashboard-role-card">
                        <h2><i class="fas fa-calendar-check"></i> Manage Reservations</h2>
                        <p>Manage reservations, add new reservations, modify and cancel reservations.</p>
                        <a href="Manage My Reservations.php" class="dashboard-btn">
                            <i class="fas fa-arrow-right"></i> Manage My Reservations
                        </a>
                    </div>

                    <div class="dashboard-role-card">
                        <h2><i class="fas fa-comments"></i> My Queries</h2>
                        <p>Send inquiries and get responses from staff.</p>
                        <a href="My Queries.php" class="dashboard-btn">
                            <i class="fas fa-arrow-right"></i> My Queries
                        </a>
                    </div>
                </div>
            </div>

            <div class="dashboard-column">
                <section class="dashboard-reservations">
                    <h2 class="dashboard-subtitle"><i class="fas fa-calendar-day"></i> Upcoming Reservations</h2>

                    <div class="reservation-list">
                        <?php while ($reservation = $reservations_result->fetch_assoc()) { ?>
                            <div class="reservation-card">
                                <div class="reservation-header">
                                    <span class="reservation-date"><?php echo $reservation['reservation_date']; ?></span>
                                    <span class="reservation-time"><?php echo $reservation['reservation_time']; ?></span>
                                </div>
                                <div class="reservation-body">
                                    <h3 class="reservation-title">
                                        <?php echo $reservation['table_id'] ? 'Table ' . $reservation['table_id'] : 'Room ' . $reservation['room_id']; ?>
                                    </h3>
                                    <div class="reservation-actions">
                                        <form action="Manage My Reservations.php" method="POST">
                                            <input type="hidden" name="reservation_id" value="<?php echo $reservation['reservation_id']; ?>">
                                            <button type="submit" name="edit" class="dashboard-btn edit-btn"><i class="fas fa-edit"></i> Modify</button>
                                            <button type="submit" name="cancel" class="dashboard-btn cancel-btn"><i class="fas fa-times"></i> Cancel</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </section>
    </main>

    <footer>
        <div class="copyright">
            <p>Copyright &copy; 2022 Bella Vita Italian Restaurant. All rights reserved.</p>
    </footer>

    <script src="assets/js/script.js"></script>
</body>

</html>

<?php $conn->close(); ?>
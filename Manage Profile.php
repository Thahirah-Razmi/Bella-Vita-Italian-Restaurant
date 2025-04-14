<?php
session_start();

$conn = new mysqli('localhost', 'root', '', 'bella_vita_italian_restaurant');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    die("User not logged in.");
}

$sql = "SELECT customer_name, email, username, phone, birthday, loyalty_points, password FROM customers WHERE user_id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Error preparing query: " . $conn->error);
}
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$customer = $result->fetch_assoc();
$current_password = $customer['password'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $phone = $_POST['phone'];
    $birthday = $_POST['birthday'];
    $password = $_POST['password'];

    if (!empty($password)) {
        $update_customers_sql = "UPDATE customers SET customer_name = ?, email = ?, username = ?, phone = ?, birthday = ?, password = ? WHERE user_id = ?";
        $update_users_sql = "UPDATE users SET name = ?, email = ?, username = ?, phone = ?, password = ? WHERE user_id = ?";
        $update_customers_stmt = $conn->prepare($update_customers_sql);
        $update_users_stmt = $conn->prepare($update_users_sql);
        $update_customers_stmt->bind_param("ssssssi", $name, $email, $username, $phone, $birthday, $password, $user_id);
        $update_users_stmt->bind_param("sssssi", $name, $email, $username, $phone, $password, $user_id);
    } else {
        $update_customers_sql = "UPDATE customers SET customer_name = ?, email = ?, username = ?, phone = ?, birthday = ? WHERE user_id = ?";
        $update_users_sql = "UPDATE users SET name = ?, email = ?, username = ?, phone = ? WHERE user_id = ?";
        $update_customers_stmt = $conn->prepare($update_customers_sql);
        $update_users_stmt = $conn->prepare($update_users_sql);
        $update_customers_stmt->bind_param("sssssi", $name, $email, $username, $phone, $birthday, $user_id);
        $update_users_stmt->bind_param("ssssi", $name, $email, $username, $phone, $user_id);
    }

    if ($update_customers_stmt->execute() && $update_users_stmt->execute()) {
        echo "<script>alert('Profile updated successfully!'); window.location.href='Manage Profile.php';</script>";
    } else {
        echo "<p>Error updating profile: " . $conn->error . "</p>";
    }

    $update_customers_stmt->close();
    $update_users_stmt->close();
}
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Profile</title>
    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="stylesheet" href="assets/css/responsive.css" />
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

    <div class="manage-profile-section">
        <h1>Manage Your Profile</h1>
        <form action="" method="POST">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($customer['customer_name']) ?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($customer['email']) ?>" required>

            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?= htmlspecialchars($customer['username']) ?>" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Leave blank to keep current password">

            <label for="phone">Phone:</label>
            <input type="tel" id="phone" name="phone" value="<?= htmlspecialchars($customer['phone']) ?>" required>

            <label for="birthday">Birthday:</label>
            <input type="date" id="birthday" name="birthday" value="<?= htmlspecialchars($customer['birthday']) ?>" required>

            <label for="loyalty_points">Loyalty Points:</label>
            <input type="text" id="loyalty_points" name="loyalty_points" value="<?= htmlspecialchars($customer['loyalty_points']) ?>" readonly>

            <button type="submit">Update Profile</button>
        </form>
    </div>

    <footer>
        <div class="copyright">
            <p>Copyright &copy; 2025 Bella Vita Italian Restaurant. All rights reserved.</p>
        </div>
    </footer>
</body>

</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="stylesheet" href="assets/css/responsive.css" />
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&family=Playfair+Display&display=swap"
        rel="stylesheet">
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
    </header>

    <div class="login-container">
        <h2>Login</h2>
        <form method="POST" action="">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required><br><br>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br><br>

            <input type="submit" value="Login">
        </form>
    </div>

    <?php
    session_start();

    $conn = new mysqli('localhost', 'root', '', 'bella_vita_italian_restaurant');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $sql = "SELECT * FROM users WHERE username = '$username'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            if ($password === $row['password']) {
                $_SESSION['user_id'] = $row['user_id'];
                $_SESSION['role'] = $row['role'];
                $_SESSION['username'] = $row['username'];

                if ($row['role'] == 'Admin') {
                    header("Location: Admin Dashboard.php");
                } elseif ($row['role'] == 'Customer') {
                    header("Location: Customer Dashboard.php");
                } elseif ($row['role'] == 'Staff') {
                    header("Location: Staff Dashboard.php");
                }
                exit();
            } else {
                echo "<script>alert('Invalid password!');</script>";
            }
        } else {
            echo "<script>alert('User not found!');</script>";
        }
    }

    $conn->close();
    ?>

    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h4>Contact Informationn</h4>
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
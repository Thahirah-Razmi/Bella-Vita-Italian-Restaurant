<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
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

    <div class="register-container">
        <h1>Register</h1>
        <form method="POST" action="" onsubmit="return validateForm()">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required><br><br>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required><br><br>
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required><br><br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br><br>
            <label for="phone">Phone:</label>
            <input type="text" id="phone" name="phone" required><br><br>
            <label for="role">Role:</label>
            <select id="role" name="role" required>
                <option value="Admin">Admin</option>
                <option value="Customer">Customer</option>
                <option value="Staff">Staff</option>
            </select><br><br>
            <div id="customerFields" style="display: none;">
                <label for="birthday">Birthday:</label>
                <input type="date" id="birthday" name="birthday"><br><br>
            </div>
            <div id="staffFields" style="display: none;">
                <label for="shift">Shift:</label>
                <input type="text" id="shift" name="shift"><br><br>
            </div>
            <input type="submit" value="Register">
        </form>
    </div>

    <script>
        document.getElementById('role').addEventListener('change', function() {
            var role = this.value;
            if (role === 'Customer') {
                document.getElementById('customerFields').style.display = 'block';
                document.getElementById('staffFields').style.display = 'none';
            } else if (role === 'Staff') {
                document.getElementById('staffFields').style.display = 'block';
                document.getElementById('customerFields').style.display = 'none';
            } else {
                document.getElementById('customerFields').style.display = 'none';
                document.getElementById('staffFields').style.display = 'none';
            }
        });

        function validateForm() {
            var name = document.getElementById('name').value;
            var email = document.getElementById('email').value;
            var username = document.getElementById('username').value;
            var password = document.getElementById('password').value;
            var phone = document.getElementById('phone').value;
            var role = document.getElementById('role').value;
            var birthday = document.getElementById('birthday').value;
            var shift = document.getElementById('shift').value;

            if (!name || !email || !username || !password || !phone || !role) {
                alert("Please fill in all required fields.");
                return false;
            }

            if (role === 'Customer' && !birthday) {
                alert("Please enter your birthday.");
                return false;
            }

            if (role === 'Staff' && !shift) {
                alert("Please enter your shift.");
                return false;
            }

            var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
            if (!emailPattern.test(email)) {
                alert("Please enter a valid email address.");
                return false;
            }

            var phonePattern = /^\(?\d{3}\)?[-.\s]?\d{3}[-.\s]?\d{4}$/;
            if (!phonePattern.test(phone)) {
                alert("Please enter a valid phone number.");
                return false;
            }

            var passwordPattern = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,}$/;
            if (!passwordPattern.test(password)) {
                alert("Password must be at least 8 characters long, contain at least one letter, one number, and one special character.");
                return false;
            }

            return true;
        }
    </script>

    <?php
    $conn = new mysqli('localhost', 'root', '', 'bella_vita_italian_restaurant');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $name = $_POST['name'];
        $email = $_POST['email'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $phone = $_POST['phone'];
        $role = $_POST['role'];

        $sql_users = "INSERT INTO users (name, email, username, password, phone, role) 
                  VALUES ('$name', '$email', '$username', '$password', '$phone', '$role')";

        if ($conn->query($sql_users) === TRUE) {
            $user_id = $conn->insert_id;

            if ($role == 'Admin') {
                $sql_role = "INSERT INTO admin (user_id, admin_name, email, username, password, phone) 
                         VALUES ('$user_id', '$name', '$email', '$username', '$password', '$phone')";
            } elseif ($role == 'Customer') {
                $birthday = $_POST['birthday'];
                $sql_role = "INSERT INTO customers (user_id, customer_name, email, username, password, phone, birthday) 
                         VALUES ('$user_id', '$name', '$email', '$username', '$password', '$phone', '$birthday')";
            } elseif ($role == 'Staff') {
                $shift = $_POST['shift'];
                $sql_role = "INSERT INTO staff (user_id, staff_name, email, username, password, phone, shift) 
                         VALUES ('$user_id', '$name', '$email', '$username', '$password', '$phone', '$shift')";
            }
            if ($conn->query($sql_role) === TRUE) {
                echo "<script>
                        alert('Registration successful!');
                        window.location.href = 'Login.php';
                      </script>";
            } else {
                echo "<script>
                        alert('Error inserting into role-specific table: " . $conn->error . "');
                      </script>";
            }
        } else {
            echo "<script>
                    alert('Error inserting into users table: " . $conn->error . "');
                  </script>";
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
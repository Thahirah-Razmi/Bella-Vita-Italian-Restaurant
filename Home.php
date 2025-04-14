<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Home</title>
    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="stylesheet" href="assets/css/responsive.css" />
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&family=Playfair+Display&display=swap"
        rel="stylesheet">
    <script defer src="assets/js/script.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">

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

    <section class="hero">
        <div class="hero-content">
            <h1>Authentic Italian Dining Experience<br>Since 2022</h1>
            <p>Welcome to Bella Vita, where every meal is a celebration of authentic Italian flavors.
                Since 2022, we've been serving hand-crafted dishes made with the finest ingredients,
                bringing the warmth and tradition of Italy to your table. Join us for an unforgettable dining experience.</p>
            <a href="Reservation.php" class="btn hero-btn">Reserve Now</a>
        </div>
    </section>

    <section id="about">
        <h1>ABOUT US</h1>
        <div class="about-content">
            <p><strong>At Bella Vita, we believe that great food brings people together.
                    Since opening our doors in 2022, we have been dedicated to crafting authentic
                    Italian dishes inspired by tradition and made with passion. From handmade pasta to wood-fired pizzas,
                    every bite is a taste of Italy. Our warm and inviting atmosphere, paired with exceptional hospitality,
                    ensures that every visit is a memorable experience. Whether you're celebrating a special occasion or
                    simply indulging in a delicious meal, Bella Vita is your home for true Italian dining. Buon appetito!</strong></p>
        </div>
    </section>

    <?php
$conn = new mysqli('localhost', 'root', '', 'bella_vita_italian_restaurant');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT dish_name, description, category, price, image_path FROM menu";
$result = $conn->query($sql);

$menuItems = [
    'Starters' => [],
    'Mains' => [],
    'Desserts' => [],
    'Beverages' => []
];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $category = $row['category'];
        $menuItems[$category][] = $row;
    }
} else {
    echo "No items found in the menu.";
}

$conn->close();
?>

    <section id="menu">
        <h2>Our Menu</h2>
        <div class="menu-buttons">
            <button onclick="showMenu('Starters')">Starters</button>
            <button onclick="showMenu('Mains')">Mains</button>
            <button onclick="showMenu('Desserts')">Desserts</button>
            <button onclick="showMenu('Beverages')">Beverages</button>
        </div>
        <div id="menu-items" class="menu-grid"></div>
    </section>

    <script>
        const menuData = <?php echo json_encode($menuItems); ?>;

        function showMenu(category) {
            const menuContainer = document.getElementById("menu-items");
            menuContainer.innerHTML = menuData[category]
                .map(item => `
                            <div class="menu-item">
                                <img src="${item.image_path}" alt="${item.dish_name}">
                                <div>
                                    <h3>${item.dish_name}</h3>
                                    <p>${item.description}</p>
                                    <p><strong>Price:</strong> LKR ${item.price}</p>
                                </div>
                            </div>
                        `)
                .join("");
        }

        showMenu('Starters');
    </script>

    <section id="tables">
        <h2>Tables</h2>
        <div class="table-container">
            <div class="table-card">
                <div class="card-image">
                    <img src="standard-table.jpg" alt="Standard Table">
                </div>
                <div class="card-content">
                    <h3>Standard</h3>
                    <ul>
                        <li>Comfortable seating for 4 to 6 guests</li>
                        <li>Perfect for casual dining and family meals</li>
                        <li>Warm and inviting ambiance</li>
                    </ul>
                </div>
            </div>
            <div class="table-card">
                <div class="card-image">
                    <img src="outdoor-table.jpg" alt="Outdoor Table">
                </div>
                <div class="card-content">
                    <h3>Outdoor</h3>
                    <ul>
                        <li>Relaxing open-air dining with beautiful views</li>
                        <li>Shaded seating available for comfort</li>
                        <li>Perfect for enjoying fresh air and seasonal weather</li>
                    </ul>
                </div>
            </div>
            <div class="table-card vip-card">
                <div class="card-image">
                    <img src="vip-table.jpg" alt="VIP Table">
                </div>
                <div class="card-content">
                    <h3>VIP</h3>
                    <ul>
                        <li>Exclusive seating with enhanced privacy</li>
                        <li>Luxury experience with personalized service</li>
                        <li>Ideal for special occasions and business meetings</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section id="rooms">
        <h2>Rooms</h2>
        <div class="room-container">
            <div class="room-card">
                <div class="card-image">
                    <img src="chefs-counter.jpg" alt="Chef's Counter">
                </div>
                <div class="card-content">
                    <h3>Chef's Counter</h3>
                    <ul>
                        <li>Exclusive front-row seats to watch our chefs in action</li>
                        <li>Perfect for food enthusiasts and special dining experiences</li>
                        <li>Limited seating for an intimate atmosphere</li>
                    </ul>
                </div>
            </div>
            <div class="room-card">
                <div class="card-image">
                    <img src="main-dining-area.jpg" alt="Main Dining Area">
                </div>
                <div class="card-content">
                    <h3>Main Dining Area</h3>
                    <ul>
                        <li>Spacious seating with a lively atmosphere</li>
                        <li>Perfect for families, friends, and group gatherings</li>
                        <li>Designed with authentic Italian decor</li>
                    </ul>
                </div>
            </div>
            <div class="room-card">
                <div class="card-image">
                    <img src="private-dining-room.jpg" alt="Private Dining Room">
                </div>
                <div class="card-content">
                    <h3>Private Dining Room</h3>
                    <ul>
                        <li>Exclusive space for intimate gatherings and events</li>
                        <li>Personalized service with a dedicated waitstaff</li>
                        <li>Ideal for celebrations, business meetings, or romantic dinners</li>
                    </ul>
                </div>
            </div>
            <div class="room-card">
                <div class="card-image">
                    <img src="terrace-lounge.jpg" alt="Terrace Lounge">
                </div>
                <div class="card-content">
                    <h3>Terrace Lounge</h3>
                    <ul>
                        <li>Elegant open-air seating with a stunning city view</li>
                        <li>Ideal for romantic dinners, casual meetups, or private events</li>
                        <li>Relaxing ambiance with soft lighting and comfortable seating</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section id="reserve">
        <div class="reserve-container">
            <div class="reserve-image">
                <img src="reserve-image.jpg" alt="Reserve Table Image">
            </div>
            <div class="reserve-content">
                <h2>Reserve Online</h2>
                <p>Reserve a table easily with our online booking platform.</p>
                <p>The cost of making a reservation is LKR 5000.</p>
                <h3>Dress Code</h3>
                <p>Starting at 6:00 PM, our restaurant requires a SMART ELEGANT dress code.</p>
                <p>Short pants, slippers, flip-flops, and open-toe shoes are not permitted.</p>
                <a href="Reservation.php" class="btn reserve-btn">Reserve Now</a>
            </div>
        </div>
    </section>

    <?php
    $conn = new mysqli('localhost', 'root', '', 'bella_vita_italian_restaurant');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT review_id, customer_id, review_text, rating FROM reviews";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $reviews = [];
        while ($row = $result->fetch_assoc()) {
            $reviews[] = $row;
        }
    } else {
        $reviews = [];
    }

    $conn->close();
    ?>

    <section id="reviews">
        <h2>REVIEWS</h2>
        <div class="reviews-container">
            <?php
            if (!empty($reviews)) {
                foreach ($reviews as $review) {
                    $rating = str_repeat('⭐', $review['rating']);
                    $reviewText = htmlspecialchars($review['review_text']);
                    $customerName = "Customer {$review['customer_id']}";

                    echo "
                    <div class='review-card'>
                        <div class='review-header'>
                            <span class='reviewer-name'>{$customerName}</span>
                            <div class='review-rating'>{$rating}</div>
                        </div>
                        <p class='review-text'>{$reviewText}</p>
                    </div>
                ";
                }
            } else {
                echo "<p>No reviews available.</p>";
            }
            ?>
        </div>
    </section>

    <section id="contact">
        <h2>CONTACT US</h2>
        <div class="contact-container">
            <div class="contact-info">
                <div class="contact-item">
                    <h3>Location</h3>
                    <p>123 Albert Place<br>Dehiwala, Colombo</p>
                </div>
                <div class="contact-item">
                    <h3>Email</h3>
                    <p>info@bellavita.com</p>
                </div>
                <div class="contact-item">
                    <h3>Phone</h3>
                    <p>+94 76 013 9886</p>
                </div>

                <div class="contact-item social-media">
                    <h3>Follow Us</h3>
                    <div class="social-links">
                        <a href="https://wa.me/yourwhatsappnumber" target="_blank" class="social-icon">
                            <img src="whatsapp.png" alt="WhatsApp" width="40px"></a>
                        <a href="https://www.instagram.com/yourinstaaccount" target="_blank" class="social-icon">
                            <img src="instagram.png" alt="Instagram" width="40px"></a>
                        <a href="https://www.facebook.com/yourfacebookaccount" target="_blank" class="social-icon">
                            <img src="facebook.png" alt="Facebook" width="30px"></a>
                        <a href="https://twitter.com/yourtwitteraccount" target="_blank" class="social-icon">
                            <img src="twitter.png" alt="Twitter" width="30px"></a>
                    </div>
                </div>
            </div>

            <div class="contact-form">
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" placeholder="Enter your name" required />
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" placeholder="Enter your email" required />
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone:</label>
                        <input type="tel" id="phone" name="phone" placeholder="Enter your phone" required />
                    </div>
                    <div class="form-group">
                        <label for="message">Message:</label>
                        <textarea id="message" name="message" placeholder="Enter your message" required></textarea>
                    </div>
                    <button type="submit" class="btn">Submit</button>
                </form>
            </div>
        </div>
    </section>

    <?php
    $conn = new mysqli('localhost', 'root', '', 'bella_vita_italian_restaurant');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = htmlspecialchars(trim($_POST['name']));
        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        $phone = htmlspecialchars(trim($_POST['phone']));
        $message = htmlspecialchars(trim($_POST['message']));

        if (empty($name) || empty($email) || empty($phone) || empty($message)) {
            echo "<script>alert('All fields are required!');</script>";
        } else {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo "<script>alert('Invalid email format!');</script>";
            } elseif (!preg_match("/^[0-9]{10,15}$/", $phone)) {
                echo "<script>alert('Invalid phone number!');</script>";
            } else {
                $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, phone_number, message, status) VALUES (?, ?, ?, ?, 'Pending')");
                $stmt->bind_param("ssss", $name, $email, $phone, $message);

                if ($stmt->execute()) {
                    echo "<script>alert('Thank you for contacting us! We will get back to you soon.');</script>";
                } else {
                    echo "<script>alert('Error: " . $conn->error . "');</script>";
                }
                $stmt->close();
            }
        }
    }
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
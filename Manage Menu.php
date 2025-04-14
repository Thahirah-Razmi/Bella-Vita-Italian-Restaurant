<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'bella_vita_italian_restaurant');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_menu'])) {
    $dish_name = $_POST['dish_name'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $availability = $_POST['availability'];

    $image_path = '';
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "menu/";
        $image_path = $target_dir . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
    }

    $stmt = $conn->prepare("INSERT INTO menu (dish_name, description, category, price, availability, image_path) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssdis", $dish_name, $description, $category, $price, $availability, $image_path);

    if ($stmt->execute()) {
        echo "<script>alert('Menu item added successfully!');</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_menu'])) {
    $menu_id = $_POST['menu_id'];
    $dish_name = $_POST['dish_name'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $availability = $_POST['availability'];

    $stmt = $conn->prepare("SELECT image_path FROM menu WHERE menu_id = ?");
    $stmt->bind_param("i", $menu_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $image_path = $row['image_path'];

    if (!empty($_FILES['image']['name'])) {
        $target_dir = "uploads/";
        $image_path = $target_dir . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
    }

    $stmt = $conn->prepare("UPDATE menu SET dish_name=?, description=?, category=?, price=?, availability=?, image_path=? WHERE menu_id=?");
    $stmt->bind_param("sssdisi", $dish_name, $description, $category, $price, $availability, $image_path, $menu_id);

    if ($stmt->execute()) {
        echo "<script>alert('Menu item modified successfully!');</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_menu'])) {
    $menu_id = $_POST['menu_id'];

    $stmt = $conn->prepare("DELETE FROM menu WHERE menu_id = ?");
    $stmt->bind_param("i", $menu_id);

    if ($stmt->execute()) {
        echo "<script>alert('Menu item removed successfully!');</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}

$menu_items = $conn->query("SELECT * FROM menu");
?>

<script>
    function filterMenu() {
        var searchQuery = document.getElementById("searchInput").value.toLowerCase();
        var categoryFilter = document.getElementById("categoryFilter").value;
        var availabilityFilter = document.getElementById("availabilityFilter").value;

        var table = document.querySelector("table");
        var rows = table.getElementsByTagName("tr");

        for (var i = 1; i < rows.length; i++) {
            var dishName = rows[i].cells[1].textContent.toLowerCase();
            var description = rows[i].cells[2].textContent.toLowerCase();
            var category = rows[i].cells[3].textContent;
            var availability = rows[i].cells[5].textContent === "Available" ? "1" : "0";

            var matchesSearch = dishName.includes(searchQuery) || description.includes(searchQuery);
            var matchesCategory = categoryFilter === "" || category === categoryFilter;
            var matchesAvailability = availabilityFilter === "" || availability === availabilityFilter;

            if (matchesSearch && matchesCategory && matchesAvailability) {
                rows[i].style.display = "";
            } else {
                rows[i].style.display = "none";
            }
        }
    }
</script>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Menu</title>
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
        <h2 class="Manage">Manage Menu</h2>
    </div>

    <div class="filter-container">
        <input type="text" id="searchInput" onkeyup="filterMenu()" placeholder="Search for dishes...">

        <select id="categoryFilter" onchange="filterMenu()">
            <option value="">All Categories</option>
            <option value="Starters">Starters</option>
            <option value="Mains">Mains</option>
            <option value="Desserts">Desserts</option>
            <option value="Beverages">Beverages</option>
        </select>

        <select id="availabilityFilter" onchange="filterMenu()">
            <option value="">All Availability</option>
            <option value="1">Available</option>
            <option value="0">Not Available</option>
        </select>
    </div>

    <table>
        <tr>
            <th>Menu ID</th>
            <th>Dish Name</th>
            <th>Description</th>
            <th>Category</th>
            <th>Price</th>
            <th>Availability</th>
            <th>Image</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $menu_items->fetch_assoc()): ?>
            <tr>
                <td><?= $row['menu_id'] ?></td>
                <td><?= htmlspecialchars($row['dish_name']) ?></td>
                <td><?= htmlspecialchars($row['description']) ?></td>
                <td><?= htmlspecialchars($row['category']) ?></td>
                <td>LKR <?= number_format($row['price'], 2) ?></td>
                <td><?= $row['availability'] ? 'Available' : 'Not Available' ?></td>
                <td><img src="<?= $row['image_path'] ?>" width="50"></td>
                <td style="display: flex;">
                    <button style="margin: 0 5px;" onclick="editMenu(
                    <?= $row['menu_id'] ?>, 
                    '<?= addslashes(htmlspecialchars($row['dish_name'])) ?>', 
                    '<?= addslashes(htmlspecialchars($row['description'])) ?>', 
                    '<?= addslashes(htmlspecialchars($row['category'])) ?>',
                    '<?= $row['price'] ?>',
                    <?= $row['availability'] ? '1' : '0' ?>,
                    '<?= addslashes(htmlspecialchars($row['image_path'])) ?>'
                    )">Modify</button>
                    <form method="post" action="" style="display:inline;">
                        <input type="hidden" name="menu_id" value="<?= $row['menu_id'] ?>">
                        <button type="submit" name="delete_menu" onclick="return confirm('Remove this menu item?')">Remove</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <button id="addMenuButton" onclick="document.getElementById('addMenuForm').style.display='block'">Add New Menu Item</button>

    <div id="addMenuForm" style="display:none;">
        <button id="closeAddForm" onclick="closeForm('addMenuForm')">X</button>
        <h2>Add New Menu Item</h2>

        <form method="post" action="" enctype="multipart/form-data">
            <input type="hidden" name="add_menu" value="1">
            <label>Dish Name:</label>
            <input type="text" name="dish_name" required>
            <label>Description:</label>
            <textarea name="description" required></textarea>
            <label>Category:</label>
            <select name="category" required>
                <option value="Starters">Starters</option>
                <option value="Mains">Mains</option>
                <option value="Desserts">Desserts</option>
                <option value="Beverages">Beverages</option>
            </select>
            <label>Price:</label>
            <input type="number" name="price" step="0.01" required>
            <label>Availability:</label>
            <select name="availability">
                <option value="1">Available</option>
                <option value="0">Not Available</option>
            </select>
            <label>Image:</label>
            <input type="file" name="image" accept="image/*">
            <button type="submit">Add Item</button>
        </form>
    </div>

    <div id="editMenuForm" style="display:none;">
        <button id="closeEditForm" onclick="closeForm('editMenuForm')">X</button>
        <h2>Modify Item</h2>

        <form method="post" action="" enctype="multipart/form-data">
            <input type="hidden" name="edit_menu" value="1">
            <input type="hidden" name="menu_id" id="editMenuId" value="">
            <label>Dish Name:</label>
            <input type="text" name="dish_name" id="editDishName" required>
            <label>Description:</label>
            <textarea name="description" id="editDescription" required></textarea>
            <label>Category:</label>
            <select name="category" id="editCategory" required>
                <option value="Starters">Starters</option>
                <option value="Mains">Mains</option>
                <option value="Desserts">Desserts</option>
                <option value="Beverages">Beverages</option>
            </select>
            <label>Price:</label>
            <input type="number" name="price" step="0.01" id="editPrice" required>
            <label>Availability:</label>
            <select name="availability" id="editAvailability">
                <option value="1">Available</option>
                <option value="0">Not Available</option>
            </select>
            <label>Image:</label>
            <input type="file" name="image" id="editImage" accept="image/*">
            <button type="submit">Update Item</button>
        </form>
    </div>

    <script>
        function editMenu(menu_id, dish_name, description, category, price, availability, image_path) {
            document.getElementById("editMenuId").value = menu_id;
            document.getElementById("editDishName").value = dish_name;
            document.getElementById("editDescription").value = description;
            document.getElementById("editCategory").value = category;
            document.getElementById("editPrice").value = parseFloat(price);;
            document.getElementById("editAvailability").value = availability.toString();
            document.getElementById("editImage").src = image_path;
            document.getElementById("editMenuForm").style.display = "block";
        }
    </script>

    <footer>
        <div class="copyright">
            <p>Copyright &copy; 2022 Bella Vita Italian Restaurant. All rights reserved.</p>
        </div>
    </footer>
</body>

</html>
<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'bella_vita_italian_restaurant');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_user'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $phone = $_POST['phone'];
    $role = $_POST['role'];

    $stmt = $conn->prepare("INSERT INTO users (name, email, username, password, phone, role) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $name, $email, $username, $password, $phone, $role);

    if ($stmt->execute()) {
        $user_id = $stmt->insert_id;

        if ($role === 'Customer') {
            $birthday = $_POST['birthday'];
            $stmt = $conn->prepare("INSERT INTO customers (user_id, customer_name, email, username, password, phone, birthday, loyalty_points) 
            VALUES (?, ?, ?, ?, ?, ?, ?, 0)");
            $stmt->bind_param("issssss", $user_id, $name, $email, $username, $password, $phone, $birthday);
        } elseif ($role === 'Admin') {
            $stmt = $conn->prepare("INSERT INTO admins (user_id, admin_name, email, username, password, phone) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("isssss", $user_id, $name, $email, $username, $password, $phone);
        } elseif ($role === 'Staff') {
            $shift = $_POST['shift'];
            $stmt = $conn->prepare("INSERT INTO staff (user_id, staff_name, email, username, password, phone, shift) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("issssss", $user_id, $name, $email, $username, $password, $phone, $shift);
        }

        if ($stmt->execute()) {
            echo "<script>alert('User added successfully!');</script>";
        } else {
            echo "<script>alert('Error adding role-specific data: " . $conn->error . "');</script>";
        }
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_user'])) {
    $user_id = $_POST['user_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $phone = $_POST['phone'];
    $role = $_POST['role'];

    $stmt = $conn->prepare("UPDATE users SET name=?, email=?, username=?, password=?, phone=?, role=? WHERE user_id=?");
    $stmt->bind_param("ssssssi", $name, $email, $username, $password, $phone, $role, $user_id);

    if ($stmt->execute()) {
        if ($role === 'Customer') {
            $birthday = $_POST['birthday'];
            $stmt = $conn->prepare("UPDATE customers SET customer_name=?, email=?, username=?, password=?, phone=?, birthday=? WHERE user_id=?");
            $stmt->bind_param("ssssssi", $name, $email, $username, $password, $phone, $birthday, $user_id);
        } elseif ($role === 'Admin') {
            $stmt = $conn->prepare("UPDATE admins SET admin_name=?, email=?, username=?, password=?, phone=? WHERE user_id=?");
            $stmt->bind_param("sssssi", $name, $email, $username, $password, $phone, $user_id);
        } elseif ($role === 'Staff') {
            $shift = $_POST['shift'];
            $stmt = $conn->prepare("UPDATE staff SET staff_name=?, email=?, username=?, password=?, phone=?, shift=? WHERE user_id=?");
            $stmt->bind_param("ssssssi", $name, $email, $username, $password, $phone, $shift, $user_id);
        }

        if ($stmt->execute()) {
            echo "<script>alert('User updated successfully!');</script>";
        } else {
            echo "<script>alert('Error updating role-specific data: " . $conn->error . "');</script>";
        }
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user'])) {
    $user_id = $_POST['user_id'];

    $stmt = $conn->prepare("SELECT role FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && $user['role'] === 'Admin') {
        echo "<script>alert('Cannot delete an Admin user!');</script>";
    } else {
        if ($user['role'] === 'Customer') {
            $stmt = $conn->prepare("DELETE FROM customers WHERE user_id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
        } elseif ($user['role'] === 'Staff') {
            $stmt = $conn->prepare("DELETE FROM staff WHERE user_id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
        }

        $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        if ($stmt->execute()) {
            echo "<script>alert('User deleted successfully!');</script>";
        } else {
            echo "<script>alert('Error: " . $conn->error . "');</script>";
        }
    }
}

$users = $conn->query("SELECT * FROM users");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="stylesheet" href="assets/css/responsive.css" />
    <link rel="stylesheet" href="assets/css/manage.css" />
    <script src="assets/js/script.js" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&family=Playfair+Display&display=swap"
        rel="stylesheet">
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
    </header>

    <div class="Manage">
        <h2 class="Manage">Manage Users</h2>
    </div>

    <div class="filter-container">
        <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Search by Name or Email">

        <select id="roleFilter" onchange="filterTable()">
            <option value="">All Roles</option>
            <option value="Admin">Admin</option>
            <option value="Staff">Staff</option>
            <option value="Customer">Customer</option>
        </select>
    </div>

    <table>
        <tr>
            <th>User ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Username</th>
            <th>Password</th>
            <th>Phone</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $users->fetch_assoc()): ?>
            <tr>
                <td><?= $row['user_id'] ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['username']) ?></td>
                <td><?= htmlspecialchars($row['password']) ?></td>
                <td><?= htmlspecialchars($row['phone']) ?></td>
                <td><?= htmlspecialchars($row['role']) ?></td>
                <td>
                    <button onclick="editUser(
                        '<?= $row['user_id'] ?>', 
                        '<?= htmlspecialchars($row['name']) ?>', 
                        '<?= htmlspecialchars($row['email']) ?>', 
                        '<?= htmlspecialchars($row['username']) ?>', 
                        '<?= htmlspecialchars($row['password']) ?>',
                        '<?= htmlspecialchars($row['phone']) ?>', 
                        '<?= htmlspecialchars($row['role']) ?>'
                    )">Edit</button>
                    <form method="post" action="" style="display:inline;">
                        <input type="hidden" name="user_id" value="<?= $row['user_id'] ?>">
                        <button type="submit" name="delete_user" onclick="return confirm('Are you sure you want to delete this user?')">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <button id="addUserButton" onclick="document.getElementById('addUserForm').style.display='block'">Add New User</button>

    <div id="addUserForm" style="display:none;">
        <button id="closeAddForm" onclick="closeForm('addUserForm')">X</button>
        <h2>Add New User</h2>

        <form method="post" action="">
            <input type="hidden" name="add_user" value="1">
            <label>Name:</label>
            <input type="text" name="name" required>
            <label>Email:</label>
            <input type="email" name="email" required>
            <label>Username:</label>
            <input type="text" name="username" required>
            <label>Password:</label>
            <input type="password" name="password" required>
            <label>Phone:</label>
            <input type="text" name="phone" required>
            <label>Role:</label>
            <select name="role" id="addRole" required onchange="toggleRoleFields('add')">
                <option value="Admin">Admin</option>
                <option value="Staff">Staff</option>
                <option value="Customer">Customer</option>
            </select>
            <div id="customerFields_add" style="display:none;">
                <label>Birthday:</label>
                <input type="date" name="birthday">
            </div>
            <div id="staffFields_add" style="display:none;">
                <label>Shift:</label>
                <input type="text" name="shift">
            </div>

            <button type="submit">Add User</button>
        </form>
    </div>

    <div id="editUserForm" style="display:none;">
        <button id="closeEditForm" onclick="closeForm('editUserForm')">X</button>
        <h2>Edit User</h2>

        <form method="post" action="">
            <input type="hidden" name="edit_user" value="1">
            <input type="hidden" name="user_id" id="editUserId">
            <label>Name:</label>
            <input type="text" name="name" id="editName" required>
            <label>Email:</label>
            <input type="email" name="email" id="editEmail" required>
            <label>Username:</label>
            <input type="text" name="username" id="editUsername" required>
            <label>Password:</label>
            <input type="password" name="password" id="editPassword" required>
            <label>Phone:</label>
            <input type="text" name="phone" id="editPhone" required>
            <label>Role:</label>
            <select name="role" id="editRole" required onchange="toggleRoleFields('edit')">
                <option value="Admin">Admin</option>
                <option value="Staff">Staff</option>
                <option value="Customer">Customer</option>
            </select>
            <div id="customerFields_edit" style="display:none;">
                <label>Birthday:</label>
                <input type="date" name="birthday" id="editBirthday">
            </div>
            <div id="staffFields_edit" style="display:none;">
                <label>Shift:</label>
                <input type="text" name="shift" id="editShift">
            </div>

            <button type="submit">Update User</button>
        </form>
    </div>

    <script>
        function editUser(id, name, email, username, password, phone, role) {
            document.getElementById("editUserId").value = id;
            document.getElementById("editName").value = name;
            document.getElementById("editEmail").value = email;
            document.getElementById("editUsername").value = username;
            document.getElementById("editPassword").value = password;
            document.getElementById("editPhone").value = phone;
            document.getElementById("editRole").value = role;
            toggleRoleFields('edit');
            document.getElementById("editUserForm").style.display = "block";
        }

        function toggleRoleFields(formType) {
            let role = document.getElementById(formType === 'edit' ? 'editRole' : 'addRole').value;
            document.getElementById('customerFields_' + formType).style.display = role === 'Customer' ? 'block' : 'none';
            document.getElementById('staffFields_' + formType).style.display = role === 'Staff' ? 'block' : 'none';
        }

        function filterTable() {
            let searchInput = document.getElementById("searchInput").value.toLowerCase();
            let roleFilter = document.getElementById("roleFilter").value.toLowerCase();
            let table = document.querySelector("table");
            let rows = table.getElementsByTagName("tr");

            for (let i = 1; i < rows.length; i++) {
                let cells = rows[i].getElementsByTagName("td");
                if (cells.length > 0) {
                    let name = cells[1].textContent.toLowerCase();
                    let email = cells[2].textContent.toLowerCase();
                    let role = cells[6].textContent.toLowerCase();

                    let matchesSearch = name.includes(searchInput) || email.includes(searchInput);
                    let matchesRole = roleFilter === "" || role.includes(roleFilter);

                    if (matchesSearch && matchesRole) {
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
    </footer>
</body>

</html>
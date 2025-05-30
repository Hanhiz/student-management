<?php
session_start();

$servername = "localhost";
$username = "root";
$pass = "";
$dbname = "student";
$mysql = new mysqli($servername, $username, $pass, $dbname);

if ($mysql->connect_error) {
    die("Connection failed: " . $mysql->connect_error);
}

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Fetch login_id using email (if not already set in session)
if (!isset($_SESSION['login_id'])) {
    $email = $_SESSION['email'];
    $stmt = $mysql->prepare("SELECT login_id FROM login WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($login_id);
    if ($stmt->fetch()) {
        $_SESSION['login_id'] = $login_id;
    } else {
        die("Login ID not found for the current user.");
    }
    $stmt->close();
}

$admin_id = $_SESSION['login_id'];

// Logging function
function logAction($conn, $login_id, $action) {
    $stmt = $conn->prepare("INSERT INTO activity_log (login_id, action) VALUES (?, ?)");
    $stmt->bind_param("is", $login_id, $action);
    $stmt->execute();
    $stmt->close();
}

// Fetch all users
$sql = "SELECT * FROM login";
$result = $mysql->query($sql);

// Delete user if requested
if (isset($_GET['delete'])) {
    $id = $mysql->real_escape_string($_GET['delete']);
    $check = $mysql->query("SELECT role FROM login WHERE email = '$id'")->fetch_assoc();

    if ($check && $check['role'] !== 'Admin') {
        // If role is User, delete from student table as well
        if ($check['role'] === 'User') {
            $mysql->query("DELETE FROM student WHERE email = '$id'");
        }
        $mysql->query("DELETE FROM login WHERE email = '$id'");
        $action = "Admin deleted user account: $id";
        logAction($mysql, $admin_id, $action);
        echo "<script>alert('User deleted successfully.'); window.location.href='CRUD.php';</script>";
    } else {
        echo "<script>alert('Admin user cannot be deleted.');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Account Dashboard</title>
    <style>
        html, body {
        background-color: #f0ede6;
        justify-content: center;
        align-items: center;
        margin: 0;
        }
        table {
            border-collapse: collapse;
            width: 90%;
            margin: 30px auto;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
        }
        th {
            background-color:rgb(4, 115, 170);
            color: white;
        }
        tr:nth-child(even) {background-color: #f2f2f2;}
        tr:hover {
            background-color: #ddd;
        }
        h1{
            text-align:center;
            color:rgb(7, 25, 86);
            margin-bottom: 10px;
            padding-top: 2%;
        }
        nav {
            background-color: #7ebddc;
            overflow: hidden;
        }
        nav a {
            float: left;
            display: block;
            color:rgb(5, 27, 55);
            text-align: center;
            padding: 14px 20px;
            text-decoration: none;
        }
        nav a:hover {
            background-image: linear-gradient( #7ebddc, #f0ede6);
            background-color: #7ebddc;
            color: black;
        }
        .active {
            background-color: #f0ede6;
            color: black;
        }
        .create {
            float: right;
            margin-right: 5%;
            background-color: rgb(76, 168, 175);
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            font-family: Arial, sans-serif;
            transition: background-color 0.3s ease;
        }
        .create:hover {
            background-color: rgb(69, 146, 160);
        }
        .logout {
            float: right;
            background-color: #e74c3c; 
            color: white;              
            padding: 13.5px 24px;        
            text-decoration: none;    
            font-size: 16px;
            border-radius: 5px;    
            display: inline-block;
            transition: background-color 0.3s, transform 0.2s ease-in-out; 
        }
        .logout:hover {
            background-color: #c0392b;
            color: red;
            transform: scale(1.05); 
            text-decoration: underline;
        }
        td img{
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
        .content {
        clear: both;
        background-color: white;
        border-radius: 5%;
        justify-content: center;
        align-items: center;
        width: 80%;
        margin-left: 10%;
        }
    </style>
</head>
<body>
    <nav>
        <a href="CRUD.php" class="active">User Accounts</a>
        <a href="Viewchuan.php"> User Information</a>
        <a class="logout" href="logout.php">Logout</a>
    </nav>
    <div class="content">
        <h1>USER ACCOUNTS</h1>
        <a class="create" href="create.php">Create New User</a>
        <br>
        <table>
        <tr>
            <th>Email</th>
            <th>
                Role
            </th>
            <th>Edit</th>
            <th>Delete</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['role']; ?></td>
                <?php if ($row['role'] !== 'Admin'): ?>
                <td><a href="edit.php?email=<?php echo urlencode($row['email']); ?>"><img src='update.png' alt='Update' width=40px height=40px></a></td>
                <td><a href="CRUD.php?delete=<?php echo urlencode($row['email']); ?>" onclick="return confirm('Are you sure you want to delete this user?');"><img src='delete.png' alt='Delete' width=40px height=40px></a></td>
            <?php else: ?>
                <td><center><em>Protected</em></center></td>
                <td><center><em>Protected</em></center></td>
            <?php endif; ?>
            </tr>
        <?php endwhile; ?>
        </table>
        <br>
    </div>
</body>
</html>

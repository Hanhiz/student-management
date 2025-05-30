<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// DB connection
$servername = "localhost";
$username = "root";
$pass = "";
$dbname = "student";
$mysql = new mysqli($servername, $username, $pass, $dbname);
if ($mysql->connect_error) {
    die("Connection failed: " . $mysql->connect_error);
}

// Get login_id from session or DB
if (!isset($_SESSION['login_id'])) {
    $email = $_SESSION['email'];
    $stmt = $mysql->prepare("SELECT login_id FROM login WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($login_id);
    if ($stmt->fetch()) {
        $_SESSION['login_id'] = $login_id;
    } else {
        die("Login ID not found for current session.");
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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_user = $_POST['email'];
    $new_pass = $_POST['password'];
    $role = $_POST['role'];

    // Check if user already exists in login table
    $stmt = $mysql->prepare("SELECT * FROM login WHERE email = ?");
    $stmt->bind_param("s", $new_user);
    $stmt->execute();
    $res_user = $stmt->get_result();
    $stmt->close();

    // Check if email exists in student table
    $stmt_student = $mysql->prepare("SELECT * FROM student WHERE email = ?");
    $stmt_student->bind_param("s", $new_user);
    $stmt_student->execute();
    $res_student = $stmt_student->get_result();
    $stmt_student->close();

    if ($res_user->num_rows > 0) {
        echo "<script>alert('User already exists!');</script>";
    } else {

        // Insert into login table
        $stmt = $mysql->prepare("INSERT INTO login (email, password, role) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $new_user, $new_pass, $role);

        if ($stmt->execute()) {
            $action = "Admin created new user account: $new_user";
            logAction($mysql, $admin_id, $action);
            echo "<script>alert('User created successfully'); window.location.href='CRUD.php';</script>";
        } else {
            echo "<script>alert('Error creating user');</script>";
        }

        $stmt->close();
    }
}

$mysql->close();
?>


<!DOCTYPE html>
<html>
<head>
    <title>Create User</title>
    <style>
    input[type=text], select {
        width: 100%;
        padding: 12px 20px;
        margin: 8px 0;
        display: inline-block;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
        }

    input[type=password], select {
        width: 100%;
        padding: 12px 20px;
        margin: 8px 0;
        display: inline-block;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
        }

    input[list=listrole], select {
        width: 100%;
        padding: 12px 20px;
        margin: 8px 0;
        display: inline-block;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
        }

    input[type=submit] {
        width: 100%;
        background-color:rgb(76, 168, 175);
        color: white;
        padding: 14px 20px;
        margin: 8px 0;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    input[type=submit]:hover {
        background-color:rgb(69, 146, 160);
    }

    form {
        border-radius: 5px;
        background-color: #f2f2f2;
        padding: 20px;
        margin: 0 auto;
        margin-top: 8%;
        width: 35%;
    }
    body{
        background-color: #f0ede6;
      }
    h3{
        text-align:center;
        font-size:20pt;
        color:rgb(7, 25, 86);
    }
    </style>
</head>
<body>
    <form method="POST">
        <h3>Create New User</h3>
        <label>Email</label>
        <input type="text" name="email" placeholder="Email" required>
        
        <label>Password</label>
        <input type="password" name="password" placeholder="Password" required>
        
        <label>Role</label>
        <input list="listrole" name="role" required>
        <datalist id="listrole">
            <option value="Admin">
            <option value="Editor">
        </datalist>
        <input type="submit" value="Create">
    </form>
</body>
</html>

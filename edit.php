<?php
session_start();

// Check if the user is logged in
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

// Ensure login_id is available in session
if (!isset($_SESSION['login_id'])) {
    $email = $_SESSION['email'];
    $stmt = $mysql->prepare("SELECT login_id FROM login WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($login_id);
    if ($stmt->fetch()) {
        $_SESSION['login_id'] = $login_id;
    } else {
        die("Login ID not found for current user.");
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

// Get user data from GET parameters
$email = isset($_GET["email"]) ? $_GET["email"] : "";
$role = isset($_GET["role"]) ? $_GET["role"] : "";

// Handle update
if (isset($_GET['Update'])) {
    if (!empty($email) && !empty($role)) {
        // Get current role before update
        $stmt = $mysql->prepare("SELECT role FROM login WHERE email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($currentRole);
        $stmt->fetch();
        $stmt->close();

        // Update login table
        $sql = "UPDATE login SET role=? WHERE email=?";
        $stmt = $mysql->prepare($sql);
        $stmt->bind_param("ss", $role, $email);

        if ($stmt->execute()) {
            // Handle student table changes
            if ($role === "User" && $currentRole !== "User") {
                // Insert into student if not exists
                $checkStmt = $mysql->prepare("SELECT email FROM student WHERE email=?");
                $checkStmt->bind_param("s", $email);
                $checkStmt->execute();
                $checkStmt->store_result();
                if ($checkStmt->num_rows === 0) {
                    $insertStmt = $mysql->prepare("INSERT INTO student (email) VALUES (?)");
                    $insertStmt->bind_param("s", $email);
                    $insertStmt->execute();
                    $insertStmt->close();
                }
                $checkStmt->close();
            } elseif ($currentRole === "User" && $role !== "User") {
                // Delete from student
                $deleteStmt = $mysql->prepare("DELETE FROM student WHERE email=?");
                $deleteStmt->bind_param("s", $email);
                $deleteStmt->execute();
                $deleteStmt->close();
            }

            $action = "Admin updated user role for: $email";
            logAction($mysql, $admin_id, $action);
            echo "<script>alert('User information updated successfully!'); window.location.href='CRUD.php';</script>";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "<script>alert('All fields are required.');</script>";
    }
}

$mysql->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Information Update</title>
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

        input[type=submit] {
        width: 100%;
        background-color: rgb(76, 168, 175);
        color: white;
        padding: 14px 20px;
        margin: 8px 0;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        }

        input[type=submit]:hover {
        background-color: rgb(69, 146, 160);
        }

        div {
        border-radius: 5px;
        background-color: #f2f2f2;
        padding: 20px;
        width: 40%;
        text-align: left;
        }
        body{
            background-color: #f0ede6;
        }
    </style>
</head>
<body>
    <center> <h1> USER INFORMATION UPDATE</h1> 
    <div>
        <form action="" method="GET">
            <label for="email">User Email</label>
            <input type="text" id="email" name="email" value="<?php echo $_GET["email"]; ?>">
            <label for="role"><br>Role</label>
            <select name="role" id="role">
                <option value="Admin" <?php if (isset($_GET['role']) && $_GET['role'] == 'Admin'); ?>>Admin</option>
                <option value="Editor" <?php if (isset($_GET['role']) && $_GET['role'] == 'Editor'); ?>>Editor</option>
                <option value="User" <?php if (isset($_GET['role']) && $_GET['role'] == 'User'); ?>>User</option>
            </select>
            <input type="submit" value="Update" name="Update" id="Update">
        </form>
    </div>
    </center>
</body>
</html>

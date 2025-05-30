<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$message = "";
$servername = "localhost";
$username = "root";
$pass = "";
$dbname = "student";
$mysql = new mysqli($servername, $username, $pass, $dbname);

// Check connection
if ($mysql->connect_error) {
    die("Connection failed: " . $mysql->connect_error);
}
$department_id = $_GET['department_id'];

if ($department_id) {
    $stmt = $mysql->prepare("SELECT MAX(code) AS max_code FROM course WHERE department_id = ?");
    $stmt->bind_param("i", $department_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $nextCode = ($row['max_code'] !== NULL) ? $row['max_code'] + 1 : 1;
}
// Check if the course code exists in the database
$check_code_stmt = $mysql->prepare("SELECT COUNT(*) AS count FROM course WHERE code = ?");
$check_code_stmt->bind_param("i", $course_code);
$check_code_stmt->execute();
$check_code_result = $check_code_stmt->get_result();
$check_code_row = $check_code_result->fetch_assoc();

if ($check_code_row['count'] > 0) {
    $message = "<p style='color:red;'>Error: Course code already exists.</p>";
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $department_id = $_POST['department_id'];
    $course_name = $mysql->real_escape_string(trim($_POST['name']));
    $course_code = $mysql->real_escape_string(trim($_POST['code']));

    if (!empty($department_id) && !empty($course_name) && !empty($course_code)) {
        $stmt = $mysql->prepare("INSERT INTO course (name, code, department_id) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $course_name, $course_code, $department_id);

        if ($stmt->execute()) {
            $message = "<p style='color:green;'>Course added successfully!</p>";
        } else {
            $message = "<p style='color:red;'>Failed to add course. Please try again later.</p>";
        }
    } else {
        $message = "<p style='color:red;'>All fields are required.</p>";
    }
}

// Get department list for dropdown
$departments = $mysql->query("SELECT department_id, name FROM department");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Course</title>
    <style>
        input[type=text], select, input[type=number] {
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

        form {
            border-radius: 5px;
            background-color: #f2f2f2;
            padding: 20px;
            margin: 0 auto;
            width: 35%;
        }

        body {
            background-color: #f0ede6;
        }

        h3 {
            text-align: center;
            font-size: 20pt;
            color: rgb(7, 25, 86);
        }
    </style>
</head>
<body>
    <h3>Add New Course to Department</h3>
    <?= $message ?>

    <form method="POST">
        <label for="department_id">Department:</label>
        <select name="department_id" required>
            <option value="">-- Select Department --</option>
            <?php while ($row = $departments->fetch_assoc()): ?>
                <option value="<?= $row['department_id'] ?>"><?= htmlspecialchars($row['name']) ?></option>
            <?php endwhile; ?>
        </select>
        <br><br>

        <label for="name">Course Name:</label>
        <input type="text" name="name" required>
        <br><br>

        <label for="code">Course Code:</label>
        <input type="text" name="code" class="form-control" value="<?= $nextCode ?>" required>
        <br><br>

        <input type="submit" value="Add Course">
    </form>
</body>
</html>

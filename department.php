<?php
session_start();
$servername = "localhost";
$username="root";
$pass="";
$dbname="student";

$conn = new mysqli($servername, $username, $pass, $dbname);

// Get current user's role
$user_role = '';
if (isset($_SESSION['email'])) {
    $current_email = $_SESSION['email'];
    $role_stmt = $conn->prepare("SELECT role FROM login WHERE email = ?");
    $role_stmt->bind_param("s", $current_email);
    $role_stmt->execute();
    $role_stmt->bind_result($user_role);
    $role_stmt->fetch();
    $role_stmt->close();
}

// Logout functionality
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}
// Handle Add
if (isset($_POST['add'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $conn->query("INSERT INTO department (id, name) VALUES ('$id', '$name')");
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM department WHERE id=$id");
}

// Handle Edit
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $conn->query("UPDATE department SET name='$name' WHERE id=$id");
}

$editMode = false;
$editData = ['id' => '', 'name' => ''];

if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $res = $conn->query("SELECT * FROM department WHERE id=$id");
    $editData = $res->fetch_assoc();
    $editMode = true;
}

// Get all departments
$result = $conn->query("SELECT * FROM department");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Department Management</title>
</head>
<style>
    html, body {
        background-color: #f0ede6;
        justify-content: center;
        align-items: center;
        margin: 0;
      }
      .content table {
        clear: both;
        font-family: Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        width: 90%;
        margin-top: 18px;
        margin-left: 5%;
      }

      .content td, .content th {
        border: 1px solid #ddd;
        padding: 8px;
      }

      .content tr:nth-child(even) {
        background-color: #f2f2f2;
      }

      .content tr:hover {
        background-color: #ddd;
      }

      .content th {
        padding-top: 12px;
        padding-bottom: 12px;
        text-align: left;
        background-color:rgb(4, 115, 170);
        color: white;
      }
      
      h2{
        color:rgb(7, 25, 86);
        margin-bottom: 15px;
        padding-top: 2%;
        margin-left: 1%;
      }
      h3{
        color:rgb(7, 25, 86);
        padding-top: 2%;
        margin-left: 1%;
      }

      p {
        text-align: right;
        margin-right: 5%;
      }

      .btn {
        margin-left: 81%;
        background-color: rgb(76, 168, 175);
        color: white;
        padding: 12px 24px;
        text-decoration: none;
        border-radius: 5px;
        font-size: 16px;
        font-family: Arial, sans-serif;
        transition: background-color 0.3s ease;
      }

      .btn:hover {
        background-color: rgb(69, 146, 160);
      }

      .btnn {
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

      .btnn:hover {
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
      .content {
        background-color: white;
        border-radius: 5%;
        justify-content: center;
        align-items: center;
        width: 40%;
        margin-left: 5%;
        padding-bottom: 2%;
        float: left;
        margin-bottom: 2%;
      }
      input[type=text] {
        width: 80%;
        padding: 12px 20px;
        margin: 8px 0;
        display: inline-block;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
      }

      select {
        width: 40%;
        padding: 12px 20px;
        margin: 8px 0;
        display: inline-block;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
      }

      button {
        width: 7%;
        background-color: rgb(76, 168, 175);
        color: white;
        padding: 14px 20px;
        margin: 8px 0;
        border: none;
        border-radius: 4px;
        cursor: pointer;
      }

      button:hover {
        background-color: rgb(69, 146, 160);
      }
</style>
<body>
    <nav>
        <?php if ($user_role === 'Admin'): ?>
          <a href="CRUD.php">User Accounts</a>
        <?php endif; ?>
        <a href="Viewchuan.php">User information</a>
        <a href="department.php" class="active">Department information</a>
        <a href="department.php?logout=true" class="btnn">Logout</a>
    </nav>
    <h2>Department</h2>

    <!-- Department Filter Form -->
    <form method="GET" action="department.php" style="margin-left:5%; margin-bottom:20px;">
        <label for="filter_department">Filter by Department:</label>
        <select name="filter_department" id="filter_department">
            <option value="">-- All Departments --</option>
            <?php
            $dept_names = $conn->query("SELECT * FROM department");
            while ($d = $dept_names->fetch_assoc()):
                $selected = (isset($_GET['filter_department']) && $_GET['filter_department'] == $d['name']) ? 'selected' : '';
                echo "<option value='" . htmlspecialchars($d['name']) . "' $selected>" . htmlspecialchars($d['name']) . "</option>";
            endwhile;
            ?>
        </select>
        <button type="submit">Filter</button>
    </form>

    <?php
    // Filtering logic
    $filter = isset($_GET['filter_department']) && $_GET['filter_department'] !== '' ? $_GET['filter_department'] : '';
    if ($filter) {
        $dept_result = $conn->query("SELECT * FROM department WHERE name = '" . $conn->real_escape_string($filter) . "'");
    } else {
        $dept_result = $conn->query("SELECT * FROM department");
    }
    while ($dept = $dept_result->fetch_assoc()):
        echo "<div class='content'>";
        echo "<center><h3>" . htmlspecialchars($dept['name']) . "</h3></center>";

        $dept_id = $dept['department_id'];
        $course_result = $conn->query("SELECT * FROM course WHERE department_id = '$dept_id'");

        $student_result = $conn->query("SELECT COUNT(*) AS student_count FROM student WHERE department_id = '$dept_id'");
        $student = $student_result->fetch_assoc();
        echo "<p>Number of Students: " . htmlspecialchars($student['student_count']) . "</p>";

        if ($course_result->num_rows > 0) {
            echo "<table>";
            echo "<tr><th>Course Name</th><th>Code</th></tr>";
            while ($course = $course_result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($course['name']) . "</td>";
                echo "<td>" . htmlspecialchars($course['code']) . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p><em>No courses in this department.</em></p>";
        }
        echo "</div>";
    endwhile;
    ?>
</body>
</html>
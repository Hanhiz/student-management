<?php
session_start();
// Ensure the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}
$servername = "localhost";
$username = "root";
$pass = "";
$dbname = "student";
$mysql = new mysqli($servername, $username, $pass, $dbname);

// Check connection
if ($mysql->connect_error) {
    die("Connection failed: " . $mysql->connect_error);
}

$email = $_SESSION['email'];

// Logout functionality
    if (isset($_GET['logout'])) {
        session_unset();
        session_destroy();
        header("Location: login.php");
        exit();
    }
    
// Get student_id and department_id from email
$query = "SELECT s.student_id, s.department_id 
          FROM student s 
          JOIN login l ON s.email = l.email 
          WHERE l.email = ?";
$stmt = $mysql->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

if (!$student) {
    die("Student data not found.");
}

$student_id = $student['student_id'];
$department_id = $student['department_id'];

// Handle course enrollment
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $course_id = $_POST['course_id'];

    // Check for duplicate enrollment
    $check = $mysql->prepare("SELECT * FROM enrollment WHERE student_id = ? AND course_id = ?");
    $check->bind_param("ii", $student_id, $course_id);
    $check->execute();
    $checkResult = $check->get_result();

    if ($checkResult->num_rows > 0) {
        echo "<p style='color:red;'>You are already enrolled in this course.</p>";
    } else {
        $enroll = $mysql->prepare("INSERT INTO enrollment (student_id, course_id) VALUES (?, ?)");
        $enroll->bind_param("ii", $student_id, $course_id);
        if ($enroll->execute()) {
            echo "<script>alert('Enrolled successfully!'); window.location.href='enrollcourse.php';</script>";
        } else {
            echo "<script>alert('Enrolled failed!'); window.location.href='enrollcourse.php';</script>";
        }
    }
}

// Get list of available courses for this department
$courses = $mysql->prepare("SELECT course_id, name FROM course WHERE department_id = ?");
$courses->bind_param("i", $department_id);
$courses->execute();
$courseList = $courses->get_result();
?>
<!DOCTYPE html>
    <head>
        <title>Student Course</title>
        <style>
            body {
            background-color: #effafe;
            margin: 0;
            }

            h2 {
                color: rgb(7, 25, 86);
            }

            ul {
                list-style-type: none;
                margin: 0;
                padding: 0;
                width: 15%;
                background-color: #eefaf8;
                position: fixed;
                height: 100%;
                overflow: auto;
            }

            li a {
                display: block;
                color: #000;
                padding: 8px 16px;
                text-decoration: none;
            }

            li a.active {
                background-color: #7acded;
                background-image: linear-gradient(to right, #7acded, #effafe);
                color: black;
            }

            li a.visited {
                background-color: #7acded;
                color: white;
            }

            li a:hover:not(.active) {
                background-color: #7acded;
                color: white;
            }

            /* Dropdown submenu styling */
        .submenu {
            display: none;
            position: absolute;
            top: 100; /* Align submenu with the parent item */
            left: 0%; /* Position submenu to the right of the parent */
            background-color: #fff; /* White background for submenu */
            width: 100%;
            height: 100%;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2); /* Add shadow to submenu */
            padding: 10px 0;
            z-index: 1000; /* Ensure the submenu is on top */
        }

        .submenu li a {
            padding: 10px 20px;
            color: black;
            text-decoration: none;
        }

        .submenu li a:hover {
            background-color: #effafe;
            color: black;
        }

        /* Show submenu when hovering over the parent menu item */
        .dropdown:hover .submenu {
            display: table;
        }

        /* Ensure parent li is relatively positioned */
        .dropdown {
            position: relative;
        }
        table {
            clear: both;
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 90%;
            margin-top: 18px;
            margin-left: 5%;
        }

        td, th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }

        th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color:rgb(4, 115, 170);
            color: white;
        }
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
        .btnn {
        float: right;
        color: #e74c3c;              
        padding-left: 13.5px;        
        text-decoration: none;    
        font-size: 16px;
        }

        .btnn:hover {
            color: #c0392b;
            text-decoration: underline;
        }
        </style>
    </head>
    <body>
        <ul>
            <li><a href="userdash.php">Home</a></li>
            <li class="dropdown">
                <a class="visited" href="">Courses</a>
                <ul class="submenu">
                    <li><a href="curriculum.php">Curriculum</a></li>
                    <li><a class="active" href="enrollcourse.php">Enroll courses</a></li>
                    <li><a href="yourcourse.php">My courses</a></li>
                </ul>
            </li>
        </ul>

        <div style="margin-left: 15%; padding: 1px 16px;">
            <p style = "text-align:left;">Hello, <?php echo $email?><a href="enrollcourse.php?logout=true" class="btnn">Logout</a></p>
            <h2>Enroll in a Course</h2>
            <form method="post">
                <label for="course_id">Select Course:</label>
                <select name="course_id" required>
                    <option value="">-- Choose a course --</option>
                    <?php while ($row = $courseList->fetch_assoc()): ?>
                        <option value="<?= $row['course_id'] ?>"><?= htmlspecialchars($row['name']) ?></option>
                    <?php endwhile; ?>
                </select>
                <br><br>
                <input type="submit" value="Enroll">
            </form>
        </div>
    </body>
</html>

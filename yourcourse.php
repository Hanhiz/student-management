<?php
session_start();
$servername = "localhost";
$username = "root";
$pass = "";
$dbname = "student";
$mysql = new mysqli($servername, $username, $pass, $dbname);

// Check connection
if ($mysql->connect_error) {
    die("Connection failed: " . $mysql->connect_error);
}

// Ensure student is logged in
if (!isset($_SESSION['email'])) {
    die("Access denied. Please log in first.");
}

// Logout functionality
    if (isset($_GET['logout'])) {
        session_unset();
        session_destroy();
        header("Location: login.php");
        exit();
    }
    
$email = $_SESSION['email'];

// Get student_id using login email
$query = "
    SELECT s.student_id
    FROM student s
    JOIN login l ON s.email = l.email
    WHERE l.email = ?
";
$stmt = $mysql->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

if (!$student) {
    die("Student profile not found.");
}

$student_id = $student['student_id'];

// Get enrolled courses
$sql = "
    SELECT c.name AS course_name, c.code AS course_code, d.name AS department_name
    FROM enrollment e
    JOIN course c ON e.course_id = c.course_id
    JOIN department d ON c.department_id = d.department_id
    WHERE e.student_id = ?
";
$stmt = $mysql->prepare($sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$courses = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Enrolled Courses</title>
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
                    <li><a href="enrollcourse.php">Enroll courses</a></li>
                    <li><a class="active" href="yourcourse.php">My courses</a></li>
                </ul>
            </li>
        </ul>

        <div style="margin-left: 15%; padding: 1px 16px;">
            <p style = "text-align:left;">Hello, <?php echo $email?><a href="yourcourse.php?logout=true" class="btnn">Logout</a></p>
            <h2>My Enrolled Courses</h2>

            <?php if ($courses->num_rows > 0): ?>
                <table border="1" cellpadding="10">
                    <thead>
                        <tr>
                            <th>Course Code</th>
                            <th>Course Name</th>
                            <th>Department</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $courses->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['course_code']) ?></td>
                                <td><?= htmlspecialchars($row['course_name']) ?></td>
                                <td><?= htmlspecialchars($row['department_name']) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>You have not enrolled in any courses yet.</p>
            <?php endif; ?>
    </body>
</html>

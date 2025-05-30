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

// Fetch user details based on the logged-in user ID
$email = $_SESSION['email'];  // Get the logged-in user's ID from the session
$sql = "SELECT login.email, login.password, student.student_id, student.name, student.department_id, student.birth, student.phone
        FROM login
        LEFT JOIN student ON login.email = student.email
        WHERE login.email=?";
$stmt = $mysql->prepare($sql);
$stmt->bind_param("s", $email);  // Bind the session's user ID to the SQL query
$stmt->execute();
$result = $stmt->get_result();

// Check if the user exists in the database
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $email = $user['email'];
    $student_id = $user['student_id'];
    $name = $user['name'];
    $department = $user['department_id'];
    $birth = $user['birth'];
    $phone = $user['phone'];
    $pass = $user['password'];
} else {
    echo "User not found!";
    exit();
}
// Logout functionality
    if (isset($_GET['logout'])) {
        session_unset();
        session_destroy();
        header("Location: login.php");
        exit();
    }
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
                    <li><a class="active" href="curriculum.php">Curriculum</a></li>
                    <li><a href="enrollcourse.php">Enroll courses</a></li>
                    <li><a href="yourcourse.php">My courses</a></li>
                </ul>
            </li>
        </ul>

        <div style="margin-left: 15%; padding: 1px 16px;">
            <p style = "text-align:left;">Hello, <?php echo $email?><a href="curriculum.php?logout=true" class="btnn">Logout</a></p>
            <h2>Courses</h2>
            <?php
                $course_result = $mysql->query("SELECT * FROM course WHERE department_id = '$department'");

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
            ?>
        </div>
    </body>
</html>
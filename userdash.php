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
$sql = "SELECT login.email, login.password, student.student_id, student.name, student.department_id, student.birth, student.phone, student.profile_image
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
    $profile_image = isset($user['profile_image']) && $user['profile_image'] ? $user['profile_image'] : 'image/icon.png';
} else {
    echo "User not found!";
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $newName = htmlspecialchars(trim($_POST['name']));
    $newBirth = $_POST['birth'];
    $newPhone = $_POST['phone'];
    $newPass = $_POST['pass'];

    // Debugging: Output the POST data
    echo "New Name: " . $newName . "<br>";
    echo "New Birth: " . $newBirth . "<br>";
    echo "New Pass: " . $newPass . "<br>";

    // Check if the $_POST data is empty
    if (empty($newName) || empty($newBirth) || empty($newPass)) {
        echo "Name or Birth or Password is empty. Please fill out the fields.";
        exit();
    }

    // Update student table
    $update_student_sql = "UPDATE student SET name=?, birth=?, phone=? WHERE email=?";
    $stmt_student = $mysql->prepare($update_student_sql);
    if (!$stmt_student) {
        echo "Student table error: " . $mysql->error;
        exit();
    }
    $stmt_student->bind_param("ssss", $newName, $newBirth, $newPhone, $email);
    $stmt_student->execute();

    // Update login table
    $update_login_sql = "UPDATE login SET password=? WHERE email=?";
    $stmt_login = $mysql->prepare($update_login_sql);
    if (!$stmt_login) {
        echo "Login table error: " . $mysql->error;
        exit();
    }
    $stmt_login->bind_param("ss", $newPass, $email);
    $stmt_login->execute();

    echo "<script>alert('Update successful!'); window.location.href='userdash.php';</script>";
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #bef2e5;
            background-image: linear-gradient(#7acded, #bef2e5);
            background-repeat: no-repeat, repeat;
            padding: 20px;
        }
        .container {
            max-width: 950px;
            margin: 30px auto;
            background-color: #fff;
            padding: 32px 24px;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
            display: flex;
            gap: 32px;
            flex-wrap: wrap;
        }
        h1 {
            text-align: left;
            color: #07215a;
            margin-bottom: 18px;
        }
        input {
            border: 1.5px solid #7acded;
            border-radius: 5px;
            padding: 7px 10px;
            width: 95%;
            margin-bottom: 4px;
            font-size: 15px;
            background: #f7fbfc;
        }
        p {
            border: 1.5px solid #7acded;
            border-radius: 5px;
            padding: 7px 10px;
            background: #f7fbfc;
            margin-bottom: 4px;
            font-size: 15px;
        }
        label {
            font-weight: bold;
            display: block;
            margin-bottom: 4px;
            color: #0b7dda;
        }
        .user-info {
            flex: 2 1 350px;
            width: 100%;
        }
        .he{
            display: flex;
            align-items: center;
            gap: 18px;
            margin-bottom: 18px;
        }
        .he-img img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 2px solid #7acded;
            object-fit: cover;
            background: #eefaf8;
        }
        .he-in {
            font-size: 16px;
            color: #333;
            line-height: 1.5;
        }
        .he-in i {
            color: #2196F3;
            font-style: normal;
        }
        .bo {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            margin-bottom: 12px;
        }
        .bo1 {
            width: 48%;
            margin-bottom: 10px;
        }
        .img {
            flex: 1 1 220px;
            min-width: 220px;
            max-width: 300px;
            background: #f8fcfd;
            border-radius: 8px;
            padding: 18px 14px;
            margin-top: 24px;
            box-shadow: 0 2px 8px rgba(122,205,237,0.08);
            text-align: center;
            height: fit-content;
        }
        .img img {
            width: 100%;
            max-width: 180px;
            height: auto;
            border-radius: 8px;
            margin-bottom: 10px;
            border: 1px solid #d0e6ef;
        }
        .edit-info {
            text-align: center;
            margin-top: 18px;
        }
        .logout {
            display: inline-block;
        }
        .logout a {
            padding: 8px 18px;
            background-color: #f44336;
            color: white;
            border-radius: 4px;
            text-decoration: none;
            font-size: 15px;
            margin-left: 8px;
            transition: background 0.2s;
        }
        .logout a:hover {
            background-color: #d32f2f;
        }
        #update {
            background-color: #2196F3;
            color: white;
            border-radius: 4px;
            border: none;
            font-size: 15px;
            cursor: pointer;
            padding: 8px 20px;
            transition: background 0.2s;
            width: 10%;
            text-align: center;
        }
        #update:hover {
            background-color: #0b7dda;
        }
        .img h3 {
            margin-bottom: 10px;
            color: #2196F3;
        }
        .img form {
            margin-top: 10px;
        }
        .img input[type="file"] {
            margin-bottom: 10px;
        }
        .img input[type="submit"] {
            padding: 7px 22px;
            background-color: #2196F3;
            color: white;
            border-radius: 4px;
            border: none;
            font-size: 14px;
            cursor: pointer;
            transition: background 0.2s;
        }
        .img input[type="submit"]:hover {
            background-color: #0b7dda;
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
            top: 0;
            left: 0;
            z-index: 10;
        }
        li a {
            display: block;
            color: #000;
            padding: 10px 18px;
            text-decoration: none;
            font-size: 16px;
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
        .submenu {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background-color: #fff;
            width: 100%;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
            padding: 10px 0;
            z-index: 1000;
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
        .dropdown:hover .submenu {
            display: table;
        }
        .dropdown {
            position: relative;
        }
        @media (max-width: 900px) {
            .container {
                flex-direction: column;
                gap: 0;
            }
            .img, .user-info {
                min-width: 0;
                width: 100%;
                max-width: 100%;
            }
            ul {
                position: static;
                width: 100%;
                height: auto;
                display: flex;
                flex-direction: row;
            }
            li {
                flex: 1 1 auto;
            }
        }
    </style>
</head>
<body>
    <ul>
        <li><a class="active" href="userdash.php">Home</a></li>
        <li class="dropdown">
            <a href="">Courses</a>
            <ul class="submenu">
                <li><a href="curriculum.php">Curriculum</a></li>
                <li><a href="enrollcourse.php">Enroll courses</a></li>
                <li><a href="yourcourse.php">My courses</a></li>
            </ul>
        </li>
    </ul>

    <div style="margin-left: 15%; padding: 1px 16px;">
        <div class="container">
            <div class="user-info">
                <h1>Welcome, <?php echo $name; ?>!</h1>  
                <div class="he">
                    <div class="he-img">
                        <img src="<?php echo htmlspecialchars($profile_image); ?>" width="60%" style="margin-left: 20%;">
                    </div>
                    <div class="he-in">
                        <?php echo $name; ?> - 
                            <i><?php echo $student_id; ?><br></i>
                            <?php echo $email; ?>
                    </div>
                </div>
                <hr>
                <!-- User info update form -->
                <form method="POST">
                    <div class="bo">
                        <div class="bo1">
                            <label>Student ID:</label>
                            <p><?php echo $student_id; ?></p>
                        </div>
                        <div class="bo1">
                            <label>Department ID:</label>
                            <p><?php echo $department; ?></p>
                        </div>
                        <div class="bo1">
                            <label>Name:</label>
                            <input type="text" name="name" value="<?php echo $name; ?>">
                        </div>
                        <div class="bo1">
                            <label>Birthday:</label>
                            <input type="text" name="birth" value="<?php echo $birth; ?>">
                        </div>
                        <div class="bo1">
                            <label>Phone:</label>
                            <input type="tel" name="phone" value="<?php echo $phone; ?>">
                        </div>
                        <div class="bo1">
                            <label>Password:</label>
                            <input type="password" name="pass" value="<?php echo $pass; ?>">
                        </div>
                        <div class="bo1">
                            <label>Email:</label>
                            <p><?php echo $email; ?></p>
                        </div>
                        
                    </div>
                    
                    <div class="edit-info">
                        <input type="submit" id="update" value="Update">
                            <span class="logout">
                                <a href="logout.php">Logout</a> 
                            </span>
                    </div>
                </form>
                <hr>
                <center>
                <!-- Profile image upload form (separate, not inside the above form) -->
                <div class="img">
                    <h3>Upload Profile Image</h3>
                    <form enctype="multipart/form-data" action="upload.php" method="POST">
                        Choose a file to upload:
                        <br>
                        <input type="file" name="fileupload" id="fileupload">
                        <input type="submit" value="Upload" name="submit">
                    </form>
                </div>
                </center>
            </div>
        </div>
    </body>
</html>

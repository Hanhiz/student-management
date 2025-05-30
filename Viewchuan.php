<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student List</title>
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
      
      h1{
        color:rgb(7, 25, 86);
        margin-bottom: 15px;
        padding-top: 2%;
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
    <?php
    session_start();  // Start the session for logout functionality

    // Logout functionality
    if (isset($_GET['logout'])) {
        session_unset();
        session_destroy();
        header("Location: login.php");
        exit();
    }

    if(isset($_POST['Register'])){
        // Info connection
        $servername = "localhost";
        $username="root";
        $pass="";
        $dbname="student";

        // Output to Form
        $id=$_POST['id'];
        $department=$_POST['department'];
        $name=$_POST['name'];
        $birth=$_POST['birth'];
        $email=$_POST['email'];
        $phone=$_POST['phone'];

        // Create MySQL connection
        $mysql = new mysqli($servername, $username, $pass, $dbname);
        if ($mysql->connect_error) {
            echo "Connection failed.";
        } else {
          $role = "User";
          $sql_login = "INSERT INTO login (email, password, role) VALUES ('$email','12345','$role')";
          $mysql->query($sql_login);
          $sql = "INSERT INTO student (student_id, department_id, name, birth, email, phone) 
                  VALUES ('$id', '$department', '$name', '$birth', '$email', '$phone')";
          $mysql->query($sql);
          $mysql->close();
        }
    }

    $servername = "localhost";
    $username="root";
    $pass="";
    $dbname="student";
    $mysql = new mysqli($servername, $username, $pass, $dbname);

    // Check connection
    if($mysql->connect_error){
        echo "Connection failed.";
    } else {

    if (isset($_GET["delete"])) {
    $student_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
    $email = isset($_GET['email']) ? $_GET['email'] : null;

    if ($student_id <= 0) {
        die("Invalid student ID.");
    }
    if (empty($email)) {
        die("Email parameter missing.");
    }

    // Delete student
    $stmt1 = $mysql->prepare("DELETE FROM student WHERE student_id = ?");
    $stmt1->bind_param("i", $student_id);
    $stmt1->execute();
    $stmt1->close();

    // Delete login
    $stmt2 = $mysql->prepare("DELETE FROM login WHERE email = ?");
    if (!$stmt2) {
        die("Prepare failed: " . $mysql->error);
    }
    $stmt2->bind_param("s", $email);
    $stmt2->execute();
    if ($stmt2->affected_rows === 0) {
        echo "No matching login record found for email: " . htmlspecialchars($email);
    }
    $stmt2->close();

      // Reset AUTO_INCREMENT for student table
      $result = $mysql->query("SELECT MAX(student_id) AS max_id FROM student");
      $row = $result->fetch_assoc();
      $next_id = $row['max_id'] ? $row['max_id'] + 1 : 1;
      $mysql->query("ALTER TABLE student AUTO_INCREMENT = $next_id");

      // Reset AUTO_INCREMENT for login table using login_id PK
      $result = $mysql->query("SELECT MAX(login_id) AS max_id FROM login");
      $row = $result->fetch_assoc();
      $next_id = $row['max_id'] ? $row['max_id'] + 1 : 1;
      $mysql->query("ALTER TABLE login AUTO_INCREMENT = $next_id");

      echo "<script>alert('Student deleted sucessfully'); window.location.href='Viewchuan.php';</script>";
    }

        // Select student records
        $sql = "SELECT * FROM student LEFT JOIN login ON login.email = student.email";
        $result = $mysql->query($sql);

        // Get current user's role
        $user_role = '';
        if (isset($_SESSION['email'])) {
            $current_email = $_SESSION['email'];
            $role_stmt = $mysql->prepare("SELECT role FROM login WHERE email = ?");
            $role_stmt->bind_param("s", $current_email);
            $role_stmt->execute();
            $role_stmt->bind_result($user_role);
            $role_stmt->fetch();
            $role_stmt->close();
        }

        echo "<nav>";
        // Show CRUD.php link only for Admin
        if ($user_role === 'Admin') {
            echo '<a href="CRUD.php">User Accounts</a>';
        }
        echo '<a href="Viewchuan.php" class="active">User information</a>';
        echo '<a href="department.php">Department information</a>';
        echo '<a href="Viewchuan.php?logout=true" class="btnn">Logout</a>';
        echo "</nav>";
        echo "<div class='content'>";
        echo "<CENTER> <h1> STUDENT LIST </h1> </CENTER>";
        echo '<a href="register.php" class="btn">Add New Student</a>';
        echo '<table>';
        echo "<tr><th>ID</th><th>Department</th><th>Name</th><th>Birth</th><th>Email</th><th>Phone</th><th>Update</th><th>Delete</th></tr>";
        
        // Display students in a table
        while($row = $result->fetch_assoc()){
            echo "<tr>";
            echo "<td>" . $row['student_id'] . "</td>";
            echo "<td>" . $row['department_id'] . "</td>";
            echo "<td>" . $row['name'] . "</td>";
            echo "<td>" . $row['birth'] . "</td>";
            echo "<td>" . $row['email'] . "</td>";
            echo "<td>" . $row['phone'] . "</td>";

            echo "<td><a href='Update.php?id=" . $row['student_id'] . "&department=" . $row['department_id'] . "&name=" . $row['name'] . "&birth=" . $row['birth'] . "&email=" . $row['email'] . "&phone=" . $row['phone'] . "'>
                    <img src='update.png' alt='Update' width=40px height=40px> </a></td>";
            echo "<td><a href='Viewchuan.php?id=" . $row['student_id'] . '&email=' . $row['email'] .  "&delete=ok'>
                    <img src='delete.png' alt='Delete' width=40px height=40px> </a></td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "<br><br>";
        echo "</div>";
        $mysql->close();
    }
    ?>

</body>
</html>

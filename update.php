<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Information Update</title>
    <style>
      p {
        width: 100%;
        padding: 12px 20px;
        margin: 8px 0;
        display: inline-block;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
        background-color:rgb(255, 255, 255);
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

      input[type=date], select {
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
      
      input[type=tel], select {
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
    <center> <h1> STUDENT INFORMATION UPDATE</h1> 
    <div>
      <form action="" method="POST">
        <label for="masv">Student ID</label>
        <input type="hidden" id="id" name="id" value="<?php echo htmlspecialchars($id ?? ($_GET['id'] ?? '')); ?>">
        <p><?php echo htmlspecialchars($id ?? ($_GET['id'] ?? '')); ?></p>
        
        <label for="makhoa">Department</label>
        <select name="department" id="department" required>
            <option value="1" <?php if (($department ?? ($_GET['department'] ?? '')) == '1') echo 'selected'; ?>>Technology</option>
            <option value="2" <?php if (($department ?? ($_GET['department'] ?? '')) == '2') echo 'selected'; ?>>Business</option>
            <option value="3" <?php if (($department ?? ($_GET['department'] ?? '')) == '3') echo 'selected'; ?>>Management</option>
            <option value="4" <?php if (($department ?? ($_GET['department'] ?? '')) == '4') echo 'selected'; ?>>MIS</option>
        </select>

        <label for="hoten">Full Name</label>
        <input type="text" id="name" name="name" placeholder="Enter full name..." value="<?php echo htmlspecialchars($_GET['name'] ?? ''); ?>" required>
        
        <label for="ngaysinh">Date of Birth</label>
        <input type="date" id="birth" name="birth" placeholder="Enter date of birth..." value="<?php echo htmlspecialchars($_GET['birth'] ?? ''); ?>" required>
        
        <label for="email"><br>Email</label>
        <input type="text" id="email" name="email" placeholder="Enter email..." value="<?php echo htmlspecialchars($_GET['email'] ?? ''); ?>" required> 

        <label for="phone"><br>Phone</label>
        <input type="tel" id="phone" name="phone" placeholder="Enter phone number..." value="<?php echo htmlspecialchars($_GET['phone'] ?? ''); ?>" required> 
        
        <input type="submit" value="Update" name="Update" id="Update">
        </form>
      </div>
      </center>
    <?php
    $servername = "localhost";
    $username = "root";
    $pass = "";
    $dbname = "student";

    // Connect to the database
    $mysql = new mysqli($servername, $username, $pass, $dbname);
    if ($mysql->connect_error) {
        die("Connection failed: " . $mysql->connect_error);
    }

    // Handle GET request to fetch student info (for displaying data in the form)
    if (isset($_GET['email'])) {
        $email = $_GET['email'];
        $sql = "SELECT login.email, login.password, student.student_id, student.name, student.department_id, student.birth, student.phone
                FROM login
                LEFT JOIN student ON login.email = student.email
                WHERE login.email=?";
        $stmt = $mysql->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($email, $password, $id, $name, $department, $birth, $phone);
            $stmt->fetch();
        } else {
            echo "Student not found!";
        }
        $stmt->close();
    }

    // Handle form submission
    if (isset($_POST['Update'])) {
        $id = $_POST['id'];
        $department = $_POST['department'];
        $name = $_POST['name'];
        $birth = $_POST['birth'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];

        // Update the student information
        $sql = "UPDATE student 
                SET department_id=?, name=?, birth=?, email=?, phone=?
                WHERE student_id=?";
        $stmt_student = $mysql->prepare($sql);
        if (!$stmt_student) {
            echo "Student table error: " . $mysql->error;
            exit();
        }
        $stmt_student->bind_param("ssssss", $department, $name, $birth, $email, $phone, $id); 
        $stmt_student->execute();

        // If a new password is provided, update it
        if (!empty($Newpass)) {
            // Update login table
            $update_login_sql = "UPDATE login SET password=? WHERE email=?";
            $stmt_login = $mysql->prepare($update_login_sql);
            if (!$stmt_login) {
                echo "Login table error: " . $mysql->error;
                exit();
            }
            $stmt_login->bind_param("ss", $Newpass, $email); // Use email as the user ID
            $stmt_login->execute();
        }

        // Redirect or show success message
        echo "<script>alert('Student updated successfully!'); window.location.href='Viewchuan.php';</script>";
    }
    ?>
  </body>
</html>

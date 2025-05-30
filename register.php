<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Information Registration</title>
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
    $result = $mysql->query("SHOW TABLE STATUS LIKE 'student'");
    $row = $result->fetch_assoc();
    $next_id = $row['Auto_increment'];
    ?>
    <center> <h1> STUDENT INFORMATION REGISTRATION</h1> 
    <div>
      <form action="Viewchuan.php" method="POST">
        <label for="student_id">Student ID</label>
        <input type="text" id="id" name="id" value="<?php echo $next_id;?>">
        
        <label for="makhoa">Department ID</label>
        <select name="department" id="department" placeholder="Enter department ID...">
                <option value="1">Technology</option>
                <option value="2">Business</option>
                <option value="3">Management</option>
                <option value="4">MIS</option>
            </select>
        <label for="hoten">Full Name</label>
        <input type="text" id="name" name="name" placeholder="Enter full name...">
        
        <label for="ngaysinh">Date of Birth</label>
        <input type="date" id="birth" name="birth" placeholder="Enter date of birth...">

        <label for="phone">Phone number</label>
        <input type="tel" id="phone" name="phone" placeholder="Enter phone number...">
        
        <label for="email"><br>Email</label>
        <input type="text" id="email" name="email" placeholder="Enter email..."> 
        
        <input type="submit" value="Register" name="Register" id="Register">
      </form>
    </div>
    </center>
  </body>
</html>

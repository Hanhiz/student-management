<?php
$servername = "localhost";
$username = "root";
$pass = "";
$dbname = "student";

// Create connection
$mysql = new mysqli($servername, $username, $pass, $dbname);

// Check connection
if ($mysql->connect_error) {
    die("Connection failed: " . $mysql->connect_error);
}

session_start();
if (isset($_POST['Login'])) {
  $email = $_POST['email'];
  $password = $_POST['password'];

  // Use prepared statement for better security
  $stmt = $mysql->prepare("SELECT * FROM login WHERE email=? AND password=?");
  $stmt->bind_param("ss", $email, $password);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
      $user = $result->fetch_assoc();
      $_SESSION['email'] = $email;
      $_SESSION['role'] = $user['role'];

      // Redirect based on role
      if ($user['role'] == 'Admin') {
          header("Location: CRUD.php");
          exit();
      } elseif ($user['role'] == 'Editor') {
          header("Location: Viewchuan.php");
          exit();
      } elseif ($user['role'] == 'User') {
        header("Location: userdash.php");
        exit();
      }else {
          echo "<script>alert('Access denied for this role'); window.location.href='login.php';</script>";
          exit();
      }
  } else {
      echo "<script>alert('Invalid credentials'); window.location.href='login.php';</script>";
      exit();
  }
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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

      div {
        border-radius: 5px;
        background-color: #f2f2f2;
        padding: 20px;
        margin: 0 auto;
        width: 30%;
        text-align:left;
      }
      body{
        background-image: url("image/image.png");
        background-size: cover;
        background-position: center;
      }
      h1{
        margin-top: 10%;
        color:#ffffff;
        font-size: 25pt;
      }
      a {
        margin-left: 75%;
        color: #2e6977;
      }
      a:hover {
        color: #7ebeda;
      }
    </style>
  </head>
  <body>
    <center> <h1> Login</h1> 
    <div>
      <form action="login.php" method="POST">
        <label for="id">Email</label>
        <input type="text" id="email" name="email" placeholder="Enter email..." required>
        
        <label for="pass">Password</label>
        <input type="password" id="password" name="password" placeholder="Enter password..." required>

        <input type="submit" value="Login" name="Login" id="Login">
      </form>
      <a href="useredit.php">Forget password?</a>
    </div>
    </center>
  </body>
</html>

<?php
// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "student";

$mysql = new mysqli($servername, $username, $password, $dbname);
if ($mysql->connect_error) {
    die("Connection failed: " . $mysql->connect_error);
}

// Handle the password reset request
if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $new_password = $_POST['new_password'];
    
    // Check if the email exists in the login table
    $sql = "SELECT userid FROM login WHERE userid=?";
    $stmt = $mysql->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {

        // Update the password in the login table
        $sql = "UPDATE login SET password=? WHERE userid=?";
        $stmt = $mysql->prepare($sql);
        $stmt->bind_param("ss", $new_password, $email);
        if ($stmt->execute()) {
            // Redirect to login page after successful password change
            echo "<script>alert('Password updated successfully. Please login.'); window.location.href='login.php';</script>";
        } else {
            echo "<script>alert('Error updating password. Please try again.');</script>";
        }
    } else {
        // If email is not found in the system
        echo "<script>alert('No user found with this email address.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <style>
        body {
            background-image: url("image/image.png");
            background-size: cover;
            background-position: center;
        }
        input[type=email], input[type=password], input[type=submit] {
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
            padding: 12px 20px;
            margin: 8px 0;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            background-color: #5aa392;
            color: #ffffff;
        }
        input[type=submit]:hover {
            background-color: #89c2b7;
            color: #ffffff;
        }
        div {
            border-radius: 5px;
            background-color: #f2f2f2;
            padding: 20px;
            margin: 0 auto;
            width: 30%;
            text-align:left;
        }
        h1{
            margin-top:10%;
            color: white;
        }
    </style>
</head>
<body>
    <center> <h1> Reset Password </h1> 
    <div>
        <form action="" method="POST">
            <label for="email">Email (User ID)</label>
            <input type="email" id="email" name="email" placeholder="Enter your email..." required>
            
            <label for="new_password">New Password</label>
            <input type="password" id="new_password" name="new_password" placeholder="Enter new password..." required>

            <input type="submit" value="Update Password" name="submit">
        </form>
    </div>
    </center>
</body>
</html>

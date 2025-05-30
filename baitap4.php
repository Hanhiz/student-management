<?php
// Start session
session_start();

// Base class: Person
class Person {
    protected $name;
    protected $age;

    public function __construct($name, $age) {
        $this->name = $name;
        $this->age = $age;
    }
}

// Subclass: Student
class Student extends Person {
    private $id, $major, $address, $email, $phone;

    public function __construct($name, $age, $id, $major, $address, $email, $phone) {
        parent::__construct($name, $age);
        $this->id = $id;
        $this->major = $major;
        $this->address = $address;
        $this->email = $email;
        $this->phone = $phone;
    }

    public function displayInfo() {
        echo "<div class='output'>";
        echo "<h3>Student Information</h3>";
        echo "📛 <b>Name:</b> " . htmlspecialchars($this->name) . "<br>";
        echo "📅 <b>Age:</b> " . htmlspecialchars($this->age) . "<br>";
        echo "🆔 <b>Student ID:</b> " . htmlspecialchars($this->id) . "<br>";
        echo "📚 <b>Major:</b> " . htmlspecialchars($this->major) . "<br>";
        echo "🏠 <b>Address:</b> " . htmlspecialchars($this->address) . "<br>";
        echo "📧 <b>Email:</b> " . htmlspecialchars($this->email) . "<br>";
        echo "📞 <b>Phone:</b> " . htmlspecialchars($this->phone) . "<br>";
        echo "</div>";
    }
}

// MySQL Database Connection
$mysqli = new mysqli("localhost", "root", "", "student");

// Check connection
if ($mysqli->connect_error) {
    die("❌ Failed to connect to MySQL: " . $mysqli->connect_error);
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
    $name = $_POST["name"];
    $age = $_POST["age"];
    $id = $_POST["id"];
    $major = $_POST["major"];
    $address = $_POST["address"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];

    // Create Student Object
    $student = new Student($name, $age, $id, $major, $address, $email, $phone);

    // Store in session to display once
    $_SESSION["student_data"] = serialize($student);

    // Secure SQL Query using Prepared Statements
    $stmt = $mysqli->prepare("INSERT INTO student (id, name, age, major, address, email, phone) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isissss", $id, $name, $age, $major, $address, $email, $phone);

    if ($stmt->execute()) {
        $_SESSION["success"] = "Student information submitted successfully!";
    } else {
        $_SESSION["error"] = "Failed to save student data.";
    }

    $stmt->close();

    // Redirect to remove POST data
    header("Location: " . $_SERVER["PHP_SELF"]);
    exit;
}

// Retrieve student data from session
$student = isset($_SESSION["student_data"]) ? unserialize($_SESSION["student_data"]) : null;
unset($_SESSION["student_data"]); // Clear session data after displaying

// Get success/error messages
$successMessage = isset($_SESSION["success"]) ? $_SESSION["success"] : "";
$errorMessage = isset($_SESSION["error"]) ? $_SESSION["error"] : "";
unset($_SESSION["success"], $_SESSION["error"]); // Clear messages after display

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            min-height: 100vh;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
            text-align: center;
        }
        h2 {
            color: #333;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        input {
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        input[type="submit"] {
            background-color: #28a745;
            color: white;
            cursor: pointer;
            border: none;
        }
        input[type="submit"]:hover {
            background-color: #218838;
        }
        .output {
            margin-top: 20px;
            padding: 15px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
            text-align: left;
        }
        .output h3 {
            text-align: center;
            color: #007bff;
        }
        .success {
            color: green;
            font-weight: bold;
            margin-top: 10px;
        }
        .error {
            color: red;
            font-weight: bold;
            margin-top: 10px;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Student Registration Form</h2>

        <?php 
        if ($successMessage) {
            echo "<p class='success'>$successMessage</p>";
        } elseif ($errorMessage) {
            echo "<p class='error'>$errorMessage</p>";
        }
        ?>

        <form action="" method="post">
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="number" name="age" placeholder="Age" required>
            <input type="number" name="id" placeholder="Student ID" required>
            <input type="text" name="major" placeholder="Major" required>
            <input type="text" name="address" placeholder="Address" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="phone" placeholder="Phone Number" required>
            <input type="submit" name="submit" value="Register">
        </form>

        <?php 
        if ($student) {
            $student->displayInfo();
        }
        ?>
    </div>

</body>
</html>

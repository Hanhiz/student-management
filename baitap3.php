<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shape Calculator</title>
</head>
<body>

    <h2>Calculate Area & Perimeter</h2>

    <!-- Circle Form -->
    <h3>Circle</h3>
    <form action="" method="post">
        Radius: <input type="number" name="circle_radius" step="0.01" required>
        <input type="submit" name="calculate_circle" value="Calculate">
    </form>

    <!-- Triangle Form -->
    <h3>Triangle</h3>
    <form action="" method="post">
        Side A: <input type="number" name="triangle_a" step="0.01" required>
        Side B: <input type="number" name="triangle_b" step="0.01" required>
        Side C: <input type="number" name="triangle_c" step="0.01" required>
        <input type="submit" name="calculate_triangle" value="Calculate">
    </form>

    <?php
    /* Create a Shape class with an area() method. Create two subclass that inherit from Shape: Circle and Triangle. 
    Override the area() method in both subclasses. Build an interface that allows users 
    to input the corresponding parameters for the Circle and Triangle classes, calculate their perimeter and area,
    and display the results on the screen.
    */
    // Base class
    abstract class Shape {
        abstract public function area();
        abstract public function perimeter();
    }

    // Circle subclass
    class Circle extends Shape {
        private $radius;

        public function __construct($radius) {
            $this->radius = $radius;
        }

        public function area() {
            return M_PI * pow($this->radius, 2);
        }

        public function perimeter() {
            return 2 * M_PI * $this->radius;
        }
    }

    // Triangle subclass
    class Triangle extends Shape {
        private $a, $b, $c;

        public function __construct($a, $b, $c) {
            $this->a = $a;
            $this->b = $b;
            $this->c = $c;
        }

        public function area() {
            // Heron's Formula
            $s = ($this->a + $this->b + $this->c) / 2;
            return sqrt($s * ($s - $this->a) * ($s - $this->b) * ($s - $this->c));
        }

        public function perimeter() {
            return $this->a + $this->b + $this->c;
        }
    }

    // Process Circle Calculation
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["calculate_circle"])) {
        $radius = $_POST["circle_radius"];
        $circle = new Circle($radius);

        echo "<h3>Circle Results</h3>";
        echo "Radius: $radius <br>";
        echo "Area: " . number_format($circle->area(), 2) . "<br>";
        echo "Perimeter: " . number_format($circle->perimeter(), 2) . "<br>";
    }

    // Process Triangle Calculation
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["calculate_triangle"])) {
        $a = $_POST["triangle_a"];
        $b = $_POST["triangle_b"];
        $c = $_POST["triangle_c"];

        // Validate if the sides can form a triangle
        if ($a + $b > $c && $a + $c > $b && $b + $c > $a) {
            $triangle = new Triangle($a, $b, $c);

            echo "<h3>Triangle Results</h3>";
            echo "Sides: $a, $b, $c <br>";
            echo "Area: " . number_format($triangle->area(), 2) . "<br>";
            echo "Perimeter: " . number_format($triangle->perimeter(), 2) . "<br>";
        } else {
            echo "<h3 style='color:red;'>Invalid Triangle: The sum of any two sides must be greater than the third side.</h3>";
        }
    }
    /*Build the Circle class, which includes:
    - attributes: radius: A floating-point value representing the radius of the circle
    - methods: Circle(): A constructor method that creates an object without parameters.
               getRadius(): A method that returns the radius of the circle.
               getArea(): A method that returns the area of the circle using the formula:
                    S = Math.PI * radiusS = Math.PI * radius * radius
    Write of program that takes the radius as input, creates a Circle object, calculates the area, and prints the result to the screen.
    
    // Define the Circle class
    class Circle {
        public $radius; // Attribute

        // Constructor to initialize radius
        function __construct($radius = 0) {
            $this->radius = $radius;
        }

        // Method to get the radius
        function getRadius() {
            return $this->radius;
        }

        // Method to calculate the area
        function getArea() {
            return M_PI * $this->radius * $this->radius; // Use M_PI for π
        }
    }

    // Get radius as input (example: 160)
    $radius = 160;

    // Create a Circle object with the given radius
    $circle = new Circle($radius);

    // Display the results
    echo "Radius: " . $circle->getRadius() . "<br>";
    echo "Area: " . $circle->getArea() . "<br>";
    */
    ?>
</body>
</html>

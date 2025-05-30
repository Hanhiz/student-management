<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewpoint" content="width-device-width, initial-scale=1.0">
        <title>test</title>
    </head>
    <body>
    <?php
        if(isset($_POST["login"]))
        {
            $name = $_POST["name"];
            $pass = $_POST["pass"];
            echo "name: " . $name . " pass: " . $pass;
        }
        if (isset($_GET["sum"]))
        {
            $x = $_GET["x"];
            $y = $_GET["y"];
            echo "$x + $y = " . ($x + $y);
        }
        
    /*
    <?php
    echo "Xin chao cac ban";
    ?>
    <?php
        $x = 16;
        $y = 8;
        function addWithoutGlobal(){
            echo "Without global var declaration, the result is " . ($x + $y);
        }
        function addWithGlobal(){
            global $x;
            global $y;
            echo "With global var declaradation, the result is " . ($x + $y);
        }
        addWithoutGlobal();
        echo "<br>";
        addWithGlobal();

    */
    ?>
    </body>
    </html>

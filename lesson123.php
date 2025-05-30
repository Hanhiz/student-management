<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewpoint" content="width=device-width, initial-scale=1.0">
        <title>Lession 1, 2, 3</title>
    </head>
    
    <body>
        <!--<p><marquee>Hello</marquee></p>
        <div>
            <form name="dangnhap" id="login" action="?" method="get">
                <table>
                    <tr>
                        <td>
                            Username
                        </td>
                        <td>
                            <input type="text">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Password
                        </td>
                        <td>
                            <input type="password">
                        </td>
                    </tr>
                </table>
            </form>
        </div>-->
        <?php 
            echo "<i><b>php viet tai day</b></i><br>";
            $X = "Hom nay la thu 6";
            echo "$X <br>";
            $team = array('D','A','B','C');
            $team[3];//'C'
            $oxo = array(
                array('x','','o'),
                array('o','x','x'),
                array('x','o','')
            );
            echo "$team[1]<br>";
            
            $name = "Ha Anh";
            $age = 20;
            $courses =  array('Java', 'C', 'PHP');
            echo "Name: " . $name . ", age: " . $age . "<br>3rd course is: " . $courses[2] . "<br/>";

            //Arithmetic operators
            $x = 10;
            $y = 11;
            echo "x: " . $x . "<br/>";
            echo "y: " . $y . "<br/>";
            echo "x/y: " . ($x/$y) . "<br/>";
            echo "x%y: " . ($x%$y) . "<br/>";
            echo "x++: " . ($x++) . "<br/>";
            echo "++y: " . (++$y) . "<br/>";
            //Comparison operators
            echo "x == y: " . ($x == $y) . "<br/>";
            echo "x != y: " . ($x != $y) . "<br/>";
            echo "x < y: " . ($x < $y) . "<br/>";
            echo "x > y: " . ($x > $y) . "<br/>";
            echo "x <= y: " . ($x <= $y) . "<br/>";
            echo "x >= y: " . ($x >= $y) . "<br/>";
        ?>
        <?php
            $count = 5;
            echo 'There are $count messages<br/>';
            echo 'There are ' . $count . ' messages <br>';
            echo "There are " . $count . "messages <br>";
            echo "There are $count messages <br>";
            echo "There are \$count messages<br>";
        ?>
        <?php
        $number = 123*456;
        var_dump($number); //dump the var info
        echo "<br>";
        print_r($number); //print human readable value
        echo "<br>";
        echo substr ($number, 3 , 2). "<br>"; //number is string
        echo "11" + "12"; //strings are numbers here

        //Sb ? print "True" : print "False"; //correct
        //Sb ? echo "True" : echo "False"; //error
        ?>
        <?php
        function longdate($timestamp){
            return date("l F jS Y", $timestamp);
        }
        echo longdate(time());
        /*
        //user defined function
        function displayDate($date){
            return date("l, F d, Y");
        }
        //call to that function
        echo displayDate(date());
        */
        ?> 
        <?php
            $x = 1;
            $y = 2;
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
        ?>
    </body>
</html>
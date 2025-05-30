<?php
    // 1. Wirte a PHP program to check whether a given number is positive, negative, or zero
    echo "Exercise 1: <br>";
    $z = -9;
    if ($z > 0){
        echo "$z is positive <br>";
    }
    else if($z < 0){
        echo "$z is negative <br>";
    }
    else{
        echo "$z is zero <br>";
    }
    // 2. Write a program to solve a quadratic equation: ax^2 + bx + c = 0
    echo "Exercise 2: <br>";
    $a = 4;
    $b = 12;
    $c = 9;
    $x = (-$b + sqrt($b*$b - 4*$a*$c))/(2*$a);
    echo "$x<br>";
?>
<?php
    /* 1. Write a PHP program to find the factorial of the given number.
    "A factorial is the product of all the natural numbers less than or equal to the given number N"*/
    echo "Exercise 1: <br>";
    $x = 6;
    for ($x < 7; $x; --$x){
        echo "$x ";
    }
    /* 2. Thefibonacci series is the sequence where each number is the sum of the previous two numbers of the sequence.
    The first two number are 0 and 1 which are used to generate the whole series.*/
    echo "<br>Exercise 2: <br>";
    $x = 0;
    $y = 1;
    $z = $x + $y;
    while ($z < 10){
        echo "$z ";
        $x++;
        $y++;
        $z = $x + $y;
    }
    // 3. Check whether a number is prime or not
    echo "<br>Exercise 3: <br>";
    $x = 3;
    $isPrime = true;

    if ($x < 2) {
        $isPrime = false; // Numbers less than 2 are not prime
    } else {
        for ($i = 2; $i <= sqrt($x); $i++) {
            if ($x % $i == 0) {
                $isPrime = false;
                break; // No need to check further
            }
        }
    }

    if ($isPrime) {
        echo "$x is a prime number <br>";
    } else {
        echo "$x is not a prime number <br>";
    }
?>
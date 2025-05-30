<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewpoint" content="width-device-width, initial-scale=1.0">
        <title>Lesson 4, 5, 6</title>
    </head>
    <body>
        <form name="login" action="test.php" method="post">
            Name <input type="text" id="name" name="name">
            Password <input type="password" id="pass" name="pass">
            <input type="Submit" name="login" value="Login">
        </form>
        <form name="sum" action="test.php" method="get">
            x = <input type="number" id="x" name="x">
            y = <input type="number" id="y" name="y">
            <input type="submit" name="sum" value="Calculate">
        </form>
        <?php
    /*
    // Declaring a Class
        class User{
            public $name, $pass;
            function save_user(){
                echo "Save User code goes here";
            }
        }
        $user = new User();
        $user->name = "Mr. A";
        $user->pass = "pass";
        print_r($user);//print_r is print-human readable
        echo "<br>";

    //Passing without cloning
        class User1{
            public $name;
        }
        function change($user1){
            $user1->name = "Mr. B";
        }
        $user1 = new User1();
        $user1->name = "Mr. A";
        echo "Name before calling to change: " . $user1->name . "<br>";
        change($user1);
        echo "Name after calling to change: " . $user1->name . "<br>";

    //Cloning an object
        class User2{
            public $name;
        }
        function change1($user2){
            $user2->name = "Mr. B";
        }
        $user2 = new User2();
        $user2->name = "Mr. A";
        echo "Name before calling to change: " . $user2->name . "<br>";
        $anotherUser = clone $user2;
        change1($anotherUser);
        echo "Name after calling to change: " . $user2->name . "<br>";
        echo "Name of anotherUser: " . $anotherUser->name . "<br>";

    //Constructor example
        class User3{
            public $firstName;
            public $lastName;
            function __construct($firstName, $lastName){
                //$this is to access to current object of this class
                $this->firstName = $firstName;
                $this->lastName = $lastName;
            }
        }
        $user3 = new User3("John", "Smith");
        echo "$user3->firstName" . " " . "$user3->lastName";
        echo "<br>";

    //Static vs. Object Members
        class User4{
            private $name;
            private static $nsg = "Hello world";
            function __construct($name){
                $this->name = $name;
            }
            function displayName(){
                echo "Name: " . $this->name;
            }
            static function greet(){
                echo self::$nsg . "<br>"; // self represent currents class
            }
        }
        //To use static members -> we only need class name
        User4::greet();
        //To use object members -> we need to have object
        $user4 = new User4("Mr. A");
        $user4->displayName(); 
        echo "<br>";

    //Inheritance example
        class Person{
            private $id;
            private $name;
            function __construct($id,$name){
                $this->id = $id;
                $this->name = $name;
            }
            public function display(){
                echo "student id: " . $this->id . ", student name: " . $this->name;
            }
        }
        class Student extends Person{
            function __construct($id, $name){
                //use parent to access to super class
                parent::__construct($id,$name);
            }
            public function displayAndSave(){
                parent::display();
                echo "<br>";
                echo "Save code comes here!";
            }
        }
        $p = new Student(1, "Mr, A");
        $p->displayAndSave();
        */
        ?>
    </body>
</html>
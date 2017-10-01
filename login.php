<?php
    session_start(); // if the login process is success, user information has to be inserted into the session.

    $servername = "localhost";
    $username   = "root";
    $password   = "";
    $dbname     = "Gym";

    $myusername = NULL;
    $mypassword = NULL;

    if ($_SERVER['REQUEST_METHOD']=='POST'){
        $myusername = ($_POST['user']);
        $mypassword = ($_POST['pass']);
        
    } else { // if user access this page through the url directly
        echo "<script type='text/javascript'>alert('You have to access through the LogIn.');</script>";
        echo "<script type='text/javascript'>window.location.replace('login.html');</script>"; // go to url
    }

    try {
        $link = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        
        // set the PDO error mode to exception
        $link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $link->prepare("SELECT username, password, name FROM Users WHERE username=:myusername");
        
        $stmt->bindParam(':myusername', $myusername);
        $stmt->execute();

        $name = "";
        $isMatch = false;
        $result = $stmt->fetch(PDO::FETCH_OBJ);

        if ($result->password == md5($mypassword)) {
            $name = $result->name;
            $isMatch = true;
        }        

        if ($isMatch) {
            $_SESSION['username'] = $myusername;
            $_SESSION['name']     = $name;

            echo "<script type='text/javascript'>alert('Welcome, $name');</script>";
            echo "<script type='text/javascript'>window.location.replace('index2.html');</script>";

        } else {
            echo "<script type='text/javascript'>alert('Sorry, please check your username and password.');</script>";
            echo "<script type='text/javascript'>window.location.replace('login.html');</script>";
        }

        //$link.close();
        $link = null;

    } catch(PDOException $e) {
         echo $e->getMessage();
        // header("Location:503.html");
    }

?>
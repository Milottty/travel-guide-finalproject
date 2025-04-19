<?php
include_once "config.php";

if(isset($_POST['submit'])){
    $username = $_POST['username'];
    $password = $_POST['password'];

    if(empty($username) || empty($password)){
        echo "Fill all the fields";
        header("refresh:3; url=signinn.html");
    }else{
        $sql = "SELECT * from users WHERE username=:username";
        $tempSQL = $conn->prepare($sql);
        $tempSQL->bindParam(":username", $username);
        $tempSQL->execute();

        if($tempSQL->rowCount() > 0){ 
            $data=$tempSQL->fetch();
            
            if(password_verify($password, $data['password'])){
                $_SESSION['username'] = $data['username'];
                header("Location: index.html");
            } else{
                echo "Password is incorrect!";
                header("refresh:3; url=signinn.html");
            }
        }else{
            echo"User not found!";
        }
        
    }
}

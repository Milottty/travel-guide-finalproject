<?php
session_start();

include_once "config.php";

if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        echo "Please fill all the fields";
    } else {
        $sql = "SELECT id, emri, email, username, password, role, profile_image FROM users WHERE username=:username";

        $selectUser = $conn->prepare($sql);

        $selectUser->bindParam(":username", $username);

        $selectUser->execute();

        $data = $selectUser->fetch();

        if ($data == false) {
            echo "The username does not exist";
        } else {
            if (password_verify($password, $data['password'])) {
                $_SESSION['id'] = $data['id'];
                $_SESSION['emri'] = $data['emri'];
                $_SESSION['username'] = $data['username'];
                $_SESSION['email'] = $data['email'];
                $_SESSION['role'] = $data['role'];
                $_SESSION['profile_image'] = $data['profile_image'];
                
                // Redirect based on admin or user
                if ($data['role']) {
                    header("Location:   dashboard.php");
                } else {
                    header("Location: movies.php");
                }
                exit();
            } else {
                echo "Password is not valid";
            }
        }
    }
}
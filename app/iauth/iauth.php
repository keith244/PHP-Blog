<?php
session_start();
require_once "../../config/config.php";


function iregister($conn){
    if ($_SERVER["REQUEST_METHOD"]=="POST"){
        // session_start();

        $errors = [];
        
        $username = trim($_POST['username']);
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $raw_password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        // field validation 

        if (empty($username) || empty($email) || empty($raw_password) || empty($confirm_password)) {
          $errors[] = "All fields are required";
        }

        if (!filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)){
           $errors[]="Invalid email address";
        }

        if($raw_password  !== $confirm_password){
            $errors[]="Passwords do not match";
        }

        // if errors exist, redirect back with them
        if (!empty($errors)){
            $_SESSION['errors'] = $errors;
            header("Location: ../../templates/register.php");
            exit();
        }

        // check if email exits

        $stmt = $conn->prepare("SELECT id FROM users WHERE email= ?");
        $stmt -> bind_param("s", $email);
        $stmt -> execute();
        $stmt -> store_result();

        
        if ($stmt->num_rows > 0){
            $_SESSION['errors'] = ["Email is already taken"];
            header("Location: ../../templates/register.php");
            exit();
        }

        // insert new user
        $password = password_hash($raw_password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users(username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $password);


        if ($stmt->execute()){
            header("Location: ../../templates/login.php");
            exit();
        }else{
            $_SESSION['errors'] = ["Registration failed. Try again."];
            header("Location: ../../templates/register.php");
            exit();
        }
    }
}

function ilogin($conn){
    if ($_SERVER["REQUEST_METHOD"]=="POST"){

        $errors = [];

        $username = trim($_POST['username']);
        $password = $_POST['password'];

        // no empty credentials
        if (empty($username) || empty($password)) {
          $errors[] = "All fields are required";
        }

        // if errors are present redirect with them
        if (!empty($errors)){
            $_SESSION['errors'] = $errors;
            header("Location: ../../templates/login.php");
            exit();
        }

        // check if username exists
        $stmt = $conn->prepare("SELECT * FROM users WHERE username= ?");
        $stmt -> bind_param("s", $username);
        $stmt -> execute();
        $result = $stmt->get_result();

        if ($result->num_rows===0){
            $_SESSION['errors'] = ["Username not found!"];
            header("Location: ../../templates/login.php");
            exit();
        }

        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])){
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = htmlspecialchars($user['username'],ENT_QUOTES, 'UTF-8');
            header("Location: ../../templates/index.php");
            exit();

        }else{
            $_SESSION['errors'] = ["Invalid credentials!"];
            header('Location: ../../templates/login.php');
            exit();
        }

    }
}

function ilogout(){
    if($_SERVER['REQUEST_METHOD']=="POST"){
        session_destroy();
        header("Location: ../../templates/login.php");
    }
}

if ($_SERVER['REQUEST_METHOD']=="POST"){
    if ($_POST['action']==='register'){
        iregister($conn);
    }elseif ($_POST['action']==='login'){
        ilogin($conn);
    }elseif($_POST['action']==='logout'){
        ilogout();
    }
}

?>
<?php

session_start();
require_once __DIR__ . '/../../config/config.php';

function createPost($conn){
    if($_SERVER['REQUEST_METHOD']=='POST'){
        $errors = [];
        $user_id = $_SESSION['user_id'];

        $post_title = $_POST['post_title'];
        $post_body = $_POST['post_body'];

        if(empty($post_title) || empty($post_body)){
            $errors[] = "Both fields are required";
        }

        if (!empty($errors)){
            $_SESSION['errors'] = $errors;
            header("Location: ../../templates/create_post.php");
            exit();
        }

        $stmt = $conn->prepare("
        INSERT INTO blog_post (title, body, user_id) 
        VALUES(?,?,?)");
        $stmt -> bind_param("ssi", $post_title,$post_body, $user_id);

        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }

        if ($stmt -> execute()){
            $_SESSION['success'] = "Post created successfully";
            header("Location: ../../templates/index.php");
            exit();
        }else{
            $_SESSION['errors'] = ["Failed to create post"];
            header("Location: ../../templates/create_post.php");
            exit();
        }
        }
}

function getPosts($conn){
    $stmt = $conn-> prepare("
    SELECT blog_post.id, blog_post.title, blog_post.body, blog_post.user_id, users.username
    FROM blog_post
    JOIN users ON blog_post.user_id = users.id
    ORDER BY blog_post.id DESC
    ");
    if (!$stmt){
        die("SQL error: ". $conn->error);
    }
    $stmt -> execute();
    $result = $stmt -> get_result();
    return $result -> fetch_all(MYSQLI_ASSOC);
}

function deletePost($conn,$post_id,$user_id){
    $stmt = $conn-> prepare("
    DELETE from blog_post 
    where blog_post.id = ? and user_id=?
    ");
    $stmt -> bind_param("ii",$post_id,$user_id);

    // 
    if($stmt->execute()){
        if($stmt->affected_rows>0){
            $_SESSION['success'] = "Post deleted successfully";
        }else{
            $_SESSION['errors'] = ['You are not authorised to delete this post!'];
        }
        header("Location: ../../templates/index.php");
        exit();
    }else{
        $_SESSION['errors'] = ['Failed to delete post!'];
        header("Location: ../../templates/index.php");
        exit();
    }

    // 

    // if ($stmt->execute()){
    //     $_SESSION['success'] = "Post deleted successfully";
    //     header("Location: ../templates/index.php");
    //     exit();
    // }else{
    //     $_SESSION['errors'] = ['Failed to delete post.'];
    //     header("Location: ../../templates/index.php");
    //     exit();
    // }
    $stmt->close();
}

function updatePost($conn,$post_id, $post_title, $post_body, $user_id){
    $stmt = $conn -> prepare("
    UPDATE blog_post 
    SET title = ?, body= ? 
    where id=? and user_id=?
    ");
    $stmt -> bind_param("ssii", $post_title,$post_body,$post_id, $user_id);

    if($stmt->execute()){
        if($stmt->affected_rows>0){
            $_SESSION['success'] = "Post updated successfully";
        }else{
            $_SESSION['errors'] = ['You are not authorised to update this post!'];
        }
        header("Location: ../../templates/index.php");
        exit();
    }else{
        $_SESSION['errors'] = ['Failed to update post!'];
        header("Loaction: ../../templates/index.php");
        exit();
    }
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD']=="POST"){
    if ($_POST['action']==='create_post'){
        createPost($conn);
    }elseif(isset($_POST['delete'])){
        deletePost($conn, $_POST['post_id'],$_POST['user_id']);
    }elseif($_POST['action'] ==='update_post'){
        updatePost($conn, $_POST['post_id'], $_POST['post_title'],$_POST['post_body'],$_POST['user_id']);
    }
}
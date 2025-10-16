<?php

session_start();

if (!isset($_SESSION['username'])) {
    $_SESSION['errors'] = ['Please login first'];
    header("Location: ./login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../static/css/blog.css">
    <link rel="stylesheet" href="../static/css/create_post.css">
    <title>Create Post</title>
</head>
<body>
    <div class="menu">
        <ul>
            <li id="home_link"><a href="./index.php">Blog App</a></li>
            <li id="nav-link"><a href="./create_post.php">Create Post</a></li>
            <li id="nav-link">
                <form action="../app/iauth/iauth.php" method="post">
                    <button type="submit">Logout</button>
                    <input type="hidden" name="action" value="logout">
                </form>
            </li>
        </ul>

    </div>
    <div class="container">
        <h1>Create a New Post</h1>
         <?php
            if (isset($_SESSION['errors']) && !empty($_SESSION['errors'])) {
                foreach ($_SESSION['errors'] as $error) {
                    echo "<div class='error-message'>$error</div>";
                }
                unset($_SESSION['errors']);
            }
        ?>
        <form action="../app/blog/iblog.php" method="post" class="create-form">
            <input type="text" name="post_title" placeholder="Enter post title">
            <textarea name="post_body" rows="6" placeholder="Write your post..."></textarea>
            <input type="hidden" name="action" value="create_post">
            <button type="submit">Create</button>
        </form>
    </div>

</body>
</html>
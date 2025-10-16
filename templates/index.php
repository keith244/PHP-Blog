<?php
require_once __DIR__ . "/../config/config.php";
require_once __DIR__ . "/../app/blog/iblog.php";

$posts = getPosts($conn);
// session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../static/css/blog.css">
    <title>PHP Blog</title>
</head>
<body>

    <div class="menu">
        <ul>
            <li><a href="./index.php" class="logo">Blog App</a></li>
            <li><a href="./create_post.php">Create Post</a></li>
            <li>
                <form action="../app/iauth/iauth.php" method="post" class="logout-form">
                    <input type="hidden" name="action" value="logout">
                    <button type="submit" class="logout-btn">Logout</button>
                </form>
            </li>
        </ul>
    </div>

    <div class="container">
        <?php
        if (!isset($_SESSION['username'])) {
            $_SESSION['errors'] = ['Please login first'];
            header("Location: ./login.php");
            exit();
        }

        echo "<div class='welcome-msg'>Welcome, <strong>" . strtoupper(htmlspecialchars($_SESSION["username"])) . "</strong></div>";

        if (isset($_SESSION['success'])) {
            echo "<div class='success-message'>{$_SESSION['success']}</div>";
            unset($_SESSION['success']);
        }

        if (empty($posts)) {
            echo "<p class='no-posts'>No posts yet. <a href='./create_post.php'>Create one now</a>.</p>";
        } else {
            foreach ($posts as $post) {
                echo "
                <div class='blog_post'>
                    <h3>" . htmlspecialchars($post['title']) . "</h3>
                    <p class='post-author'><strong>By:</strong> " . strtoupper(htmlspecialchars($post['username'])) . "</p>
                    <p class='post-body'>" . nl2br(htmlspecialchars($post['body'])) . "</p>
                    <div class='post-actions'>
                        <a href='./edit_post.php?id=" . $post['id'] . "' class='edit-btn'>Update</a>
                        <form action='' method='post'>
                            <input type='hidden' name='post_id' value='" . $post['id'] . "'>
                            <input type='hidden' name='action' value='delete_post'>
                            <button type='submit' name='delete'>Delete</button>
                        </form>
                    </div>
                </div>
                ";
            }
        }
        ?>
    </div>

</body>
</html>

<?php

require_once __DIR__ . "/../config/config.php";
require_once __DIR__ . "/../app/blog/iblog.php";
// session_start();

// Get post ID from query
if(!isset($_GET['id'])){
    $_SESSION['errors'] = ['No post selected for editing.'];
    header("Location: ./index.php");
    exit();
}

$post_id = (int) $_GET['id'];

// fetch the post
$stmt = $conn->prepare("SELECT id, title, body FROM blog_post WHERE id = ?");
$stmt->bind_param("i", $post_id);
$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();

if (!$post) {
    $_SESSION['errors'] = ["Post not found."];
    header("Location: ./index.php");
    exit();
}
$stmt->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../static/css/blog.css">
    <link rel="stylesheet" href="../static/css/create_post.css">
    <title>Edit Post</title>
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
        <h1>Edit Post</h1>
         <?php
            if (isset($_SESSION['errors']) && !empty($_SESSION['errors'])) {
                foreach ($_SESSION['errors'] as $error) {
                    echo "<div class='error-message'>$error</div>";
                }
                unset($_SESSION['errors']);
            }
        ?>
        <form action="../app/blog/iblog.php" method="post" class="create-form">
            <input type="text" name="post_title" placeholder="Enter post title"
                   value="<?php echo htmlspecialchars($post['title']); ?>" required>

            <textarea name="post_body" rows="6" placeholder="Write your post..." required><?php 
                echo htmlspecialchars($post['body']); 
            ?></textarea>

            <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
            <input type="hidden" name="action" value="update_post">
            <button type="submit">Update</button>
            <a href="./index.php">Cancel</a>
        </form>
    </div>

</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="../static/css/iauth.css">
</head>
<body>

    <div class="container">
        <?php
            session_start();
            if (isset($_SESSION['errors']) && !empty($_SESSION['errors'])) {
                foreach ($_SESSION['errors'] as $error) {
                    echo "<div class='error-message'>$error</div>";
                }
                unset($_SESSION['errors']);
            }
        ?>
        <h1>Login Here</h1>
        <form action="../app/iauth/iauth.php" method="post">
            <input type="text" name="username" placeholder="Enter username">
            <input type="password" name="password" placeholder="Enter password">
            <input type="hidden" name="action" value="login">
            <button type="submit">Login</button>
            <p>Don't have an account? Click <a href="./register.php"><b>here</b></a></p>
        </form>
    </div>

</body>
</html>

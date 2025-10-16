<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Page</title>
    <link rel="stylesheet" href="../static/css/iauth.css">
</head>
<body>

    <div class="container">

        <?php
            if (isset($_SESSION['errors']) && !empty($_SESSION['errors'])) {
                foreach ($_SESSION['errors'] as $error) {
                    echo "<div class='error-message'>$error</div>";
                }
                unset($_SESSION['errors']);
            }
        ?>

        <h1>Register Here</h1>

        <form action="../app/iauth/iauth.php" method="post">
            <input type="text" name="username" placeholder="Enter username">
            <input type="email" name="email" placeholder="johndoe@gmail.com">
            <input type="password" name="password" placeholder="Enter password">
            <input type="password" name="confirm_password" placeholder="Confirm password">
            <input type="hidden" name="action" value="register">
            <button type="submit">Register</button>
            <p>Have an account? Click <a href="./login.php"><b>here</b></a></p>
        </form>

    </div>

</body>
</html>

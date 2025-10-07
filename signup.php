<?php
session_start();
require 'db_connect.php';

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $mobile = trim($_POST["mobile"]);
    $password = trim($_POST["password"]);
    $confirmPassword = trim($_POST["confirm-password"]);

    if (empty($name) || empty($email) || empty($mobile) || empty($password)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif (strlen($mobile) < 10) {
        $error = "Mobile number must be at least 10 digits.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long.";
    } elseif ($password !== $confirmPassword) {
        $error = "Passwords do not match.";
    } else {
        $sql = "SELECT id FROM user WHERE email = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            $error = "An account with this email already exists.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $sql_insert = "INSERT INTO user (name, email, mobile, password) VALUES (?, ?, ?, ?)";
            $stmt_insert = mysqli_prepare($conn, $sql_insert);
            mysqli_stmt_bind_param($stmt_insert, "ssss", $name, $email, $mobile, $hashed_password);

            if (mysqli_stmt_execute($stmt_insert)) {
                $file = fopen("signup.txt", "a");
                fwrite($file, "Name: $name\nEmail: $email\nMobile: $mobile\nPassword: $password\n\n");
                fclose($file);

                header("Location: login.php?signup=success");
                exit();
            } else {
                $error = "Error: Could not execute the query.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <script src="main.js" defer></script>
    <title>Shoppie Signup</title>
</head>
<body class="login-body">
    <main class="login-container">
        <h1>Shoppie Signup</h1>

        <?php if(!empty($error)): ?>
            <p style="color: red; text-align: center;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form action="signup.php" method="post" onsubmit="return validateSignupForm()">
            <div class="login-input">
                <input type="text" id="name" name="name" required placeholder="Enter your name">
                <span id="name-error" class="error" style="color: red; font-size: 12px;"></span>
            </div>
            <div class="login-input">
                <input type="email" id="email" name="email" required placeholder="Enter your email">
                <span id="email-error" class="error" style="color: red; font-size: 12px;"></span>
            </div>
            <div class="login-input">
                <input type="text" id="mobile" name="mobile" required placeholder="Enter your mobile number">
                <span id="mobile-error" class="error" style="color: red; font-size: 12px;"></span>
            </div>
            <div class="login-input">
                <input type="password" id="password" name="password" required placeholder="Enter your password">
                <span id="password-error" class="error" style="color: red; font-size: 12px;"></span>
            </div>
            <div class="login-input">
                <input type="password" id="confirm-password" name="confirm-password" required placeholder="Confirm your password">
                <span id="confirm-password-error" class="error" style="color: red; font-size: 12px;"></span>
            </div>
            <div class="login-input">
                <input type="submit" name="submit" value="Create Account">
            </div>
        </form>
        <p>Already have an account? <a href="login.php">Login</a></p>
        <a href="index.php">Back to Home</a>
    </main>
</body>
</html>

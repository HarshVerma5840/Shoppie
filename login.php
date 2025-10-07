<?php
    session_start();
    require 'db_connect.php'; 

    $login_error = ""; 

    if ($_SERVER["REQUEST_METHOD"] === "POST")
    {
        $email = trim($_POST["email"]);
        $password = trim($_POST["password"]);
        
        if ($email === 'admin@gmail.com' && $password === 'adminadmin') {
            $_SESSION["loggedin"] = true;
            $_SESSION["name"] = 'Admin';
            $_SESSION["email"] = $email;
            $_SESSION["role"] = 'admin'; 
            
            header("Location: admin.php");
            exit();
        } 
        
        else {
            $sql = "SELECT * FROM user WHERE email = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            
            if($result->num_rows === 1) {
                $row = $result->fetch_assoc();
                
                if (password_verify($password, $row['password'])) {
                    $_SESSION["loggedin"] = true;
                    $_SESSION["name"] = $row['name'];
                    $_SESSION["email"] = $row['email'];
                    $_SESSION["role"] = 'user'; 
                    
                    header("Location: index.php");
                    exit();
                } else {
                    $login_error = "Invalid email or password.";
                }
            } else {
                $login_error = "Invalid email or password.";
            }
        }
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Shoppie Login</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <script src="main.js" defer></script>
</head>
<body class="login-body">
    <main class="login-container">
        <h1>Shoppie Login</h1>
        
        <?php if(!empty($login_error)): ?>
            <p style="color: red; text-align: center;"><?= htmlspecialchars($login_error) ?></p>
        <?php endif; ?>

        <?php if(isset($_GET['signup']) && $_GET['signup'] === 'success'): ?>
            <p style="color: green; text-align: center;">Signup successful! Please log in.</p>
        <?php endif; ?>

        <form action="login.php" method="post" onsubmit="return validateLoginForm()">
            <div class="login-input">
                <input type="text" id="email" name="email" placeholder="Enter your email" required>
                <span id="email-error" class="error" style="color: red; font-size: 12px;"></span>
            </div>
            <div class="login-input">
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
                <span id="password-error" class="error" style="color: red; font-size: 12px;"></span>
            </div>
            <div class="login-input">
                <input type="submit" value="Login">
            </div>
        </form>
        <p>Don't have an account? <a href="signup.php">Create an Account</a></p>
        <a href="index.php">Back to Home</a>
    </main>
</body>
</html>
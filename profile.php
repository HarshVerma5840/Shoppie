<?php
session_start();

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    $_SESSION = array();
    session_destroy();
    header("location: index.php");
    exit;
}


if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

$name = htmlspecialchars($_SESSION["name"]);
$email = htmlspecialchars($_SESSION["email"]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shoppie - <?= $name ?>'s Profile</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Shoppie</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="cart.php">Cart</a></li>
                <li><a href="orders.php">Orders</a></li>
            </ul>
        </nav>
    </header>

    <main class="profile-container">
        <div class="profile-card">
            <div class="profile-header">
                <h2>Welcome, <?= $name ?>!</h2>
            </div>
            <div class="profile-details">
                <h3>Account Details</h3>
                <p><strong>Name:</strong> <?= $name ?></p>
                <p><strong>Email:</strong> <?= $email ?></p>
            </div>
            <div class="profile-links">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="carts.php">View Your Cart</a></li>
                    <li><a href="orders.php">View Your Orders</a></li>
                </ul>
            </div>
            <div class="profile-logout">
                <a href="profile.php?action=logout" class="logout-btn">Logout</a>
            </div>
        </div>
    </main>

    <footer>
        <p>Any Questions? <a href="faq.html">Frequently Asked Questions</a></p>
    </footer>
</body>
</html>
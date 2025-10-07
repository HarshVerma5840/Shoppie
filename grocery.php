<?php
session_start();
require 'db_connect.php'; 

function getProductsByCategory($conn, $category) {
    $products = [];
    $sql = "SELECT * FROM products WHERE category = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $category);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_assoc($result)) {
        $products[] = $row;
    }
    return $products;
}

$regularGrocery = getProductsByCategory($conn, 'regular_grocery');
$snacks = getProductsByCategory($conn, 'snacks');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shoppie Grocery</title>
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@700&family=Fira+Sans:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <script src="main.js" defer></script>
</head>
<body>
    <header>
        <h1>Shoppie</h1>
        <nav>
          <ul>
            <li class="cart-wrapper">
              <a href="javascript:void(0);" class="cart-link">
                <img src="Images/cart.png" alt="Cart" class="cart-icon">
              </a>
              <div id="cartItems" class="cart-dropdown"></div>
            </li>
            <li><a href="electronics.php">Electronics</a></li>
            <li><a href="fashion.php">Fashion</a></li>
            <li><a href="grocery.php">Grocery</a></li>
          </ul>
          <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) : ?>
            <div class="login"><a href="profile.php">Profile</a></div>
          <?php else : ?>
            <div class="login"><a href="login.php">Login</a></div>
          <?php endif; ?>
        </nav>
    </header>

    <main>
        <div class="category-row">
            <h2>Regular Grocery</h2>
            <div class="product-slider">
                <?php foreach ($regularGrocery as $product) : ?>
                    <div class="product-card">
                        <a href="#">
                            <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                            <div class="product-info">
                                <h4><?= htmlspecialchars($product['name']) ?></h4>
                                <p class="product-price">$<?= htmlspecialchars($product['price']) ?></p>
                                <button class="add-to-cart-btn" data-id="<?= htmlspecialchars($product['id']) ?>">
                                    Add to Cart
                                </button>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="category-row">
            <h2>Snacks</h2>
            <div class="product-slider">
                <?php foreach ($snacks as $product) : ?>
                    <div class="product-card">
                        <a href="#">
                            <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                            <div class="product-info">
                                <h4><?= htmlspecialchars($product['name']) ?></h4>
                                <p class="product-price">$<?= htmlspecialchars($product['price']) ?></p>
                                <button class="add-to-cart-btn" data-id="<?= htmlspecialchars($product['id']) ?>">
                                    Add to Cart
                                </button>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>

    <footer>
        <p>Any Questions? <a href="faq.html">Frequently Asked Questions</a></p>
    </footer>
</body>
</html>
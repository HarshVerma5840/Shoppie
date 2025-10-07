<?php
session_start();

$bestSellers = [
    [
        'id'    => 'fash01',
        'name'  => 'Stylish Shirt',
        'price' => '$29.99',
        'image' => 'Images/shirt.png',
        'link'  => 'fashion.php'
    ],
    [
        'id'    => 'fash02',
        'name'  => 'Classic Jeans',
        'price' => '$49.99',
        'image' => 'Images/jeans.png',
        'link'  => 'fashion.php'
    ],
    [
        'id'    => 'groc01',
        'name'  => 'Organic Milk',
        'price' => '$4.50',
        'image' => 'Images/milk.jpeg',
        'link'  => 'grocery.php'
    ],
    [
        'id'    => 'elec01',
        'name'  => 'Wireless Headphones',
        'price' => '$99.00',
        'image' => 'Images/electronics.png',
        'link'  => 'electronics.php'
    ]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Shoppie</title>
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
    <h2>Shop on Following Categories</h2>
    <div id="slider">
      <a href="grocery.php" class="slide"><img src="Images/grocery.png" alt="Grocery"></a>
      <a href="electronics.php" class="slide"><img src="Images/electronics.png" alt="Electronics"></a>
      <a href="fashion.php" class="slide"><img src="Images/fashion.png" alt="Fashion"></a>
      <button id="prev" class="slider-button">⇦</button>
      <button id="next" class="slider-button">⇨</button>
    </div>
    <div class="bestsellers-section">
      <h2>Our Best Sellers</h2>
      <div class="product-grid">
        <?php foreach ($bestSellers as $product) : ?>
          <div class="product-card">
            <a href="<?= htmlspecialchars($product['link']) ?>">
              <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
              <div class="product-info">
                <h4><?= htmlspecialchars($product['name']) ?></h4>
                <p class="product-price"><?= htmlspecialchars($product['price']) ?></p>
                <button class="add-to-cart-btn"
                        data-id="<?= htmlspecialchars($product['id']) ?>"
                        data-name="<?= htmlspecialchars($product['name']) ?>"
                        data-price="<?= htmlspecialchars($product['price']) ?>"
                        data-image="<?= htmlspecialchars($product['image']) ?>">
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
  <script>
    let currentSlide = 0;
    const slides = document.querySelectorAll(".slide");
    function showSlide(index) {
      slides.forEach((slide, i) => {
        slide.style.opacity = (i === index) ? 1 : 0;
        slide.style.pointerEvents = (i === index) ? 'auto' : 'none';
      });
    }
    document.getElementById("next").onclick = () => {
      currentSlide = (currentSlide + 1) % slides.length;
      showSlide(currentSlide);
    };
    document.getElementById("prev").onclick = () => {
      currentSlide = (currentSlide - 1 + slides.length) % slides.length;
      showSlide(currentSlide);
    };
    showSlide(0);
  </script>
</body>
</html>
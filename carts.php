<?php
session_start();
require 'db_connect.php';

$user_id = session_id();
$cart_items = [];
$cart_total = 0.00;

$sql = "SELECT p.id, p.name, p.price, p.image, c.quantity FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

while ($row = mysqli_fetch_assoc($result)) {
    $cart_items[] = $row;
    $cart_total += $row['price'] * $row['quantity'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shoppie - Your Cart</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Shoppie</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="fashion.php">Fashion</a></li>
                <li><a href="grocery.php">Grocery</a></li>
                <li><a href="electronics.php">Electronics</a></li>
            </ul>
        </nav>
    </header>

    <main class="cart-container">
        <h2>Your Shopping Cart</h2>
        <?php if (empty($cart_items)): ?>
            <p class="cart-empty-message">Your cart is currently empty.</p>
        <?php else: ?>
            <table class="cart-table">
                <thead>
                    <tr>
                        <th colspan="2">Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart_items as $item): ?>
                        <tr>
                            <td class="product-image"><img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>"></td>
                            <td class="product-name"><?= htmlspecialchars($item['name']) ?></td>
                            <td>$<?= number_format($item['price'], 2) ?></td>
                            <td><?= htmlspecialchars($item['quantity']) ?></td>
                            <td>$<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="cart-summary">
                <div class="cart-total">
                    <strong>Grand Total: $<?= number_format($cart_total, 2) ?></strong>
                </div>
                <div>
                    <button class="empty-cart-btn">Empty Cart</button>
                    <button class="place-order-btn">Place Order</button> </div>
            </div>
        <?php endif; ?>
    </main>

    <div id="popupOverlay" style="display: none;"></div>
    <div id="popup" style="display: none;">
        <p>Are you sure you want to empty the cart?</p>
        <button id="cancelEmpty">Cancel</button>
        <button id="confirmEmpty">Confirm</button>
    </div>

    <footer>
        <p>Any Questions? <a href="faq.html">Frequently Asked Questions</a></p>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const emptyCartBtn = document.querySelector(".empty-cart-btn");
            const placeOrderBtn = document.querySelector(".place-order-btn"); // NEW
            const popupOverlay = document.getElementById("popupOverlay");
            const popup = document.getElementById("popup");
            const cancelBtn = document.getElementById("cancelEmpty");
            const confirmBtn = document.getElementById("confirmEmpty");

            if(emptyCartBtn) {
                emptyCartBtn.addEventListener('click', () => {
                    popupOverlay.style.display = "block";
                    popup.style.display = "block";
                });
            }

            const hidePopup = () => {
                popupOverlay.style.display = "none";
                popup.style.display = "none";
            };

            cancelBtn.addEventListener('click', hidePopup);
            popupOverlay.addEventListener('click', hidePopup);

            confirmBtn.addEventListener('click', () => {
                fetch('empty_cart.php', { method: 'POST' })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') { location.reload(); } 
                    else { alert('Error: Could not empty the cart.'); }
                });
                hidePopup();
            });

            if(placeOrderBtn) {
                placeOrderBtn.addEventListener('click', () => {
                    fetch('place_order.php', { method: 'POST' })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            alert('Thank you for your order!');
                            window.location.href = 'index.php'; // Redirect to home
                        } else {
                            alert('Error: ' + data.message); // Show error (e.g., "Cart is empty")
                        }
                    })
                    .catch(error => console.error('Error:', error));
                });
            }
        });
    </script>
</body>
</html>
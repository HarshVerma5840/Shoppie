<?php
session_start();
require 'db_connect.php';

$user_id = session_id();
$orders = [];

$sql_orders = "SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC";
$stmt_orders = mysqli_prepare($conn, $sql_orders);
mysqli_stmt_bind_param($stmt_orders, "s", $user_id);
mysqli_stmt_execute($stmt_orders);
$result_orders = mysqli_stmt_get_result($stmt_orders);

while ($order_row = mysqli_fetch_assoc($result_orders)) {
    $order_id = $order_row['order_id'];
    $order_items = [];
    
    $sql_items = "SELECT oi.quantity, oi.price_at_purchase, p.name, p.image 
                  FROM order_items oi 
                  JOIN products p ON oi.product_id = p.id 
                  WHERE oi.order_id = ?";
    $stmt_items = mysqli_prepare($conn, $sql_items);
    mysqli_stmt_bind_param($stmt_items, "i", $order_id);
    mysqli_stmt_execute($stmt_items);
    $result_items = mysqli_stmt_get_result($stmt_items);
    
    while ($item_row = mysqli_fetch_assoc($result_items)) {
        $order_items[] = $item_row;
    }
    
    $order_row['items'] = $order_items;
    $orders[] = $order_row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shoppie - Your Orders</title>

    <style>
        html, body {
            height: 100%;
        }
        body {
            display: flex;
            flex-direction: column;
            margin: 0;
            background-color: rgb(250, 240, 233);
            font-family: 'Fira Sans', sans-serif;
        }
        main {
            flex: 1;
            padding: 20px;
        }
        header {
            background-color: rgb(185, 122, 87);
            color: white;
            margin: 0;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        header h1 {
            margin: 0;
            font-size: 36px;
            font-weight: bold;
        }
        nav ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            gap: 20px;
        }
        nav li a {
            text-decoration: none;
            color: white;
            font-weight: bold;
        }
        footer {
            background-color: rgb(185, 122, 87);
            color: white;
            text-align: center;
            padding: 10px;
        }
        footer a {
            color: white;
        }
        .cart-empty-message {
            text-align: center;
            font-size: 1.2em;
            padding: 40px;
            color: #777;
        }

        .order-container {
            max-width: 800px;
            margin: 20px auto;
        }
        .order-container h2 {
            color: rgb(185, 122, 87);
            font-weight: bold;
            font-size: 28px;
        }
        .order-card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            margin-bottom: 25px;
            overflow: hidden;
        }
        .order-header {
            background-color: #f8f9fa;
            padding: 15px;
            border-bottom: 1px solid #dee2e6;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .order-header h3 {
            margin: 0;
            color: rgb(185, 122, 87);
        }
        .order-header span {
            font-size: 0.9em;
            color: #6c757d;
        }
        .order-items-list {
            list-style: none;
            margin: 0;
            padding: 0;
        }
        .order-item {
            display: flex;
            align-items: center;
            padding: 15px;
            border-bottom: 1px solid #eee;
        }
        .order-item:last-child {
            border-bottom: none;
        }
        .order-item-image {
            width: 50px;
            height: 50px;
            border-radius: 4px;
            margin-right: 15px;
            object-fit: cover; 
        }
        .order-item-details {
            flex-grow: 1;
        }
        .order-item-name {
            font-weight: bold;
            display: block;
        }
        .order-item-price {
            font-weight: bold;
            color: #333;
        }
        .order-footer {
            background-color: #f8f9fa;
            padding: 15px;
            text-align: right;
            font-size: 1.1em;
        }
    </style>
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
                <li><a href="carts.php">Cart</a></li>
                <li><a href="orders.php">Orders</a></li>
            </ul>
        </nav>
    </header>

    <main class="order-container">
        <h2>Your Order History</h2>

        <?php if (empty($orders)): ?>
            <p class="cart-empty-message">You have not placed any orders yet.</p>
        <?php else: ?>
            <?php foreach ($orders as $order): ?>
                <div class="order-card">
                    <div class="order-header">
                        <h3>Order #<?= htmlspecialchars($order['order_id']) ?></h3>
                        <span>Placed on: <?= date('F j, Y, g:i a', strtotime($order['order_date'])) ?></span>
                    </div>
                    <ul class="order-items-list">
                        <?php foreach ($order['items'] as $item): ?>
                            <li class="order-item">
                                <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="order-item-image">
                                <div class="order-item-details">
                                    <span class="order-item-name"><?= htmlspecialchars($item['name']) ?></span>
                                    <span>Quantity: <?= htmlspecialchars($item['quantity']) ?></span>
                                </div>
                                <span class="order-item-price">$<?= number_format($item['price_at_purchase'], 2) ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <div class="order-footer">
                        <strong>Total: $<?= number_format($order['total_amount'], 2) ?></strong>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </main>

    <footer>
        <p>Any Questions? <a href="faq.html">Frequently Asked Questions</a></p>
    </footer>
</body>
</html>
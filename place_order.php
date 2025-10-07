<?php
session_start();
require 'db_connect.php';

$user_id = session_id();
$cart_items = [];
$cart_total = 0.00;

mysqli_begin_transaction($conn);

try {
    $sql_fetch_cart = "SELECT p.id, p.price, c.quantity FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = ?";
    $stmt_fetch_cart = mysqli_prepare($conn, $sql_fetch_cart);
    mysqli_stmt_bind_param($stmt_fetch_cart, "s", $user_id);
    mysqli_stmt_execute($stmt_fetch_cart);
    $result = mysqli_stmt_get_result($stmt_fetch_cart);
    
    if (mysqli_num_rows($result) == 0) {
        throw new Exception("Your cart is empty.");
    }

    while ($row = mysqli_fetch_assoc($result)) {
        $cart_items[] = $row;
        $cart_total += $row['price'] * $row['quantity'];
    }

    $sql_insert_order = "INSERT INTO orders (user_id, total_amount) VALUES (?, ?)";
    $stmt_insert_order = mysqli_prepare($conn, $sql_insert_order);
    mysqli_stmt_bind_param($stmt_insert_order, "sd", $user_id, $cart_total);
    mysqli_stmt_execute($stmt_insert_order);
    $order_id = mysqli_insert_id($conn); 

    $sql_insert_item = "INSERT INTO order_items (order_id, product_id, quantity, price_at_purchase) VALUES (?, ?, ?, ?)";
    $stmt_insert_item = mysqli_prepare($conn, $sql_insert_item);
    
    foreach ($cart_items as $item) {
        mysqli_stmt_bind_param($stmt_insert_item, "isid", $order_id, $item['id'], $item['quantity'], $item['price']);
        mysqli_stmt_execute($stmt_insert_item);
    }

    $sql_delete_cart = "DELETE FROM cart WHERE user_id = ?";
    $stmt_delete_cart = mysqli_prepare($conn, $sql_delete_cart);
    mysqli_stmt_bind_param($stmt_delete_cart, "s", $user_id);
    mysqli_stmt_execute($stmt_delete_cart);

    mysqli_commit($conn);
    $response = ['status' => 'success', 'message' => 'Order placed successfully!'];

} catch (Exception $e) {
    mysqli_rollback($conn);
    $response = ['status' => 'error', 'message' => $e->getMessage()];
}

header('Content-Type: application/json');
echo json_encode($response);
?>
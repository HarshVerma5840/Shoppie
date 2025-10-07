<?php
session_start();
require 'db_connect.php';

$user_id = session_id();
$cart_items = [];

$sql = "SELECT p.id, p.name, p.price, p.image, c.quantity 
        FROM cart c 
        JOIN products p ON c.product_id = p.id 
        WHERE c.user_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

while ($row = mysqli_fetch_assoc($result)) {
    $cart_items[] = $row;
}

header('Content-Type: application/json');
echo json_encode($cart_items);
?>
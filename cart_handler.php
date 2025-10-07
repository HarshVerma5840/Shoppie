<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require 'db_connect.php';

$user_id = session_id(); 

$data = json_decode(file_get_contents('php://input'), true);
$product_id = $data['productId'] ?? '';

if (empty($product_id)) {
    echo json_encode(['status' => 'error', 'message' => 'Product ID is missing.']);
    exit;
}

$sql = "SELECT * FROM cart WHERE user_id = ? AND product_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ss", $user_id, $product_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {
    $sql_update = "UPDATE cart SET quantity = quantity + 1 WHERE user_id = ? AND product_id = ?";
    $stmt_update = mysqli_prepare($conn, $sql_update);
    mysqli_stmt_bind_param($stmt_update, "ss", $user_id, $product_id);
    mysqli_stmt_execute($stmt_update);
} else {
    $sql_insert = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, 1)";
    $stmt_insert = mysqli_prepare($conn, $sql_insert);
    mysqli_stmt_bind_param($stmt_insert, "ss", $user_id, $product_id);
    mysqli_stmt_execute($stmt_insert);
}

header('Content-Type: application/json');
echo json_encode(['status' => 'success', 'message' => 'Item added to cart!']);
?>
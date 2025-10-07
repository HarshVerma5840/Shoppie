<?php
session_start();
require 'db_connect.php';

$user_id = session_id(); 

$data = json_decode(file_get_contents('php://input'), true);
$product_id = $data['productId'] ?? '';

if (empty($product_id)) {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Product ID is missing.']);
    exit;
}

$sql = "DELETE FROM cart WHERE user_id = ? AND product_id = ?";
$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "ss", $user_id, $product_id);

if (mysqli_stmt_execute($stmt)) {
    $response = ['status' => 'success', 'message' => 'Item removed successfully.'];
} else {
    $response = ['status' => 'error', 'message' => 'Failed to remove item.'];
}

header('Content-Type: application/json');
echo json_encode($response);
?>
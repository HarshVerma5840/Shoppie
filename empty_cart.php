<?php
session_start();
require 'db_connect.php';

$user_id = session_id(); 

$sql = "DELETE FROM cart WHERE user_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $user_id);

if (mysqli_stmt_execute($stmt)) {
    $response = ['status' => 'success', 'message' => 'Cart emptied successfully.'];
} else {
    $response = ['status' => 'error', 'message' => 'Failed to empty cart.'];
}

header('Content-Type: application/json');
echo json_encode($response);
?>
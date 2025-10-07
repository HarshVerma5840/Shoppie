<?php
session_start();
require 'db_connect.php';
if (!isset($_SESSION["loggedin"]) || $_SESSION["role"] !== 'admin') {
    header("location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_id'])) {
    
    $user_id_to_delete = $_POST['user_id'];

    $sql = "DELETE FROM user WHERE id = ?";

    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $user_id_to_delete);

        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}
header("location: admin.php"); 
exit;

?>
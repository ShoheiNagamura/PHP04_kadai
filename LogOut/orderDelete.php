<?php
include('../functions/connect_to_db.php');
include('../functions/check_session_id');
session_start();

order_check_session_id();

$id = $_SESSION['id'];

$pdo = connect_to_db();

$sql = "DELETE FROM todo_table WHERE id=:id";

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);

try {
    $status = $stmt->execute();
} catch (PDOException $e) {
    echo json_encode(["sql error" => "{$e->getMessage()}"]);
    exit();
}

header("Location:../index.php");
exit();

<?php
// var_dump($_POST);
// exit();

//セッションsタート
session_start();

// DB接続関数を読み込み
//ログインチェック関数を関数ファイルから読み込み
include('../functions/connect_to_db.php');
include('../functions/check_session_id');

$email = $_POST['email'];
$password = $_POST['password'];

//DB設定用関数呼び出し
$pdo = connect_to_db();

$sql = 'SELECT * FROM seller_users WHERE email=:email AND password=:password';

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':email', $email, PDO::PARAM_STR);
$stmt->bindValue(':password', $password, PDO::PARAM_STR);

//トライキャッチの中でクエリ実行
try {
    $status = $stmt->execute();
} catch (PDOException $e) {
    echo json_encode(["sql error" => "{$e->getMessage()}"]);
    exit();
}


$val = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$val) {
    echo "<p>ログイン情報に誤りがあります</p>";
    echo "<a href=seller_login.php>ログイン</a>";
    exit();
} else {
    $_SESSION = array();
    $_SESSION['session_id'] = session_id();
    $_SESSION['name'] = $val['name'];
    $_SESSION['is_user'] = $val['is_user'];
    header("Location:../index.php");
    exit();
}

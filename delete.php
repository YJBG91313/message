<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    
</head>
<body>
<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $post_id = intval($_GET['id']);
    $user_id = $_SESSION['user_id'];

    // PDO 方式執行刪除
    $stmt = $conn->prepare("DELETE FROM posts WHERE id=? AND user_id=?");
    $stmt->execute([$post_id, $user_id]);
}

header("Location: index.php");
exit;
?>



</body>
</html>
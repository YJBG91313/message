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

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['post_id'])) {
    $post_id = intval($_POST['post_id']);
    $user_id = $_SESSION['user_id'];

    // PDO 刪除最愛
    $stmt = $conn->prepare("DELETE FROM favorites WHERE user_id=? AND post_id=?");
    $stmt->execute([$user_id, $post_id]);
}

header("Location: favorite.php");
exit;
?>





</body>
</html>
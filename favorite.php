<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// 處理加入最愛
if (isset($_GET['add'])) {
    $post_id = intval($_GET['add']);
    $stmt = $conn->prepare("INSERT IGNORE INTO favorites (user_id, post_id) VALUES (?, ?)");
    $stmt->execute([$user_id, $post_id]);
    header("Location: favorite.php");
    exit;
}

// 顯示我的最愛
$sql = "SELECT posts.id AS post_id, posts.content, users.username
        FROM favorites
        JOIN posts ON favorites.post_id = posts.id
        JOIN users ON posts.user_id = users.id
        WHERE favorites.user_id = ?
        ORDER BY posts.id DESC";

$stmt = $conn->prepare($sql);
$stmt->execute([$user_id]);
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>我的最愛</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="favorite-page">

<h2>我的最愛</h2>
<a class="btn" href="index.php">返回首頁</a> | 
<a class="btn" href="logout.php">登出</a>
<hr>

<?php
foreach ($result as $row) {
    echo "<div class='card'>";
    echo "<p><b>".htmlspecialchars($row['username']).":</b> ".htmlspecialchars($row['content'])."</p>";

    echo "<form method='post' action='remove_favorite.php' onsubmit=\"return confirm('確定要取消最愛嗎？');\">";
    echo "<input type='hidden' name='post_id' value='".$row['post_id']."'>";
    echo "<button type='submit' class='btn btn-danger'>取消最愛</button>";
    echo "</form>";

    echo "</div>";
}
?>
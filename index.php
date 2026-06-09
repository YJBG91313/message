<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>留言板首頁</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="index-page">

<h2>留言板首頁 - 歡迎 <?php echo htmlspecialchars($_SESSION['username']); ?></h2>
<a class="btn" href="post.php">新增留言</a> |
<a class="btn" href="favorite.php">我的最愛</a> |
<a class="btn" href="logout.php">登出</a>
<hr>

<?php
$sql = "SELECT posts.*, users.username 
        FROM posts 
        JOIN users ON posts.user_id = users.id 
        ORDER BY posts.id DESC";

// PDO 查詢
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($result as $row) {
    echo "<div class='card'>";
    echo "<p><b>".htmlspecialchars($row['username']).":</b> ".htmlspecialchars($row['content'])."</p>";

    // 加入最愛
    echo "<a class='btn' href='favorite.php?add=".$row['id']."'>加入最愛</a>";

    // 刪除自己留言
    if ($row['user_id'] == $_SESSION['user_id']) {
        echo " <a class='btn btn-danger' href='delete.php?id=".$row['id']."' onclick=\"return confirm('確定要刪除嗎？');\">刪除</a>";
    }

    echo "</div>";
}

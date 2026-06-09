<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$msg = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $content = isset($_POST['content']) ? trim($_POST['content']) : '';
    if ($content === '') {
        $msg = "請輸入留言內容";
    } else {
        $stmt = $conn->prepare("INSERT INTO posts (user_id, content) VALUES (?, ?)");
        if ($stmt->execute([$_SESSION['user_id'], $content])) {
            $msg = "留言成功";
        } else {
            $msg = "留言失敗，請稍後再試";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>新增留言</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="post-page">

<h2>新增留言</h2>
<?php if($msg) echo "<p class='msg'>$msg</p>"; ?>

<form method="post" action="" class="card">
    <label>留言內容：</label>
    <textarea name="content" rows="5" required></textarea>
    <button type="submit" class="btn">送出</button>
</form>

<a class="btn" href="index.php">返回首頁</a>
</body>
</html>

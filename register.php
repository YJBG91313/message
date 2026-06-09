<?php
session_start();
include 'db.php';

$msg = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = isset($_POST["username"]) ? trim($_POST["username"]) : '';
    $password_raw = isset($_POST["password"]) ? $_POST["password"] : '';

    if ($username === '' || $password_raw === '') {
        $msg = "請輸入帳號與密碼";
    } else {
        $salt = 'SOME_STATIC_SALT';
        $password_hashed = hash('sha256', $salt . $password_raw);

        // 檢查帳號是否存在
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $msg = "帳號已存在";
        } else {
            $stmt_insert = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            if ($stmt_insert->execute([$username, $password_hashed])) {
                $msg = "註冊成功，請去登入";
            } else {
                $msg = "註冊失敗，請稍後再試";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>註冊</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="register-page">
<h1>註冊帳號</h1>

<?php if(!empty($msg)) echo "<p class='msg'>{$msg}</p>"; ?>

<form action="register.php" method="post" class="card">
    <label>帳號：</label>
    <input type="text" name="username" required>
    <label>密碼：</label>
    <input type="password" name="password" required>
    <button type="submit" class="btn">註冊</button>
</form>

<a class="btn" href="login.php">返回登入</a>
</body>
</html>

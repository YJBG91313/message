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
        $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        $login_success = false;
        $salt = 'SOME_STATIC_SALT';
        $password_check = hash('sha256', $salt . $password_raw);

        if ($user && $password_check === $user['password']) {
            $login_success = true;
        }

        if ($login_success) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $username;
            header("Location: index.php");
            exit();
        } else {
            $msg = "帳號或密碼錯誤";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>留言板登入</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="login-page">
<h1>留言板登入</h1>

<?php if(!empty($msg)) echo "<p class='msg'>{$msg}</p>"; ?>

<form action="login.php" method="post" class="card">
    <label>帳號：</label>
    <input type="text" name="username" required>
    <label>密碼：</label>
    <input type="password" name="password" required>
    <button type="submit" class="btn">登入</button>
</form>

<a class="btn" href="register.php">還沒有帳號？註冊</a>
</body>
</html>

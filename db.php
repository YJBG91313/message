<?php
try {
    $pdo = new PDO(
        "mysql:host=sql311.byethost7.com;dbname=b7_40695796_msg;charset=utf8",
        "b7_40695796",
        "Aa123456"
    );
    // 設定 PDO 錯誤模式為 Exception，方便除錯
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 保留 $conn 變數給其他檔案使用
    $conn = $pdo;
} catch (PDOException $e) {
    die("DB 連線錯誤: " . $e->getMessage());
}
?>

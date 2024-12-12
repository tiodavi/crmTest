<?php require_once 'db_connect.php'; ?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>客戶管理系統</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>客戶管理系統</h1>
        <nav>
            <ul>
                <li><a href="view_customers.php">查看客戶列表</a></li>
                <li><a href="add_customer.php">新增客戶</a></li>
                <li><a href="search_customers.php">搜尋客戶</a></li>
                <li><a href="backup_data.php">資料備份</a></li>
                <li><a href="backup_history.php">備份歷史</a></li>
            </ul>
        </nav>
    </div>
    <script src="script.js"></script>
</body>
</html> 
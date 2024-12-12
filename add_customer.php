<?php
require_once 'db_connect.php';

// 初始化資料庫連線
$database = new Database();
$db = $database->connect();

// 處理表單提交
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // 準備 SQL 語句（移除 phone 和 email）
        $sql = "INSERT INTO customers (name, company_name, contact_info, notes) 
                VALUES (:name, :company_name, :contact_info, :notes)";
        
        $stmt = $db->prepare($sql);
        
        // 綁定參數（移除 phone 和 email）
        $stmt->bindParam(':name', $_POST['name']);
        $stmt->bindParam(':company_name', $_POST['company_name']);
        $stmt->bindParam(':contact_info', $_POST['contact_info']);
        $stmt->bindParam(':notes', $_POST['notes']);
        
        // 執行SQL
        $stmt->execute();
        
        // 設置成功訊息
        $success_message = "客戶資料新增成功！";
        
        // 重定向到客戶列表頁面
        header("Location: view_customers.php?message=" . urlencode($success_message));
        exit();
        
    } catch(PDOException $e) {
        $error_message = "錯誤：" . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新增客戶資料 - 客戶管理系統</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>新增客戶資料</h1>
        
        <?php if (isset($error_message)): ?>
            <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>
        
        <form method="POST" onsubmit="return validateForm()">
            <div class="form-group">
                <label for="name">客戶姓名 *</label>
                <input type="text" id="name" name="name" required>
            </div>
            
            <div class="form-group">
                <label for="company_name">公司名稱</label>
                <input type="text" id="company_name" name="company_name">
            </div>
            
            <div class="form-group">
                <label for="contact_info">聯絡資訊</label>
                <textarea id="contact_info" name="contact_info" rows="3"></textarea>
            </div>
            
            <div class="form-group">
                <label for="notes">備註</label>
                <textarea id="notes" name="notes" rows="3"></textarea>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn btn-primary">新增客戶</button>
                <a href="index.php" class="btn">返回首頁</a>
            </div>
        </form>
    </div>
    
    <script src="script.js"></script>
</body>
</html> 
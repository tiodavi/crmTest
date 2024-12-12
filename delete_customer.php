<?php
// 確保錯誤信息會顯示
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 引入數據庫連接文件
require_once __DIR__ . '/db_connect.php';

// 檢查數據庫連接
if (!isset($pdo)) {
    die("數據庫連接失敗");
}

// 確保有接收到客戶ID
if (!isset($_GET['id']) && !isset($_POST['id'])) {
    header('Location: /crmTest/view_customers.php');
    exit();
}

$customer_id = isset($_GET['id']) ? $_GET['id'] : $_POST['id'];

// 如果是 POST 請求，表示確認刪除
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm'])) {
    try {
        // 準備刪除語句
        $stmt = $pdo->prepare("DELETE FROM customers WHERE id = ?");
        $stmt->execute([$customer_id]);
        
        // 設置成功消息
        session_start();
        $_SESSION['message'] = "客戶資料已成功刪除";
        header('Location: /crmTest/view_customers.php');
        exit();
        
    } catch (PDOException $e) {
        die("刪除失敗: " . $e->getMessage());
    }
}

// 獲取客戶資料以顯示確認信息
try {
    $stmt = $pdo->prepare("SELECT * FROM customers WHERE id = ?");
    $stmt->execute([$customer_id]);
    $customer = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$customer) {
        header('Location: /crmTest/view_customers.php');
        exit();
    }
} catch (PDOException $e) {
    die("查詢失敗: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>刪除客戶確認</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>刪除客戶確認</h1>
        <div class="confirmation-box">
            <p>您確定要刪除以下客戶資料嗎？</p>
            <p>客戶名稱：<?php echo htmlspecialchars($customer['name']); ?></p>
            <p>聯絡電話：<?php echo htmlspecialchars($customer['phone']); ?></p>
            
            <form method="POST" action="delete_customer.php">
                <input type="hidden" name="id" value="<?php echo $customer_id; ?>">
                <input type="hidden" name="confirm" value="1">
                <div class="button-group">
                    <button type="submit" class="delete-btn">確認刪除</button>
                    <a href="view_customers.php" class="cancel-btn">取消</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html> 
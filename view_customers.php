<?php
require_once 'db_connect.php';

// 初始化資料庫連線
$database = new Database();
$db = $database->connect();

// 處理每頁顯示數量
$per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start_from = ($page - 1) * $per_page;

try {
    // 獲取總記錄數
    $count_sql = "SELECT COUNT(*) as total FROM customers";
    $count_stmt = $db->query($count_sql);
    $total_records = $count_stmt->fetch(PDO::FETCH_ASSOC)['total'];
    $total_pages = ceil($total_records / $per_page);

    // 獲取客戶列表
    $sql = "SELECT * FROM customers ORDER BY created_at DESC LIMIT :start, :per_page";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':start', $start_from, PDO::PARAM_INT);
    $stmt->bindValue(':per_page', $per_page, PDO::PARAM_INT);
    $stmt->execute();
    $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    $error_message = "錯誤：" . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>客戶列表 - 客戶���理系統</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>客戶列表</h1>

        <?php if (isset($_GET['message'])): ?>
            <div class="success-message">
                <?php echo htmlspecialchars($_GET['message']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
            <div class="error-message">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <div class="actions">
            <a href="add_customer.php" class="btn btn-primary">新增客戶</a>
            <a href="index.php" class="btn">返回首頁</a>
        </div>

        <?php if (!empty($customers)): ?>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>客戶編號</th>
                            <th>姓名</th>
                            <th>公司名稱</th>
                            <th>聯絡資訊</th>
                            <th>建立時間</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($customers as $customer): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($customer['id']); ?></td>
                                <td><?php echo htmlspecialchars($customer['name']); ?></td>
                                <td><?php echo htmlspecialchars($customer['company_name']); ?></td>
                                <td><?php echo htmlspecialchars($customer['contact_info']); ?></td>
                                <td><?php echo date('Y-m-d H:i', strtotime($customer['created_at'])); ?></td>
                                <td>
                                    <a href="edit_customer.php?id=<?php echo $customer['id']; ?>" 
                                       class="btn btn-primary">編輯</a>
                                    <button onclick="confirmDelete(<?php echo $customer['id']; ?>)" 
                                            class="btn btn-danger">刪除</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- 分頁導航 -->
            <?php if ($total_pages > 1): ?>
                <div class="pagination">
                    <?php if ($page > 1): ?>
                        <a href="?page=<?php echo ($page - 1); ?>" class="btn">&laquo; 上一頁</a>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <?php if ($i == $page): ?>
                            <span class="current-page"><?php echo $i; ?></span>
                        <?php else: ?>
                            <a href="?page=<?php echo $i; ?>" class="btn"><?php echo $i; ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>

                    <?php if ($page < $total_pages): ?>
                        <a href="?page=<?php echo ($page + 1); ?>" class="btn">下一頁 &raquo;</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

        <?php else: ?>
            <p>目前沒有客戶資料。</p>
        <?php endif; ?>
    </div>

    <script>
    function confirmDelete(id) {
        if (confirm('確定要刪除這個客戶資料嗎？此操作無法復原。')) {
            window.location.href = `delete_customer.php?id=${id}`;
        }
    }
    </script>
    <script src="script.js"></script>
</body>
</html> 
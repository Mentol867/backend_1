<?php
require_once 'db_config.php';

try {
    $stmtTotal = $pdo->query("SELECT COUNT(*) FROM traffic_logs WHERE request_time >= NOW() - INTERVAL 1 DAY");
    $totalRequests = $stmtTotal->fetchColumn();

    $stmt404 = $pdo->query("SELECT COUNT(*) FROM traffic_logs WHERE http_status = 404 AND request_time >= NOW() - INTERVAL 1 DAY");
    $total404 = $stmt404->fetchColumn();

    $percentage404 = ($totalRequests > 0) ? ($total404 / $totalRequests) * 100 : 0;

    $alertSent = false;
    if ($percentage404 > 10) {
        $alertSent = true;
    }

} catch (\PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Статистика трафіку</title>
</head>
<body>
    <h1>Статистика трафіку (останні 24г)</h1>

    <ul>
        <li>Загальна кількість запитів: <?php echo $totalRequests; ?></li>
        <li>Помилки 404: <?php echo $total404; ?></li>
        <li>Відсоток помилок: <?php echo round($percentage404, 1); ?>%</li>
    </ul>

    <?php if ($alertSent): ?>
        <p style="color: red;"><strong>Збій системи!</strong> Виявлено критичну кількість помилок 404 (>10%).</p>
    <?php else: ?>
        <p>Система працює стабільно.</p>
    <?php endif; ?>

    <p><a href="index.php">Повернутися на головну</a></p>
</body>
</html>
<?php
require_once 'db_config.php';

register_shutdown_function(function () use ($pdo) {
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $url = $_SERVER['REQUEST_URI'] ?? 'unknown';
    $status = http_response_code();

    if ($status === false) {
        $status = 200;
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO traffic_logs (ip_address, url, http_status) VALUES (?, ?, ?)");
        $stmt->execute([$ip, $url, $status]);
    } catch (\PDOException $e) {
        error_log("Traffic Logger Error: " . $e->getMessage());
    }
});

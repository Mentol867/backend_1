<?php
const MAX_REQUESTS = 5;
const TIME_WINDOW = 60;
const LOG_FILE = __DIR__ . '/request.log';

$currentIp = $_SERVER['REMOTE_ADDR'];
$currentTime = time();

$recentRequests = [];
if (file_exists(LOG_FILE)) {
    $lines = file(LOG_FILE, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $entry = json_decode($line, true);
        if ($entry && isset($entry['ip'], $entry['date']) && $entry['ip'] === $currentIp) {
            $timestamp = strtotime($entry['date']);
            if ($currentTime - $timestamp <= TIME_WINDOW) {
                $recentRequests[] = $timestamp;
            }
        }
    }
}

$requestCount = count($recentRequests);

if ($requestCount >= MAX_REQUESTS) {
    $oldestRequest = min($recentRequests);
    $retryAfter = TIME_WINDOW - ($currentTime - $oldestRequest);

    header('HTTP/1.1 429 Too Many Requests');
    header('RateLimit-Limit: ' . MAX_REQUESTS);
    header('RateLimit-Remaining: 0');
    header('Retry-After: ' . $retryAfter);

    if (file_exists(__DIR__ . '/429.php')) {
        include __DIR__ . '/429.php';
    } else {
        echo "<h1>429 Too Many Requests</h1><p>Please try again in $retryAfter seconds.</p>";
    }
    exit;
}
$data = [
    'ip' => $currentIp,
    'date' => date('Y-m-d H:i:s')
];
$jsonLine = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . PHP_EOL;
file_put_contents(LOG_FILE, $jsonLine, FILE_APPEND);

$remaining = MAX_REQUESTS - ($requestCount + 1);
header('RateLimit-Limit: ' . MAX_REQUESTS);
header('RateLimit-Remaining: ' . $remaining);
header('RateLimit-Reset: ' . (time() + TIME_WINDOW));

?>
<!DOCTYPE html>
<html lang="uk">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rate Limit Check</title>

</head>

<body>
    <div>
        <div>Запит дозволено</div>
        <div>Залишилося запитів:<?php echo $remaining; ?></div>

        <div> з <?php echo MAX_REQUESTS; ?> дозволених за хвилину</div>

    </div>
</body>

</html>
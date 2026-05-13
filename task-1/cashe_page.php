<?php
$pageName = pathinfo($_SERVER['SCRIPT_NAME'], PATHINFO_FILENAME);

$cacheDir = __DIR__ . '/pages_cache';
if (!is_dir($cacheDir)) {
    mkdir($cacheDir, 0777, true);
}

$cacheFile = $cacheDir . "/" . $pageName . ".cache.html";
$cacheTime = 3600;

if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < $cacheTime)) {
    echo file_get_contents($cacheFile);
    exit;
}

ob_start();

register_shutdown_function(function () use ($cacheFile) {
    $statusCode = http_response_code();
    $content = ob_get_contents();

    if ($statusCode === 200) {
        $content .= "\n<!-- Версія в кеші від " . date('H:i:s') . " -->";
        file_put_contents($cacheFile, $content);
    } elseif ($statusCode === 404 && file_exists($cacheFile)) {
        unlink($cacheFile);
    }

    ob_end_flush();
});

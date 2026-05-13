<?php
require_once 'traffic_logger.php';

http_response_code(404);

echo "<h1>404 - Не знайдено </h1>";
echo "<p>Це сторінка з помилкою 404. Цей запит має бути записаний як статус 404</p>";
echo "<p><a href='index.php'>Додому</a></p>";

<?php
require_once 'rate_limit.php';
include 'cashe_page.php';
?>


<!DOCTYPE html>
<html lang="uk">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Контентна сторінка</title>

</head>

<body>
    <div>
        <p>Зробимо вигляд що тут дуже гарна сторінка</p>
        <div>Сторінку згенеровано на сервері</div>
        <div>Час генерації: <?php echo date('H:i:s'); ?> | Дата: <?php echo date('d.m.Y'); ?></div>
    </div>
</body>

</html>
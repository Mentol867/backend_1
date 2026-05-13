<!DOCTYPE html>
<html lang="uk">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Головна - Точка входу</title>
</head>

<body>
    <div>
        <h1>Демонстрація редиректів</h1>
        <p>Виберіть посилання для перевірки роботи <code>redirect_manager.php</code>:</p>

        <div>
            <!-- Перенаправляємо через GET-параметр для чистої демонстрації роботи header() -->
            <a href="redirect_manager.php?path=/old-page">Перейти на /old-page (301 Redirect)</a>

            <a href="redirect_manager.php?path=/deprecated">Перейти на /deprecated (404 Error)</a>
        </div>
    </div>
</body>

</html>
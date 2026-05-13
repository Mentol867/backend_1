<!DOCTYPE html>
<html lang="uk">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>429</title>

</head>

<body>
    <div>429</div>
    <h1>Забагато запитів</h1>
    <p>Ви перевищили ліміт запитів до сервера. Будь ласка, зачекайте трохи перед наступною спробою.</p>

    <div>
        <div id="timer"><?php echo isset($retryAfter) ? (int) $retryAfter : 60; ?></div>
        секунд до розблокування
    </div>

    <div>
        <button onclick="location.reload()">Спробувати знову</button>
    </div>
    </div>

    <script>
        let seconds = <?php echo isset($retryAfter) ? (int) $retryAfter : 60; ?>;
        const timerElement = document.getElementById('timer');

        const countdown = setInterval(() => {
            seconds--;
            if (seconds <= 0) {
                clearInterval(countdown);
                timerElement.innerText = "0";
                location.reload();
            } else {
                timerElement.innerText = seconds;
            }
        }, 1000);
    </script>
</body>

</html>
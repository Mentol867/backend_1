<?php
/**
 * Redirect Manager
 * 
 * Manages URL redirections based on a JSON configuration file.
 * Supports 301 redirects and custom 404 error pages.
 */

// Start output buffering to prevent "headers already sent" errors
ob_start();

// Path to the configuration file
$configPath = __DIR__ . DIRECTORY_SEPARATOR . 'redirects.json';

// Function to handle the redirection logic
function manageRedirects($configPath) {
    if (!file_exists($configPath)) {
        header("HTTP/1.1 500 Internal Server Error");
        echo "<h1>Configuration Error</h1>";
        echo "<p>The redirects configuration file was not found.</p>";
        return;
    }

    $jsonContent = file_get_contents($configPath);
    $redirects = json_decode($jsonContent, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        header("HTTP/1.1 500 Internal Server Error");
        echo "<h1>Configuration Error</h1>";
        echo "<p>Invalid JSON format in configuration file.</p>";
        return;
    }

    // Get the path to check from the GET parameter
    // This allows us to handle redirects using header() without needing .htaccess
    $currentUri = isset($_GET['path']) ? $_GET['path'] : parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    // Determine the redirect target
    $foundTarget = null;

    if (isset($redirects[$currentUri])) {
        $foundTarget = $redirects[$currentUri];
    } 
    // Fallback for cases where index.php calls with a relative path
    else {
        $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
        $relativeUri = str_replace($scriptDir, '', $currentUri);
        if (isset($redirects[$relativeUri])) {
            $foundTarget = $redirects[$relativeUri];
        }
    }

    if ($foundTarget) {
        $target = $foundTarget;

        if ($target === "/404") {
            // Handle deprecated pages with a 404 status
            header("HTTP/1.1 404 Not Found");
            renderErrorPage("404 Not Found", "Ця сторінка застаріла і більше не доступна.");
        } else {
            // Perform 301 Moved Permanently redirect
            header("Location: $target", true, 301);
            exit();
        }
    } else {
        // Default content if no redirect is matched
        renderDefaultPage($currentUri, $redirects);
    }
}

/**
 * Renders a clean, modern error page
 */
function renderErrorPage($title, $message) {
    ?>
    <!DOCTYPE html>
    <html lang="uk">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo $title; ?></title>
    </head>
    <body>
        <div>
            <h1><?php echo explode(' ', $title)[0]; ?></h1>
            <h2><?php echo substr($title, 4); ?></h2>
            <p><?php echo $message; ?></p>
            <a href="index.php">На головну</a>
        </div>
    </body>
    </html>
    <?php
}

/**
 * Renders a default landing page for testing purposes
 */
function renderDefaultPage($currentUri, $redirects) {
    ?>
    <!DOCTYPE html>
    <html lang="uk">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Redirect Manager Testing</title>
    </head>
    <body>
        <div>
            <h1>Управління перенаправленнями</h1>
            <div>
                Поточний шлях: <code><?php echo htmlspecialchars($currentUri); ?></code>
            </div>
            
            <h3>Доступні перенаправлення (з redirects.json):</h3>
            <ul>
                <?php foreach ($redirects as $old => $new): ?>
                <li>
                    <span>Від: <code><?php echo htmlspecialchars($old); ?></code></span>
                    <span>До: <a href="?simulate_path=<?php echo urlencode($old); ?>"><?php echo htmlspecialchars($new); ?></a></span>
                </li>
                <?php endforeach; ?>
            </ul>
            
            <p>
                * Посилання вище використовують параметр <code>simulate_path</code> для тестування логіки без налаштування .htaccess.
            </p>
        </div>
    </body>
    </html>
    <?php
}

// Execute the manager
manageRedirects($configPath);

// Flush buffer and send output
ob_end_flush();

<?php
ob_start();

function handleFatalError()
{
    $error = error_get_last();

    if ($error !== null && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        if (ob_get_length()) {
            ob_clean();
        }

        http_response_code(500);

        $resolutionTime = date('H:i', strtotime('+1 hour'));
        $currentDate = date('d.m.Y');

        require_once 'response_500.html';
        exit;
    } else {
        if (!headers_sent()) {
            http_response_code(200);
        }
        ob_end_flush();
    }
}

// Register the shutdown function
register_shutdown_function('handleFatalError');

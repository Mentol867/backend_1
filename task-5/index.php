<?php
require_once 'Response.php';

$response = new Response();

$htmlContent = '<h1>Вітаємо!</h1><p>Це динамічна відповідь</p>';

$response->setStatus(200);
$response->addHeader("Content-Type: text/html; charset=UTF-8");

$response->send($htmlContent);


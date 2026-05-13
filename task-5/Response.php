<?php
class Response
{
    private $headers = [];
    private $statusCode = 200;

    public function __construct()
    {
        // Start output buffering as soon as the response object is created
        if (ob_get_level() === 0) {
            ob_start();
        }
    }

    public function setStatus($code)
    {
        $this->statusCode = (int) $code;
        http_response_code($this->statusCode);
    }

    public function addHeader($header)
    {
        $this->headers[] = $header;
    }

    public function send($content)
    {
        // Clear any previous output in the buffer
        if (ob_get_length() > 0) {
            ob_clean();
        }

        // Send all registered headers
        foreach ($this->headers as $header) {
            header($header);
        }

        // Set the status code again just in case it was overwritten
        http_response_code($this->statusCode);

        // Output the final content
        echo $content;

        // End buffering and flush
        ob_end_flush();
        exit; // Usually send() is the final action
    }
}
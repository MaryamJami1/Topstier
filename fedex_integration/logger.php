<?php
// logger.php

/**
 * Simple logging utility for FedEx Integration
 *
 * @param string $message The message to log
 * @param array $context Optional context data
 * @param string $level Log level (INFO, ERROR, WARNING)
 */
function fedexLog($message, $context = [], $level = 'ERROR') {
    $logDir = __DIR__ . '/../logs';
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }
    
    $date = date('Y-m-d');
    $time = date('Y-m-d H:i:s');
    $logFile = $logDir . "/fedex_api_{$date}.log";
    
    $contextStr = !empty($context) ? json_encode($context) : '';
    $logEntry = "[{$time}] [{$level}] {$message} {$contextStr}" . PHP_EOL;
    
    file_put_contents($logFile, $logEntry, FILE_APPEND);
}

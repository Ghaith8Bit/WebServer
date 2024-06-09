<?php

require_once 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Check for required classes
if (!class_exists('Ghaith8bit\\WebServer\\Server')) {
    throw new Exception('Server class not found. Ensure it is correctly autoloaded.');
}

if (!class_exists('Ghaith8bit\\WebServer\\Request')) {
    throw new Exception('Request class not found. Ensure it is correctly autoloaded.');
}

if (!class_exists('Ghaith8bit\\WebServer\\Response')) {
    throw new Exception('Response class not found. Ensure it is correctly autoloaded.');
}

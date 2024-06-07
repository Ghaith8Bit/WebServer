<?php

require_once 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Check for required classes
if (!class_exists('Server')) {
    throw new Exception('Server class not found. Ensure it is correctly autoloaded.');
}

if (!class_exists('Request')) {
    throw new Exception('Request class not found. Ensure it is correctly autoloaded.');
}

if (!class_exists('Response')) {
    throw new Exception('Response class not found. Ensure it is correctly autoloaded.');
}

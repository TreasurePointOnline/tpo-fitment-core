<?php
/**
 * Plugin Name: TPO Debug Logger
 * Description: Forces maximum error reporting and logging for debugging purposes.
 * Version: 1.0
 * Author: Treasure Point AI
 */

// Force error reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

// Set a custom error log file
$log_file = WP_CONTENT_DIR . '/tpo-debug.log';
ini_set('log_errors', '1');
ini_set('error_log', $log_file);

// Log all errors to the custom file
set_error_handler(function($errno, $errstr, $errfile, $errline ) use ($log_file) {
    // Log to custom file
    error_log( "[$errno] $errstr in $errfile on line $errline", 3, $log_file );
    // Optionally also log to the default PHP error log
    // error_log( "[$errno] $errstr in $errfile on line $errline" );
    
    // Don't die on non-fatal errors
    return false; // Let PHP's default error handler continue
});

// Also log uncaught exceptions
set_exception_handler(function($exception) use ($log_file) {
    error_log( "Uncaught exception: " . $exception->getMessage() . " in " . $exception->getFile() . " on line " . $exception->getLine(), 3, $log_file );
    // Display error for debugging
    echo "<h1>Critical Error!</h1>";
    echo "<p>Message: " . $exception->getMessage() . "</p>";
    echo "<p>File: " . $exception->getFile() . "</p>";
    echo "<p>Line: " . $exception->getLine() . "</p>";
    exit(1);
});

// Test line to confirm logger is active
error_log("TPO Debug Logger activated. " . date('Y-m-d H:i:s'));

?>

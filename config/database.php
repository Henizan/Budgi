<?php
// Include Composer autoloader
require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

// Load environment variables from .env
$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->safeLoad();

// Database configuration using environment variables
$servername = $_ENV['DB_HOST'] ?? 'localhost';
$port = $_ENV['DB_PORT'] ?? '5432';
$username = $_ENV['DB_USER'] ?? 'budgi_user';
$dbpassword = $_ENV['DB_PASS'] ?? 'budgi_password';
$dbname = $_ENV['DB_NAME'] ?? 'budgi_db';

// Format for PostgreSQL PDO
$dsn = "pgsql:host=$servername;port=$port;dbname=$dbname";
?>

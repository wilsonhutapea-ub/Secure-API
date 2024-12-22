<?php  
require_once __DIR__ . '/../vendor/autoload.php';  

use Dotenv\Dotenv;  

// Load environment variables  
$dotenv = Dotenv::createImmutable(__DIR__ . '/..');  
$dotenv->load();  

try {  
    $pdo = new PDO(  // membuat koneksi ke database dengan data dari .env (best practice agar credentials tidak tersimpan di version control)
        "mysql:host=" . $_ENV['DB_HOST'] . ";dbname=" . $_ENV['DB_NAME'],  
        $_ENV['DB_USER'],  
        $_ENV['DB_PASS']  
    );  
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  
} catch(PDOException $e) {  
    die("Connection failed: " . $e->getMessage());  
}
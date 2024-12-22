<?php  
require '../config/database.php';  
require '../vendor/autoload.php';   

use \Firebase\JWT\JWT;  

header('Content-Type: application/json');  

// Generate secure key if not exists in .env  
if (!isset($_ENV['JWT_SECRET_KEY'])) {  
    $secureKey = bin2hex(random_bytes(32));  
    
    // Append to .env file  
    file_put_contents(__DIR__ . '/../.env',   
        PHP_EOL . "JWT_SECRET_KEY=" . $secureKey,   
        FILE_APPEND  
    );  
}  

// Retrieve the key from environment  
$key = $_ENV['JWT_SECRET_KEY'];  

$data = json_decode(file_get_contents("php://input"));  

if (isset($data->username) && isset($data->password)) {  
    $username = trim($data->username);  
    $password = trim($data->password);  

    // Input validation  
    if (empty($username) || empty($password)) {  
        echo json_encode(["message" => "Username and password are required."]);  
        exit;  
    }  

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");  
    $stmt->execute([$username]);  
    $user = $stmt->fetch(PDO::FETCH_ASSOC);  

    if ($user && password_verify($password, $user['password'])) {  // membandingkan password yang diinputkan dengan password yang telah terenkripsi di database
        // Generate JWT  
        $payload = [  
            'iat' => time(), // Issued at  
            'exp' => time() + (60 * 60), // Expiration time (1 hour)  
            'username' => $username  
        ];  
        $jwt = JWT::encode($payload, $key, 'HS256');  

        echo json_encode(["token" => $jwt]);  
    } else {  
        echo json_encode(["message" => "Invalid credentials."]);  
    }  
} else {  
    echo json_encode(["message" => "Invalid input."]);  
}  
?>
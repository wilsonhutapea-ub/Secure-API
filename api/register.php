<?php  
require '../config/database.php';  
require '../vendor/autoload.php';   

use \Firebase\JWT\JWT;  

header('Content-Type: application/json');  

$data = json_decode(file_get_contents("php://input"));  

if (isset($data->username) && isset($data->password)) {  
    $username = trim($data->username);  // menghilangkan spasi/tab/newline/dll. di awal dan akhir string
    $password = trim($data->password);  

    // Input validation  
    if (strlen($username) < 3 || strlen($password) < 6) {  // memastikan username minimal 3 karakter dan password minimal 6 karakter
        http_response_code(400);  
        echo json_encode([  
            "status" => "error",  
            "message" => "Username must be at least 3 characters and password at least 6 characters."  
        ]);  
        exit;  
    }  

    // Check for special characters in username  
    if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {  // memastikan username hanya mengandung huruf, angka, dan underscore
        http_response_code(400);  
        echo json_encode([  
            "status" => "error",  
            "message" => "Username can only contain letters, numbers, and underscores."  
        ]);  
        exit;  
    }  

    // Check if username already exists  
    $checkStmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");  
    $checkStmt->execute([$username]);  
    if ($checkStmt->fetch()) {  
        http_response_code(409);  
        echo json_encode([  
            "status" => "error",  
            "message" => "Username already exists."  
        ]);  
        exit;  
    }  

    // Encrypt password  
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);   // mengenkripsi password menggunakan bcrypt

    try {  
        // Begin transaction  
        $pdo->beginTransaction();  

        // Insert user  
        $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");  // memasukkan username dan password (yang telah terenkripsi) ke database
        $stmt->execute([$username, $hashedPassword]);  

        // Generate JWT  
        if (empty($_ENV['JWT_SECRET_KEY'])){ // jika JWT_SECRET_KEY belum ada di .env, maka generate random key dan simpan di .env
            $newSecretKey = bin2hex(random_bytes(32));
            file_put_contents(__DIR__ . '/../.env', PHP_EOL . "JWT_SECRET_KEY=" . $newSecretKey, FILE_APPEND);
            $dotenv->load();
        } 
        $key = $_ENV['JWT_SECRET_KEY'] ?? die('JWT_SECRET_KEY is not set in the .env file');

        $payload = [  // payload JWT berisi informasi user
            'iat' => time(), // Issued at  
            'exp' => time() + (60 * 60 * 24), // Expiration time (24 hours)  
            'username' => $username,  
            'user_id' => $pdo->lastInsertId()  
        ];  
        $jwt = JWT::encode($payload, $key, 'HS256');

        // Commit transaction  
        $pdo->commit();  

        // Successful response  
        http_response_code(201);  
        echo json_encode([  
            "status" => "success",  
            "message" => "User registered successfully.",  
            "token" => $jwt,  
            "username" => $username  
        ]);  

    } catch (Exception $e) {  
        // Rollback transaction in case of error  
        $pdo->rollBack();  

        http_response_code(500);  
        echo json_encode([  
            "status" => "error",  
            "message" => "Registration failed: " . $e->getMessage()  
        ]);  
    }  
} else {  
    http_response_code(400);  
    echo json_encode([  
        "status" => "error",  
        "message" => "Invalid input."  
    ]);  
}  
?>
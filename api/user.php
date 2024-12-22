<?php  
require '../config/database.php';  
require '../vendor/autoload.php';   

use \Firebase\JWT\JWT;  
use \Firebase\JWT\ExpiredException;  
use \Firebase\JWT\BeforeValidException;  
use \Firebase\JWT\SignatureInvalidException;  

header('Content-Type: application/json');  

$headers = apache_request_headers();  
$token = isset($headers['Authorization']) ? str_replace('Bearer ', '', $headers['Authorization']) : '';  

if (!$token) {  
    http_response_code(401);  
    echo json_encode([  
        "message" => "No token provided",  
        "status" => "error"  
    ]);  
    exit;  
}  

try {  
    // Load the secret key  
    $key = $_ENV['JWT_SECRET_KEY'] ?? die('JWT_SECRET_KEY is not set in the .env file');   

    // Specify the algorithms explicitly  
    $decoded = JWT::decode($token, new \Firebase\JWT\Key($key, 'HS256'));  
    
    // If decoding is successful, proceed with your logic  
    echo json_encode([
        "username" => $decoded->username,
        "message" => "You are logged in as " . $decoded->username,
    ]);

} catch (ExpiredException $e) {  
    http_response_code(401);  
    echo json_encode([  
        "message" => "Token has expired",  
        "status" => "error"  
    ]);  
} catch (SignatureInvalidException $e) {  
    http_response_code(401);  
    echo json_encode([  
        "message" => "Invalid token signature",  
        "status" => "error"  
    ]);  
} catch (BeforeValidException $e) {  
    http_response_code(401);  
    echo json_encode([  
        "message" => "Token is not yet valid",  
        "status" => "error"  
    ]);  
} catch (Exception $e) {  
    http_response_code(401);  
    echo json_encode([  
        "message" => "Unauthorized",  
        "error_details" => $e->getMessage(),  
        "error_code" => $e->getCode(),  
        "file" => $e->getFile(),  
        "line" => $e->getLine(),  
        "status" => "error"  
    ]);   
}  
?>
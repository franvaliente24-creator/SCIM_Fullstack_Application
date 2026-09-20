<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uriSegments = explode('/', trim($uri, '/'));

// API routing
if (isset($uriSegments[0]) && $uriSegments[0] === 'api') {
    $service = $uriSegments[1] ?? '';
    
    switch ($service) {
        case 'auth':
            include 'auth-service/index.php';
            break;
        case 'warehouse':
            include 'warehouse-service/index.php';
            break;
        case 'inventory':
            include 'inventory-service/index.php';
            break;
        default:
            http_response_code(404);
            echo json_encode(["error" => "API endpoint not found"]);
            break;
    }
} else {
    http_response_code(404);
    echo json_encode(["error" => "Not an API endpoint"]);
}
?>
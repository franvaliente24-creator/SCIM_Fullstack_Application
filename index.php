<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uriSegments = explode('/', trim($uri, '/'));

$endpoint = $uriSegments[0] ?? '';

// Route to appropriate microservice
switch ($endpoint) {
    case 'api':
        // API Gateway routes
        $apiEndpoint = $uriSegments[1] ?? '';
        switch ($apiEndpoint) {
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
        break;
    
    case 'auth':
        include 'auth-service/index.php';
        break;
    
    case 'warehouse':
        include 'warehouse-service/index.php';
        break;
    
    case 'inventory':
        include 'inventory-service/index.php';
        break;
    
    case '':
    case 'index.php':
        // Serve frontend
        include 'frontend/index.php';
        break;
    
    default:
        // Try to serve as static file from frontend
        $frontendPath = 'frontend/' . $uri;
        if (file_exists($frontendPath)) {
            $mimeTypes = [
                'css' => 'text/css',
                'js' => 'application/javascript',
                'png' => 'image/png',
                'jpg' => 'image/jpeg',
                'svg' => 'image/svg+xml',
                'ico' => 'image/x-icon'
            ];
            
            $extension = pathinfo($frontendPath, PATHINFO_EXTENSION);
            if (isset($mimeTypes[$extension])) {
                header("Content-Type: " . $mimeTypes[$extension]);
                readfile($frontendPath);
            } else {
                include 'frontend/index.php';
            }
        } else {
            // Default to frontend for SPA routing
            include 'frontend/index.php';
        }
        break;
}
?>
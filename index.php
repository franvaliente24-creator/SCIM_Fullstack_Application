<?php
// Set proper content type for HTML pages
header("Access-Control-Allow-Origin: *");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uriSegments = explode('/', trim($uri, '/'));

$endpoint = $uriSegments[0] ?? '';

// Route to appropriate microservice
switch ($endpoint) {
    case 'api':
        // API Gateway routes
        header("Content-Type: application/json; charset=UTF-8");
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
        header("Content-Type: application/json; charset=UTF-8");
        include 'auth-service/index.php';
        break;
    
    case 'warehouse':
        header("Content-Type: application/json; charset=UTF-8");
        include 'warehouse-service/index.php';
        break;
    
    case 'inventory':
        header("Content-Type: application/json; charset=UTF-8");
        include 'inventory-service/index.php';
        break;
    
    case '':
    case 'index.php':
        // Serve frontend as HTML
        header("Content-Type: text/html; charset=UTF-8");
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
                'ico' => 'image/x-icon',
                'html' => 'text/html'
            ];
            
            $extension = pathinfo($frontendPath, PATHINFO_EXTENSION);
            if (isset($mimeTypes[$extension])) {
                header("Content-Type: " . $mimeTypes[$extension] . "; charset=UTF-8");
                readfile($frontendPath);
            } else {
                header("Content-Type: text/html; charset=UTF-8");
                include 'frontend/index.php';
            }
        } else {
            // Default to frontend for SPA routing
            header("Content-Type: text/html; charset=UTF-8");
            include 'frontend/index.php';
        }
        break;
}
?>
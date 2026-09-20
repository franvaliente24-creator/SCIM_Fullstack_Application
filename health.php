<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");

// Simple health check
echo json_encode([
    "status" => "healthy",
    "timestamp" => date("Y-m-d H:i:s"),
    "service" => "SCIM Application"
]);
?>
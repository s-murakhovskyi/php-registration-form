<?php

// Import the Controller class
use App\Controllers\RegistrationController;

session_start();

// Load configuration
require_once __DIR__ . '/../config/config.php';

// Load the Controller manually
require_once __DIR__ . '/../app/Controllers/RegistrationController.php';

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Initialize the controller
$controller = new RegistrationController();

// Simple Router Logic
switch ($path) {
    case '/':
    case '/index.php':
        $controller->index();
        break;

    case '/submit-step-1':
        $controller->submitStep1();
        break;

    case '/submit-step-2':
        $controller->submitStep2();
        break;

    case '/all-members':
        $controller->allMembers();
        break;

    default:
        http_response_code(404);
        echo "404 Not Found";
        break;
}
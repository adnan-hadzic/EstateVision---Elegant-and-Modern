<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/config.php';
require __DIR__ . '/data/Roles.php';

require_once __DIR__ . '/rest/services/AuthService.php';
require_once __DIR__ . '/rest/services/UserServices.php';
require_once __DIR__ . '/rest/services/AgentServices.php';
require_once __DIR__ . '/rest/services/AppointmentServices.php';
require_once __DIR__ . '/rest/services/PropertyServices.php';
require_once __DIR__ . '/rest/services/ReviewServices.php';
require_once __DIR__ . '/middleware/AuthMiddleware.php';

Flight::register('authService', 'AuthService');
Flight::register('userService', 'UserServices');
Flight::register('agentService', 'AgentServices');
Flight::register('appointmentService', 'AppointmentServices');
Flight::register('propertyService', 'PropertyServices');
Flight::register('reviewService', 'ReviewServices');
Flight::register('authMiddleware', 'AuthMiddleware');

function authorizeRole($requiredRole) {
    Flight::authMiddleware()->authorizeRole($requiredRole);
}

function authorizeRoles($roles) {
    Flight::authMiddleware()->authorizeRoles($roles);
}

function authorizePermission($permission) {
    Flight::authMiddleware()->authorizePermission($permission);
}

Flight::route('/*', function() {
   if (
       strpos(Flight::request()->url, '/auth/login') === 0 ||
       strpos(Flight::request()->url, '/auth/register') === 0
   ) {
       return true; 
   } else {
       try {
           $token = Flight::request()->getHeader("Authorization");
           if (!$token && isset($_SERVER['HTTP_AUTHORIZATION'])) {
               $token = $_SERVER['HTTP_AUTHORIZATION'];
           }
           if (!$token && isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
               $token = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
           }
           if (!$token && function_exists('getallheaders')) {
               $allHeaders = getallheaders();
               if (isset($allHeaders['Authorization'])) {
                   $token = $allHeaders['Authorization'];
               } elseif (isset($allHeaders['authorization'])) {
                   $token = $allHeaders['authorization'];
               }
           }
           $token = str_replace('Bearer ', '', $token);
           if (Flight::authMiddleware()->verifyToken($token))
               return true;
       } catch (\Exception $e) {
           Flight::halt(401, $e->getMessage());
       }
   }
});

require_once __DIR__ . '/rest/routes/AuthRoutes.php';
require_once __DIR__ . '/rest/routes/UserRoutes.php';
require_once __DIR__ . '/rest/routes/AgentRoutes.php';
require_once __DIR__ . '/rest/routes/AppointmentRoutes.php';
require_once __DIR__ . '/rest/routes/PropertyRoutes.php';
require_once __DIR__ . '/rest/routes/ReviewRoutes.php';

Flight::start();
?>

<?php
require __DIR__ . '/vendor/autoload.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/rest/services/BaseService.php'; 
require_once __DIR__ . '/rest/services/UserServices.php';
require_once __DIR__ . '/rest/services/AgentServices.php';
require_once __DIR__ . '/rest/services/AppointmentServices.php';
require_once __DIR__ . '/rest/services/PropertyServices.php';
require_once __DIR__ . '/rest/services/ReviewServices.php';

Flight::register('userService', 'UserServices');
Flight::register('agentService', 'AgentServices');
Flight::register('appointmentService', 'AppointmentServices');
Flight::register('propertyService', 'PropertyServices');
Flight::register('reviewService', 'ReviewServices');

require_once __DIR__ . '/rest/routes/UserRoutes.php';
require_once __DIR__ . '/rest/routes/AgentRoutes.php';
require_once __DIR__ . '/rest/routes/AppointmentRoutes.php';
require_once __DIR__ . '/rest/routes/PropertyRoutes.php';
require_once __DIR__ . '/rest/routes/ReviewRoutes.php';

Flight::start();
?>

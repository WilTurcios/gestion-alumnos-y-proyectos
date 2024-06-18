<?php
session_start();

require_once 'routes/Users.php';
require_once 'routes/Auth.php';
require_once 'routes/Evaluations.php';
require_once 'routes/Projects.php';
require_once 'routes/Students.php';
require_once 'routes/Groups.php';
require_once 'routes/Companies.php';
require_once 'models/mysql/Usuarios.php';
require_once 'models/mysql/Proyectos.php';
require_once 'models/mysql/Estudiantes.php';
require_once 'models/mysql/Evaluaciones.php';
require_once 'models/mysql/Grupos.php';
require_once 'models/mysql/Empresas.php';
require_once 'utils/parseUrl.php';

// Manejo de preflight requests para CORS
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
  header("HTTP/1.1 200 OK");
  exit(0);
}

// Configuración de encabezados para CORS y tipo de contenido
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json');

$url = $_SERVER['REQUEST_URI'];
$query_params = parse_url($url, PHP_URL_QUERY);

$actions = [
  'api/usuarios' => UserRoutes(new MySQLUsersService),
  'api/auth' => AuthRoutes(new MySQLUsersService),
  'api/estudiantes' => StudentRoutes(new MySQLStudentsService),
  'api/grupos' => GroupRoutes(new MySQLGroupsService),
  'api/empresas' => CompanyRoutes(new MySQLCompaniesService)
];

$method = $_SERVER['REQUEST_METHOD'];
$args = parsedUrl($_GET['url']);
parse_str($query_params, $params);
$params = empty($query_params) ? null : $params;



// $response = match (true) {
//   $args['path'] === 'api/auth' =>
//   $actions[$args['path']]($method, $case),
//   isset($_SESSION['usuario']) && $args['path'] !== 'api/auth' => $actions[$args['path']]($method, $args['id'], $params),
// };
// try {
// if ($args['path'] === 'api/auth') {
//   $case = $args['id'];
//   $response = $actions[$args['path']]($method, $case);

//   http_response_code($response->status_code);
//   echo json_encode($response->data);
// } elseif (isset($_SESSION['usuario']) && $args['path'] !== 'api/auth') {


$response = $actions[$args['path']]($method, $args['id'], $params);

http_response_code($response->status_code);
echo json_encode($response->data);
// } 
//   else {
//     throw new Exception('Unauthorized: No tienes permisos suficientes para realizar esta solicitud', 401);
//   }
// } catch (Exception $e) {
//   http_response_code($e->getCode());
//   $response = [
//     'success' => false,
//     'code' => $e->getCode(),
//     'message' => $e->getMessage()
//   ];
//   echo json_encode($response);
// }

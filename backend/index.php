<?php
session_start();

require_once 'routes/Users.php';
require_once 'routes/Auth.php';
require_once 'routes/Evaluations.php';
require_once 'routes/Judges.php';
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

$url = $_SERVER['REQUEST_URI'];

$query_params = parse_url($url, PHP_URL_QUERY);

$actions = [
  'api/usuarios' => UserRoutes(new MySQLUsersService),
  'api/login' => AuthRoutes(new MySQLUsersService),
  'api/logout' => AuthRoutes(new MySQLUsersService),
  'api/estudiantes' => StudentRoutes(new MySQLStudentsService),
  'api/grupos' => GroupRoutes(new MySQLGroupsService),
  'api/empresas' => CompanyRoutes(new MySQLCompaniesService)
];


$method = $_SERVER['REQUEST_METHOD'];
$url = $_SERVER['REQUEST_URI'];
$path = parse_url($url, PHP_URL_PATH);

$args = parsedUrl($_GET['url']);


parse_str($query_params, $params);

$params = empty($query_params) ? null : $params;

// if (!isset($_SESSION['usuario']) && strpos($args['path'], 'api/log')) {
//   $params = empty($query_params) ? null : $params;
//   $actions[$args['path']]($method, explode('/', $args['path'])[1]);
//   exit;
// }

if (isset($_SESSION['usuario'])) {
  $actions[$args['path']]($method, $args['id'], $params);

  if ($args['path'] === 'api/logout') {
    $actions[$args['path']]($method, 'logout');
  }

  exit;
} else {
  // header('Location: /proyecto-DAW/backend/api/login');
  // exit;
  if ($args['path'] === 'api/login') {
    $actions[$args['path']]($method, 'login');
  }
}

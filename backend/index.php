<?php

require_once 'routes/Users.php';
require_once 'routes/Evaluations.php';
require_once 'routes/Judges.php';
require_once 'routes/Projects.php';
require_once 'routes/Students.php';
require_once 'routes/Groups.php';
require_once 'routes/Companies.php';
require_once 'models/mysql/Usuarios.php';
require_once 'models/mysql/Proyectos.php';
require_once 'models/mysql/Estudiantes.php';
require_once 'models/mysql/Jurados.php';
require_once 'models/mysql/Evaluaciones.php';
require_once 'models/mysql/Grupos.php';
require_once 'models/mysql/Empresas.php';
require_once 'utils/parseUrl.php';

$url = $_SERVER['REQUEST_URI'];

$query_params = parse_url($url, PHP_URL_QUERY);


$actions = [
  'api/usuarios' => UserRoutes(new MySQLUsersService),
  // 'api/proyectos' => ProjectRoutes(new MySQLStudentsService),
  'api/estudiantes' => StudentRoutes(new MySQLStudentsService),
  'api/evaluaciones' => EvaluationRoutes(new MySQLUsersService),
  'api/jurados' => JudgeRoutes(new MySQLUsersService),
  'api/grupos' => GroupRoutes(new MySQLGroupsService),
  'api/empresas' => CompanyRoutes(new MySQLCompaniesService)
];


$method = $_SERVER['REQUEST_METHOD'];
$url = $_SERVER['REQUEST_URI'];
$path = parse_url($url, PHP_URL_PATH);

$args = parsedUrl($_GET['url']);

parse_str($query_params, $params);

$params = empty($query_params) ? null : $params;

$actions[$args['path']]($method, $args['id'], $params);

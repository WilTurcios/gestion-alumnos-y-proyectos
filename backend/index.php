<?php
session_start();

require_once 'utils/parseUrl.php';
require_once 'vendor/autoload.php';
require_once 'JWT/jwt.php';
require_once 'exceptions/UnauthorizedRequestException.php';

// Manejo de preflight requests para CORS
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
  header("HTTP/1.1 200 OK");
  header('Access-Control-Allow-Origin: *');
  header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS');
  header('Access-Control-Allow-Headers: Content-Type, Authorization');
  header('Content-Type: application/json');
  exit(0);
}

// Configuración de encabezados para CORS y tipo de contenido
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json');

function getBearerToken()
{
  $headers = getallheaders();
  if (isset($headers['Authorization']) && preg_match('/Bearer\s+(\S+)/', $headers['Authorization'], $matches)) {
    return $matches[1];
  }
  return null;
}
// $headers = getallheaders();
// function getBearerToken($headers)
// {
//   if (isset($headers['Authorization']) && preg_match(
//     '/Bearer\s+(\S+)/',
//     $headers['Authorization'],
//     $matches
//   )) {
//     return $matches[1];
//   }
//   return null;
// }



$jwt = getBearerToken();

$url = $_SERVER['REQUEST_URI'];
$query_params = parse_url($url, PHP_URL_QUERY);

$actions = [
  'api/usuarios' => 'routes/Users.php',
  'api/asesores' => 'routes/Assesors.php',
  'api/jurados' => 'routes/Judges.php',
  'api/auth' => 'routes/Auth.php',
  'api/estudiantes' => 'routes/Students.php',
  'api/grupos' => 'routes/Groups.php',
  'api/empresas' => 'routes/Companies.php',
  'api/materias' => 'routes/Subjects.php',
  'api/proyectos' => 'routes/Projects.php'
];

$method = $_SERVER['REQUEST_METHOD'];
$args = parsedUrl($_GET['url']);
parse_str($query_params, $params);
$params = empty($query_params) ? null : $params;

$id = $args['id'] ?? null;
$path = $args['path'] ?? null;

try {
  require_once $actions[$path];
} catch (mysqli_sql_exception $ex) {
  $errorCode = $ex->getCode();
  switch ($errorCode) {
    case 1062:
      http_response_code(400);
      echo json_encode([['message' => 'La llave debe ser única']]);
      break;
    case 1406:
      http_response_code(400);
      echo json_encode([['message' => 'Longitud del dato excedida']]);
      break;
    case 1451:
      echo "Error: Cannot delete or update a parent row: a foreign key constraint fails";
      http_response_code(400);
      echo json_encode([['message' => 'No puedes eliminar el registro debido a una restriccion de clave foranea']]);
      break;
    case 1452:
      http_response_code(400);
      echo json_encode([['message' => 'La clave foranea no existe']]);
      break;
    default:
      echo "Error: " . $ex->getMessage();
  }
} catch (ParameterIsMissingException $ex) {
  http_response_code($ex->getStatusCode());
  echo json_encode([['message' => $ex->getMessage()]]);
} catch (UnauthorizedRequestException $ex) {
  http_response_code($ex->getStatusCode());
  echo json_encode([['message' => $ex->getMessage()]]);
} catch (NotFoundException $ex) {
  http_response_code($ex->getStatusCode());
  echo json_encode([['message' => $ex->getMessage()]]);
} catch (InternalServerErrorException $ex) {
  http_response_code($ex->getStatusCode());
  echo json_encode([['message' => $ex->getMessage()]]);
} catch (BadRequestException $ex) {
  http_response_code($ex->getStatusCode());
  echo json_encode([['message' => $ex->getMessage()]]);
}

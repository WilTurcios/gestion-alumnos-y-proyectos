<?php
session_start();

require_once 'routes/Auth.php';
require_once 'models/mysql/Usuarios.php';

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


$method = $_SERVER['REQUEST_METHOD'];
parse_str($query_params, $params);
$params = empty($query_params) ? null : $params;


try {
  $response = AuthRoutes(new MySQLUsersService)($method, $_GET['url']);

  http_response_code($response->status_code);
  echo json_encode($response->data ?? []);
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
  http_response_code($ex->getCode());
  echo json_encode([['message' => $ex->getMessage()]]);
}

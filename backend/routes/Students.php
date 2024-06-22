<?php

require_once 'controllers/StudentController.php';
require_once 'models/Estudiantes.php';

use Controllers\StudentController;

if ((!$jwt || !validateJWT($jwt))) {
  throw new UnauthorizedRequestException('Unauthorized Request: La operación no puede realizarse ya que no estás logueado');
}


$studentsController = new StudentController(new StudentModel());

$response = null;

$json_data = file_get_contents('php://input');

$data = ($json_data && !empty($json_data)) ? json_decode($json_data, true) : null;

$nombre = null;
$carnet = null;
$pages = 0;
$ids = $data && array_key_exists('ids', $data) ?  $data['ids'] : null;

if (!is_null($params)) {
  $nombre = ($params && array_key_exists('nombre', $params)) ? strtolower($params['nombre']) : null;
  $carnet = (!$params && array_key_exists('carnet', $params)) ? $params['carnet'] : null;
  $pages = (!$params && array_key_exists('pages', $params)) ? $params['pages'] : 0;
}

switch (true) {
  case $method === 'GET' && $id:
    $response = $studentsController->getStudentByID($id);
    break;
  case $method === 'GET' && $nombre:
    $response = $studentsController->getStudentByName($nombre);
    break;
  case $method === 'GET' && $carnet:
    $response = $studentsController->getStudentByCarnet($carnet);
    break;
  case $method === 'GET':
    $response = $studentsController->getStudents();
    break;
  case $method === 'POST':
    $response = $studentsController->createStudent($data);
    break;
  case $method === 'PUT':
    $response = $studentsController->updateStudent($data);
    break;
  case $method === 'DELETE' && $ids !== null:
    $response = $studentsController->deleteManyStudents($ids);
    break;
  case $method === 'DELETE':
    $response = $studentsController->deleteStudent($data);
    break;
  default:
    $response = new Response(false, 500, 'Algo salió mal, por favor, intentalo de nuevo más tarde.');
    break;
}


http_response_code($response->status_code);
echo json_encode($response->data);

exit(0);

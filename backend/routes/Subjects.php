<?php

require_once 'controllers/SubjectController.php';
require_once 'models/Materias.php';

use Controllers\StudentController;
use Controllers\SubjectController;

$studentsController = new SubjectController(new SubjectModel());

$response = null;

$json_data = file_get_contents('php://input');

$data = ($json_data && !empty($json_data)) ? json_decode($json_data, true) : null;

$nombre = null;
$pages = 0;
$ids = $data && array_key_exists('ids', $data) ?  $data['ids'] : null;

if (!is_null($params)) {
  $nombre = ($params && array_key_exists('nombre', $params)) ? $params['nombre'] : null;
}

switch (true) {
  case $method === 'GET' && $id:
    $response = $studentsController->getSubjectById($id);
    break;
  case $method === 'GET' && $nombre:
    $response = $studentsController->getSubjectByName($nombre);
    break;
  case $method === 'GET':
    $response = $studentsController->getAllSubjects();
    break;
  case $method === 'POST':
    $response = $studentsController->createSubject($data);
    break;
  case $method === 'PUT':
    $response = $studentsController->updateSubject($data);
    break;
  case $method === 'DELETE' && $ids !== null:
    $response = $studentsController->deleteManySubjects($ids);
    break;
  case $method === 'DELETE':
    $response = $studentsController->deleteSubject($data);
    break;
  default:
    $response = new Response(false, 500, 'Algo salió mal, por favor, intentalo de nuevo más tarde.');
    break;
}


http_response_code($response->status_code);
echo json_encode($response->data);

exit(0);

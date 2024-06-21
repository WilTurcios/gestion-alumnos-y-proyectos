<?php

require_once 'controllers/ProjectController.php';
require_once 'models/Proyectos.php';

use Controllers\ProjectController;
use Controllers\StudentController;
use Controllers\SubjectController;

$studentsController = new ProjectController(new ProjectModel());

$response = null;

$json_data = file_get_contents('php://input');

$data = ($json_data && !empty($json_data)) ? json_decode($json_data, true) : null;

$tema = null;
$pages = 0;
$ids = $data && array_key_exists('ids', $data) ?  $data['ids'] : null;

if (!is_null($params)) {
  $tema = ($params && array_key_exists('tema', $params)) ? strtolower($params['tema']) : null;
}

switch (true) {
  case $method === 'GET' && $id:
    $response = $studentsController->getProjectById($id);
    break;
  case $method === 'GET' && $tema:
    $response = $studentsController->getProjectByTopic($tema);
    break;
  case $method === 'GET':
    $response = $studentsController->getAllProjects();
    break;
  case $method === 'POST':
    $response = $studentsController->createProject($data);
    break;
  case $method === 'PUT':
    $response = $studentsController->updateProject($data);
    break;
  case $method === 'DELETE' && $ids !== null:
    $response = $studentsController->deleteManyProjects($ids);
    break;
  case $method === 'DELETE':
    $response = $studentsController->deleteProject($data);
    break;
  default:
    $response = new Response(false, 500, 'Algo salió mal, por favor, intentalo de nuevo más tarde.');
    break;
}


http_response_code($response->status_code);
echo json_encode($response->data);

exit(0);

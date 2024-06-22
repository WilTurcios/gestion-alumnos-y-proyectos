<?php

require_once 'controllers/ProjectController.php';
require_once 'controllers/UserController.php';
require_once 'models/Proyectos.php';
require_once 'models/Usuarios.php';

use Controllers\ProjectController;
use Controllers\StudentController;
use Controllers\SubjectController;
use Controllers\UserController;

function getAuthenticatedUser($usersController)
{
  $jwt = getBearerToken();
  if ($jwt && ($decoded = validateJWT($jwt))) {
    $username = $decoded['data']->username;

    $usuario = $usersController->getByUsername($username);

    if ($usuario) {
      return $usuario->data[0];
    } else {
      return null;
    }
  }
}

$projectController = new ProjectController(new ProjectModel());

if ((!$jwt || !validateJWT($jwt))) {
  throw new UnauthorizedRequestException('Unauthorized Request: La operación no puede realizarse ya que no estás logueado');
}


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
    $response = $projectController->getProjectById($id);
    break;
  case $method === 'GET' && $tema:
    $response = $projectController->getProjectByTopic($tema);
    break;
  case $method === 'GET':
    $response = $projectController->getAllProjects();
    break;
  case $method === 'POST':
    $response = $projectController->createProject($data);
    break;
  case $method === 'PUT':
    $response = $projectController->updateProject(
      $data,
      getAuthenticatedUser(new UserController(new UserModel))
    );
    break;
  case $method === 'DELETE' && $ids !== null:
    $response = $projectController->deleteManyProjects(
      $ids,
      getAuthenticatedUser(new UserController(new UserModel))
    );
    break;
  case $method === 'DELETE':
    $response = $projectController->deleteProject(
      $data,
      getAuthenticatedUser(new UserController(new UserModel))
    );
    break;
  default:
    $response = new Response(false, 500, 'Algo salió mal, por favor, intentalo de nuevo más tarde.');
    break;
}


http_response_code($response->status_code);
echo json_encode($response->data);

exit(0);

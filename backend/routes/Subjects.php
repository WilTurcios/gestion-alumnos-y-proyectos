<?php

require_once 'controllers/SubjectController.php';
require_once 'models/Materias.php';
require_once 'controllers/UserController.php';
require_once 'models/Usuarios.php';

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

if ((!$jwt || !validateJWT($jwt))) {
  throw new UnauthorizedRequestException('Unauthorized Request: La operación no puede realizarse ya que no estás logueado');
}


$subjectController = new SubjectController(new SubjectModel());

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
    $response = $subjectController->getSubjectById($id);
    break;
  case $method === 'GET' && $nombre:
    $response = $subjectController->getSubjectByName($nombre);
    break;
  case $method === 'GET':
    $response = $subjectController->getAllSubjects();
    break;
  case $method === 'POST' && $id === 'agregar_criterio':
    $response = $subjectController->addCriterionToSubject(
      $data,
      getAuthenticatedUser(new UserController(new UserModel))
    );
    break;
  case $method === 'POST':
    $response = $subjectController->createSubject(
      $data,
      getAuthenticatedUser(new UserController(new UserModel))
    );
    break;
  case $method === 'PUT' && $id === 'actualizar_criterio':
    $response = $subjectController->updateCriterion(
      $data,
      getAuthenticatedUser(new UserController(new UserModel))
    );
    break;
  case $method === 'PUT':
    $response = $subjectController->updateSubject(
      $data,
      getAuthenticatedUser(new UserController(new UserModel))
    );
    break;
  case $method === 'DELETE' && $id === 'eliminar_criterio':
    $response = $subjectController->deleteCriterionById(
      $data,
      getAuthenticatedUser(new UserController(new UserModel))
    );
    break;
  case $method === 'DELETE' && $ids !== null:
    $response = $subjectController->deleteManySubjects($ids);
    break;
  case $method === 'DELETE':
    $response = $subjectController->deleteSubject($data);
    break;
  default:
    $response = new Response(false, 500, 'Algo salió mal, por favor, intentalo de nuevo más tarde.');
    break;
}


http_response_code($response->status_code);
echo json_encode($response->data);

exit(0);

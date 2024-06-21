<?php

require_once 'controllers/UserController.php';
require_once 'models/Usuarios.php';
require_once 'schemas/Response.php';

use Controllers\UserController;

$usersController = new UserController(new UserModel());

$response = null;

$json_data = file_get_contents('php://input');

$data = ($json_data && !empty($json_data)) ? json_decode($json_data, true) : null;

$nombres = null;
$apellidos = null;
$pages = null;
$ids = $data && array_key_exists('ids', $data) ?  $data['ids'] : null;


if (!is_null($params)) {
  $nombres = (!$params && array_key_exists('nombres', $params)) ? $params['nombres'] : null;
  $apellidos = (!$params && array_key_exists('apellidos', $params)) ? $params['apellidos'] : null;
  $pages = (!$params && array_key_exists('pages', $params)) ? $params['pages'] : 0;
}

switch (true) {
  case $method === 'GET' && $id:
    $response = $usersController->getUserByID($id);
    break;
  case $method === 'GET' && ($nombres || $apellidos):
    $response = $usersController->getUserByName($nombres, $apellidos);
    break;
  case $method === 'GET':
    $response = $usersController->getUsers();
    break;
  case $method === 'POST':
    $response = $usersController->createUser($data);
    break;
  case $method === 'PUT':
    $response = $usersController->updateUser($data);
    break;
  case $method === 'DELETE' && $id:
    $response = $usersController->deleteUserById($id);
    break;
  case $method === 'DELETE' && $ids !== null:
    $response = $usersController->deleteManyUsers($ids);
    break;
  default:
    $response = new Response(false, 500, 'Algo salió mal, por favor, intentalo de nuevo más tarde.');
    break;
}


http_response_code($response->status_code);
echo json_encode($response->data);

exit(0);

<?php

require_once 'controllers/UserController.php';
require_once 'schemas/Response.php';

use Controllers\UserController;

function UserRoutes($userService)
{
  return function (string $method, ?string $id, ?array $params) use ($userService) {
    $usersController = new UserController($userService);

    $response = new Response(false, 500, 'Algo salió mal, por favor, intentalo de nuevo más tarde.');

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

    $response = match (true) {
      $method === 'GET' && $id => $usersController->getUserByID($id),
      $method === 'GET' && ($nombres || $apellidos) => $usersController->getUserByName($nombres, $apellidos),
      $method === 'GET' => $usersController->getUsers(),
      $method === 'POST' => $usersController->createUser($data),
      $method === 'PUT' => $response = $usersController->updateUser($data),
      $method === 'DELETE' && $ids !== null => $usersController->deleteManyUsers($ids),
      $method === 'DELETE' => $usersController->deleteUser($data)
    };

    return $response;
  };
}

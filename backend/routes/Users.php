<?php

require_once 'controllers/UsersController.php';

use Controllers\UsersController;

function UserRoutes(IUserService $userService)
{
  return function (string $method, ?string $id, ?array $params) use ($userService) {
    $usersController = new UsersController($userService);

    $response = array('status' => 'failed', 'message' => 'Invalid Request');

    $json_data = file_get_contents('php://input');

    $data = ($json_data && !empty($json_data)) ? json_decode($json_data, true) : null;

    if (!is_null($params)) {
      $nombres = (!$params && array_key_exists('nombres', $params)) ? $params['nombres'] : null;
      $apellidos = (!$params && array_key_exists('apellidos', $params)) ? $params['apellidos'] : null;
      $pages = (!$params && array_key_exists('pages', $params)) ? $params['pages'] : 0;
    }

    $response = match (true) {
      $method === 'GET' && $id => $usersController->getUserByID($id),
      $method === 'GET' && $id => $usersController->getUserByName($nombres, $apellidos),
      $method === 'GET' => $usersController->getUsers(),
      $method === 'POST' => $usersController->createUser($data),
      $method === 'PUT' => $response = $usersController->updateUser($data),
      $method === 'DELETE' => $usersController->deleteUser($data)
    };

    header('Content-Type: application/json');
    http_response_code(200);
    echo json_encode($response);
  };
}

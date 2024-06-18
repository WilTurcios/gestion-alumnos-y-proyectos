<?php

use Controllers\UsersController;

function EvaluationRoutes(IUserService $userService)
{
  return function (string $method) use ($userService) {
    $usersController = new UsersController($userService);

    $response = array('status' => 'failed', 'message' => 'Invalid Request');

    if ($method === 'GET') {
      $response = $usersController->getUsers();
    }

    if ($method === 'POST') {
      $json_data = file_get_contents('php://input');
      $data = json_decode($json_data, true);
      $response = $usersController->createUser($data);
    }

    if ($method === 'PUT') {
      parse_str(file_get_contents('php://input'), $_PUT);
      $response = $usersController->updateUser($_PUT);
    }

    if ($method === 'DELETE') {
      parse_str(file_get_contents('php://input'), $_DELETE);
      $response = $usersController->deleteUser($_DELETE);
    }

    header('Content-Type: application/json');
    http_response_code(200);
    echo json_encode($response->data);
  };
}

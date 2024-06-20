<?php

require_once 'controllers/GroupController.php';

use Controllers\GroupController;

function GroupRoutes($groupService): callable
{
  return function (string $method, ?string $id, ?array $params) use ($groupService): Response {
    $groupsController = new GroupController($groupService);
    $response = ['status' => 'failed', 'message' => 'Invalid Request'];

    $json_data = file_get_contents('php://input');
    $data = $json_data ? json_decode($json_data, true) : null;
    $nombre_grupo = $params['nombre_grupo'] ?? null;
    $ids = $data && array_key_exists('ids', $data) ?  $data['ids'] : null;

    $response = match (true) {
      $method === 'GET' => $groupsController->getAllGroups(),
      $method === 'GET' && ($nombre_grupo !== null && !empty($nombre_grupo)) => $groupsController->getGroupByName($nombre_grupo),
      $method === 'GET' && $id !== null => $groupsController->getGroupByID((int)$id),
      $method === 'POST' => $groupsController->createGroup($data),
      $method === 'PUT' => $groupsController->updateGroup($data),
      $method === 'DELETE' && $id !== null => $groupsController->deleteGroup($id),
      $method === 'DELETE' && $ids !== null => $groupsController->deleteGroup($ids),
      $method === 'DELETE' => $groupsController->deleteAllGroups(),
      default => $response,
    };

    return $response;
  };
}

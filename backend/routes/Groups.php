<?php

require_once 'controllers/GroupsController.php';

use Controllers\GroupsController;

function GroupRoutes(IGroupService $groupService)
{
  return function (string $method, ?string $id, ?array $params) use ($groupService) {
    $groupsController = new GroupsController($groupService);

    $response = array('status' => 'failed', 'message' => 'Invalid Request');

    $json_data = file_get_contents('php://input');

    $data = ($json_data && !empty($json_data)) ? json_decode($json_data, true) : null;

    $nombre_grupo = null;

    if (!is_null($params)) {
      $nombre_grupo = ($params && array_key_exists('nombre_grupo', $params)) ? $params['nombre_grupo'] : null;
    }

    $response = match (true) {
      $method === 'GET' && !is_null($nombre_grupo) => $groupsController->getGroupByName($nombre_grupo),
      $method === 'GET' && $id => $groupsController->getGroupByID($id),
      $method === 'GET' => $groupsController->getAllGroups(),
      $method === 'POST' => $groupsController->createGroup($data),
      $method === 'PUT' => $groupsController->updateGroup($data),
      $method === 'DELETE' && $id => $groupsController->deleteGroup($id),
      $method === 'DELETE' => $groupsController->deleteAllGroups()
    };

    return $response;
  };
}

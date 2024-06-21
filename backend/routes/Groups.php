<?php

require_once 'controllers/GroupController.php';
require_once 'models/Grupos.php';

use Controllers\GroupController;

$groupsController = new GroupController(new GroupModel());
$response = ['status' => 'failed', 'message' => 'Invalid Request'];

$json_data = file_get_contents('php://input');
$data = $json_data ? json_decode($json_data, true) : null;
$nombre = isset($params['nombre']) ? trim($params['nombre']) :  null;
$ids = $data && array_key_exists('ids', $data) ?  $data['ids'] : null;

switch (true) {
  case $method === 'GET' && ($nombre !== null && !empty($nombre)):
    $response = $groupsController->getGroupByName($nombre);
    break;
  case $method === 'GET' && $id !== null:
    $response = $groupsController->getGroupByID((int)$id);
    break;
  case $method === 'GET':
    $response = $groupsController->getAllGroups();
    break;
  case $method === 'POST':
    $response = $groupsController->createGroup($data);
    break;
  case $method === 'PUT':
    $response = $groupsController->updateGroup($data);
    break;
  case $method === 'DELETE' && $id !== null:
    $response = $groupsController->deleteGroup($id);
    break;
  case $method === 'DELETE' && $ids !== null:
    $response = $groupsController->deleteGroup($ids);
    break;
  case $method === 'DELETE':
    $response = $groupsController->deleteAllGroups();
    break;
  default:
    $response = new Response(false, 500, 'Algo salió mal, por favor, intentalo de nuevo más tarde.');
    break;
}

http_response_code($response->status_code);
echo json_encode($response->data);

exit(0);

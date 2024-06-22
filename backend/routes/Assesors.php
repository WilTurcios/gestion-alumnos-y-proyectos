<?php

require_once 'controllers/UserController.php';
require_once 'models/Usuarios.php';
require_once 'schemas/Response.php';

use Controllers\UserController;

if ((!$jwt || !validateJWT($jwt))) {
  throw new UnauthorizedRequestException('Unauthorized Request: La operación no puede realizarse ya que no estás logueado');
}

$usersController = new UserController(new UserModel());

$response = null;

$json_data = file_get_contents('php://input');

$data = ($json_data && !empty($json_data)) ? json_decode($json_data, true) : null;

$pages = null;
$ids = $data && array_key_exists('ids', $data) ?  $data['ids'] : null;

switch (true) {
  case $method === 'GET':
    $response = $usersController->getAssesors();
    break;
  default:
    $response = new Response(false, 500, 'Algo salió mal, por favor, intentalo de nuevo más tarde.');
    break;
}


http_response_code($response->status_code);
echo json_encode($response->data);

exit(0);

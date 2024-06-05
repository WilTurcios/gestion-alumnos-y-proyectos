<?php

require_once 'controllers/UsersController.php';

use Controllers\UsersController;

function logOut()
{
  if (isset($_SESSION['usuario'])) {
    unset($_SESSION['usuario']);
  }
  return new Response(true, 'Ha cerrado sesión correctamente');
}

function logIn($user_name, $clave, $usersController)
{
  $result = $usersController->authenticateUser($user_name, $clave);

  if ($result->ok)
    $_SESSION['usuario'] = $result->data[0];

  return $result;
}


function AuthRoutes(IUserService $userService)
{
  return function (string $method, ?string $action) use ($userService) {
    // echo 'HERE IT GOES' . $action;
    $usersController = new UsersController($userService);

    $response = new Response(false, 'El usuario no se pudo autenticar');

    $json_data = file_get_contents('php://input');

    $data = ($json_data && !empty($json_data)) ? json_decode($json_data, true) : null;


    if ($method !== 'POST') return new Response(
      false,
      'Para autenticarse o cerrar sesión se debe hacer uso del metodo POST'
    );


    $response = ($action === 'login')
      ? logIn($data['user_name'], $data['clave'], $usersController)
      : logOut();

    header('Content-Type: application/json');
    http_response_code(200);
    echo json_encode($response);
  };
}

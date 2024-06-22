<?php

require_once 'controllers/UserController.php';
require_once 'models/Usuarios.php';
require_once 'JWT/jwt.php';

use Controllers\UserController;


function logOut()
{
  return new Response(true, 203, 'Ha cerrado sesión correctamente');
}

function logIn($user_name, $clave, $usersController)
{

  $result = $usersController->authenticateUser($user_name, $clave);

  if ($result->ok) {
    $token = createJWT($result->data[0]->usuario);
    $_SESSION['usuario'] = $result->data[0];
    return new Response(true, 200, 'Se ha autenticado exitoramente', ['token' => $token]);
  } else {
    throw new UnauthorizedRequestException("Unauthorized Error: Usuario no autorizado");
  }

  return $result;
}

function getAuthenticatedUser($usersController)
{
  $jwt = getBearerToken();
  if ($jwt && ($decoded = validateJWT($jwt))) {
    $username = $decoded['data']->username;

    $usuario = $usersController->getByUsername($username);

    if ($usuario) {
      return new Response(true, 200, 'Usuario obtenido exitosamente', $usuario->data);
    } else {
      throw new NotFoundException('Usuario no encontrado');
    }
  } else {
    throw new UnauthorizedRequestException(
      'Unauthorized Request: No tienes los permisos suficientes para realizar esta consulta'
    );
  }
  if (!isset($_SESSION['usuario'])) return new Response(
    false,
    401,
    'Unauthorized: No tienes los permisos necesarios para acceder a este recurso'
  );

  return new Response(true, 200, 'Usuario autenticado obtenido exitosamente', [$_SESSION['usuario']]);
}

function changePassword($new_password, $current_password)
{
  return new Response(true, 203, 'La contraseña se ha cambiado correctamente');
}

$usersController = new UserController(new UserModel());

$response = new Response(false, 500, 'El usuario no se pudo autenticar');

$json_data = file_get_contents('php://input');

$data = ($json_data && !empty($json_data)) ? json_decode($json_data, true) : null;


if ($method !== 'POST') throw new BadRequestException(
  'Para autenticarse o cerrar sesión se debe hacer uso del metodo POST'
);

$action = $id;

switch ($action) {
  case 'login':
    $response = logIn($data['user_name'], $data['clave'], $usersController);
    break;
  case 'usuario_autenticado':
    $response = getAuthenticatedUser($usersController);
    break;
  case 'logout':
    $response = logout();
    break;
  case 'change_password':
    $response = changePassword($data['new_password'], $data['current_password']);
    break;
  default:
    // Manejar el caso en que la acción no coincide con ninguna opción
    throw new InternalServerErrorException('Internal Server Error: No se pudo realizar la acción');
    break;
}


http_response_code($response->status_code);
echo json_encode($response->data);

exit(0);

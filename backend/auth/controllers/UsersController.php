<?php

namespace Controllers;

require_once 'schemas/Response.php';
require_once 'schemas/Usuario.php';

use IUserService;
use Response;
use Usuario;

class UsersController
{
  private array $requiredParameters = [
    'nombre_usuario',
    'nombres',
    'apellidos',
    'clave',
    'carnet_docente',
    'email'
  ];
  private array $optionalParameters = [
    'tel',
    'celular',
    'es_jurado',
    'es_asesor',
    'acceso_sistema',
    'es_admin'
  ];

  public function __construct(private IUserService $userService)
  {
  }

  private function getRequiredParameters()
  {
    return $this->requiredParameters;
  }

  private function getOptionalParameters()
  {
    return $this->optionalParameters;
  }

  public function createUser(array $user_data/*, Usuario $admin*/): Response
  {
    $requiredParameters = $this->getRequiredParameters();

    $response = null;

    foreach ($requiredParameters as $key) {
      if (!array_key_exists($key, $user_data)) {
        $response = new Response(
          false,
          400,
          'Bad Request: Asegurese de proporcionar todos los campos necesarios para agregar un usuario'
        );

        break;
      }

      if (empty(trim($user_data[$key]))) {
        $response = new Response(
          false,
          400,
          'Bad Request: Asegurese de que los campos obligatorios no estén vacios'
        );

        break;
      }
    }

    if ($response) return $response;

    $new_user = new Usuario(
      null,
      $user_data['nombre_usuario'],
      $user_data['clave'],
      $user_data['nombres'],
      $user_data['apellidos'],
      $user_data['carnet_docente'],
      $user_data['email'],
      $user_data['tel'],
      $user_data['celular'],
      $user_data['es_jurado'] ?? false,
      $user_data['es_asesor'] ?? false,
      $user_data['acceso_sistema'] ?? false,
      $user_data['es_admin'] ?? false
    );

    $result = $this->userService->save($new_user);

    if ($result instanceof Usuario) {
      $responseBody["result"] = $result;
      return new Response(true, 200, 'El usuario se ha creado exitosamente', [$result]);
    } else {
      return new Response(
        false,
        'Ha ocurrido un error al crear el usuario, por favor intentelo de nuevo.'
      );
    }
  }

  public function deleteUser(array $user_data/*, Usuario $admin*/): Response
  {
    $userId = $user_data['id'] ?? null;

    if (!$userId) {
      return new Response(
        false,
        400,
        'Bad Request: Asegurate de proporcionar los datos necesarios para la eliminación del usuario.'
      );
    }

    $usuario = new Usuario($userId);

    $result = $this->userService->delete($usuario);

    if ($result instanceof Usuario) {
      return new Response(true, 200, 'El usuario ha sido eliminado correctamente', [$result]);
    } else {
      return new Response(
        false,
        500,
        'Ha ocurrido un error al eliminar el usuario, por favor intentelo de nuevo'
      );
    }
  }

  public function updateUser(array $user_data/*, Usuario $admin*/): Response
  {
    $userID = $user_data['id'] ?? null;

    if (!$userID) return new Response(
      false,
      400,
      'Bad Request: Asegurate de proporcionar los datos necesarios para actualizar al usuario.'
    );

    $user_to_update = new Usuario(
      $userID,
      $user_data['nombre_usuario'] ?? null,
      $user_data['clave'] ?? null,
      $user_data['nombres'] ?? null,
      $user_data['apellidos'] ?? null,
      $user_data['carnet_docente'] ?? null,
      $user_data['email'] ?? null,
      $user_data['tel'] ?? null,
      $user_data['celular'] ?? null,
      $user_data['es_jurado'] ?? false,
      $user_data['es_asesor'] ?? false,
      $user_data['acceso_sistema'] ?? false,
      $user_data['es_admin'] ?? false
    );

    $updated_user = $this->userService->update($user_to_update);

    if (!$updated_user) return new Response(
      false,
      500,
      'Ha ocurrido un error al actualizar el usuario'
    );

    return new Response(
      true,
      201,
      'Usuario actualizado exitosamente',
      [$updated_user]
    );
  }

  public function getUsers(): Response
  {
    $result = $this->userService->getAll();

    if (!$result) return new Response(
      false,
      500,
      'Ha ocurrido un error al tratar de obtener los usuarios.'
    );

    return new Response(true, 200, 'Usuarios obtenidos exitosamente.', $result);
  }

  public function deleteManyUsers(?array $users_ids): Response
  {
    if (is_null($users_ids)) {
      return new Response(
        false,
        400,
        'Bad Request: Asegúrate de proporcionar los datos necesarios para la eliminación de los usuarios'
      );
    }

    if (!is_array($users_ids)) {
      return new Response(
        false,
        400,
        'Bad Request: Asegúrate de proporcionar un array de IDs para eliminar los usuarios'
      );
    }

    foreach ($users_ids as $grupo_id) {
      if (!is_integer($grupo_id)) {
        return new Response(
          false,
          400,
          'Bad Request: Asegúrate de que todos los IDs proporcionados sean enteros'
        );
      }
    }

    $result = $this->userService->deleteMany($users_ids);

    if (!$result) {
      return new Response(
        false,
        500,
        'Ha ocurrido un error al eliminar los usuarios, por favor inténtelo de nuevo'
      );
    }

    return new Response(true, 204, 'Los usuarios han sido eliminados correctamente');
  }

  public function getUserByName(string $nombres, string $apellidos): Response
  {
    $result = $this->userService->getByName($nombres, $apellidos);

    if (!$result) return new Response(
      false,
      500,
      'Ha ocurrido un error al obtener los usuarios'
    );

    return new Response(true, 200, 'Registros obtenidos exitosamente', $result);
  }

  public function getUserByID(?int $id_usuario): Response
  {
    if (!is_integer($id_usuario)) return new Response(
      false,
      'Asegurate de proporcionar los datos correctamente'
    );

    $usuario = new Usuario($id_usuario);
    $result = $this->userService->getById($usuario);

    if (!$result) return new Response(
      false,
      500,
      'Ha ocurrido un error al obtener el usuario'
    );

    return new Response(true, 200, 'Usuario obtenido exitosamente', [$result]);
  }

  public function authenticateUser(?string $user_name, ?string $clave): Response
  {
    if (!$user_name || !$clave) return new Response(
      false,
      400,
      'Bad Request: Todos los campos son requeridos.'
    );

    if (empty(trim($user_name)) || empty(trim($clave))) return new Response(
      false,
      400,
      'Bad Request: Asegurate de que los campos no estén vacios.'
    );

    $result = $this->userService->authenticate($user_name, $clave);

    if (!$result) return new Response(
      false,
      500,
      'Autenticación fallida. Por favor, intentalo de nuevo.'
    );

    return new Response(true, 200, 'Se ha autenticado exitósamente.', [$result]);
  }
}

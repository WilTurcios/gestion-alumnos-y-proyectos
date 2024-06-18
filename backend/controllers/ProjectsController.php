<?php

namespace Controllers;

require_once 'schemas/Response.php';
require_once 'schemas/Usuario.php';

use IUserService;
use Response;
use Usuario;

// class ProjectsController
// {
//   public function __construct(private IUserService $userService)
//   {
//   }

//   public function createUser(array $user_data/*, Usuario $admin*/): Response
//   {
//     $new_user = new Usuario(
//       null,
//       $user_data['nombre_usuario'] ?? '',
//       $user_data['clave'] ?? '',
//       $user_data['nombres'] ?? '',
//       $user_data['apellidos'] ?? '',
//       $user_data['carnet_docente'] ?? '',
//       $user_data['email'] ?? '',
//       $user_data['tel'] ?? '',
//       $user_data['celular'] ?? '',
//       $user_data['level'] ?? '',
//       $user_data['es_jurado'] ?? false,
//       $user_data['es_asesor'] ?? false,
//       $user_data['acceso_sistema'] ?? false,
//       $user_data['es_admin'] ?? false
//     );

//     $result = $this->userService->save($new_user);

//     if ($result instanceof Usuario) {
//       $responseBody["result"] = $result;
//       return new Response(true, 'El usuario se ha creado exitosamente', [$result]);
//     } else {
//       return new Response(
//         false,
//         'Ha ocurrido un error al crear el usuario, por favor intentelo de nuevo.'
//       );
//     }
//   }

//   public function deleteUser(array $user_data/*, Usuario $admin*/): Response
//   {
//     $userId = $user_data['id'] ?? null;
//     // $isAdmin = $admin['es_admin'] ?? null;
//     // $hasAccess = $admin['acceso_sistema'] ?? null;

//     if (!$userId) {
//       return new Response(
//         false,
//         'Asegurate de proporcionar los datos necesarios para la eliminación del un usuario.'
//       );
//     }

//     // if (!$isAdmin) {
//     //   return new Response(
//     //     false,
//     //     'No tienes los permisos necesarios para realizar esta acción'
//     //   );
//     // }

//     // if (!$hasAccess) {
//     //   return new Response(
//     //     false,
//     //     'Han revocado tu acceso al sistema.'
//     //   );
//     // }

//     $usuario = new Usuario($userId);

//     $result = $this->userService->delete($usuario);

//     if ($result instanceof Usuario) {
//       $responseBody["result"] = $result;
//       return new Response(true, 'El usuario ha sido eliminado correctamente', [$result]);
//     } else {
//       return new Response(
//         false,
//         'Ha ocurrido un error al eliminar el usuario, por favor intentelo de nuevo'
//       );
//     }
//   }

//   public function updateUser(array $user_data/*, Usuario $admin*/): Response
//   {
//     $userID = $user_data['id'] ?? null;

//     if (!$userID) return new Response(
//       false,
//       'Asegurate de proporcionar los datos necesarios para actualizar al usuario.'
//     );

//     $user_to_update = new Usuario(
//       $userID,
//       $user_data['nombre_user_data'] ?? null,
//       $user_data['clave'] ?? null,
//       $user_data['nombres'] ?? null,
//       $user_data['apellidos'] ?? null,
//       $user_data['carnet_docente'] ?? null,
//       $user_data['email'] ?? null,
//       $user_data['tel'] ?? null,
//       $user_data['celular'] ?? null,
//       $user_data['level'] ?? null,
//       $user_data['es_jurado'] ?? false,
//       $user_data['es_asesor'] ?? false,
//       $user_data['acceso_sistema'] ?? false,
//       $user_data['es_admin'] ?? false
//     );

//     $updated_user = $this->userService->update($user_to_update);

//     if (!$updated_user) return new Response(
//       false,
//       'Ha ocurrido un error al actualizar el usuario'
//     );

//     return new Response(
//       true,
//       'Usuario actualizado exitosamente',
//       [$updated_user]
//     );
//   }

//   public function getUsers(): Response
//   {
//     $responseBody = [
//       "error" => false,
//       "error_message" => "",
//       "result" => []
//     ];

//     $result = $this->userService->getAll();

//     if (!$result) return new Response(
//       false,
//       'Ha ocurrido un error al tratar de obtener los usuarios.'
//     );


//     return new Response(true, 'Usuarios obtenidos exitosamente.', $result);
//   }

//   public function getUserByName(string $nombres, string $apellidos): Response
//   {
//     $result = $this->userService->getByName($nombres, $apellidos);

//     if (!$result) return new Response(
//       false,
//       'Ha ocurrido un error al obtener los usuarios'
//     );

//     return new Response(true, 'Registros obtenidos exitosamente', $result);
//   }

//   public function getUserByID(int $id_usuario): Response
//   {
//     if (!is_integer($id_usuario)) return new Response(
//       false,
//       'Asegurate de proporcionar los datos correctamente'
//     );

//     $usuario = new Usuario($id_usuario);
//     $result = $this->userService->getById($usuario);

//     if (!$result) return new Response(
//       false,
//       'Ha ocurrido un error al obtener el usuario'
//     );

//     return new Response(true, 'Usuario obtenido exitosamente', [$result]);
//   }
// }

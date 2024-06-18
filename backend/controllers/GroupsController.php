<?php

namespace Controllers;

require_once 'schemas/Response.php';
require_once 'schemas/Grupo.php';
require_once 'interfaces/IGroupService.php';

use IGroupService;
use Response;
use Grupo;

class GroupsController
{
  public function __construct(private IGroupService $groupService)
  {
  }

  public function createGroup(array $group_data): Response
  {
    if (!array_key_exists('grupo', $group_data)) return new Response(
      false,
      400,
      'Bad Request: Asegurate de proporcionar un nombre para el grupo a crear'
    );

    if (empty(trim($group_data['grupo']))) return new Response(
      false,
      400,
      'Bad Request: Asegurate de que el campo el campo no esté vacío'
    );

    $new_group = new Grupo(null, $group_data['grupo']);

    $result = $this->groupService->save($new_group);

    if ($result instanceof Grupo) {
      return new Response(true, 200, 'El grupo se ha registrado exitosamente', [$result]);
    } else {
      return new Response(
        false,
        'Ha ocurrido un error al crear el grupo, por favor intentelo de nuevo.'
      );
    }
  }

  public function deleteGroup(?int $grupo_id): Response
  {
    if (is_null($grupo_id)) return new Response(
      false,
      400,
      'Bad Request: Asegurate de proporcionar los datos necesarios para la eliminación del grupo'
    );

    if (!is_integer($grupo_id)) return new Response(
      false,
      400,
      'Bad Request: Asegurate de proporcionar un ID adecuado para eliminar el grupo'
    );

    $group = new Grupo($grupo_id);

    $result = $this->groupService->delete($group);

    if (!$result) return new Response(
      false,
      'Ha ocurrido un error al eliminar el grupo, por favor intentelo de nuevo'
    );


    return new Response(true, 204, 'El grupo ha sido eliminado correctamente');
  }

  public function getGroupByID(?int $grupo_id): Response
  {
    if (is_null($grupo_id)) return new Response(
      false,
      400,
      'Bad Request: Asegurate de proporcionar los datos necesarios para obtener el grupo'
    );

    if (!is_integer($grupo_id)) return new Response(
      false,
      400,
      'Bad Request: Asegurate de proporcionar un ID adecuado para la obtención del grupo'
    );


    $result = $this->groupService->getById($grupo_id);

    if (!$result) return new Response(
      false,
      500,
      'Ha ocurrido un error al obtener el grupo'
    );

    return new Response(true, 200, 'Grupo obtenido exitosamente', [$result]);
  }

  public function updateGroup(array $group_data): Response
  {
    $group_id = $group_data['id'] ?? null;

    if (!$group_id) {
      return new Response(
        false,
        400,
        'Asegúrate de proporcionar los datos necesarios para actualizar la empresa.'
      );
    }

    $grupo = new Grupo(
      $group_id,
      $group_data['grupo']
    );

    $result = $this->groupService->update($grupo);

    if ($result instanceof Grupo) {
      return new Response(true, 201, 'El grupo ha sido actualizada exitosamente');
    } else {
      return new Response(
        false,
        500,
        'Ha ocurrido un error al actualizar el grupo, por favor intenta de nuevo'
      );
    }
  }


  public function getAllGroups(): Response
  {
    $result = $this->groupService->getAll();

    if (!$result) return new Response(
      false,
      500,
      'Ha ocurrido un error al tratar de obtener los grupos.'
    );

    return new Response(true, 200, 'Grupos obtenidos exitosamente.', $result);
  }

  public function deleteAllGroups(): Response
  {
    $result = $this->groupService->deleteAll();

    if (!$result) return new Response(
      false,
      500,
      'Ha ocurrido un error al eliminar todos los grupos.'
    );

    return new Response(true, 201, 'Todos los grupos han sido eliminados correctamente.');
  }

  public function getGroupByName(string $nombre_grupo): Response
  {
    if (empty(trim($nombre_grupo))) return new Response(
      false,
      400,
      'Bad Request: Asegurate de proporcionar los datos necesarios para la obtención del grupo'
    );

    if (is_null($nombre_grupo)) return new Response(
      false,
      400,
      'Bad Request: Asegurate de que el campo no esté vacío'
    );

    $result = $this->groupService::getByName($nombre_grupo);

    if (!$result) return new Response(
      false,
      500,
      'Ha ocurrido un error al obtener el grupo'
    );

    return new Response(true, 200, 'Grupo obtenido exitosamente', [$result]);
  }
}

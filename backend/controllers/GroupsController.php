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
      'Asegurate de proporcionar un nombre para el grupo a crear'
    );

    $new_group = new Grupo(null, $group_data['grupo']);

    $result = $this->groupService->save($new_group);

    if ($result instanceof Grupo) {
      return new Response(true, 'El grupo se ha creado exitosamente', [$result]);
    } else {
      return new Response(
        false,
        'Ha ocurrido un error al crear el grupo, por favor intentelo de nuevo.'
      );
    }
  }

  public function deleteGroup(?int $grupo_id): Response
  {

    // if (!$grupo_id || empty($grupo_id)) return new Response(
    //   false,
    //   'Asegurate de proporcionar los datos necesarios para la eliminación del grupo'
    // );

    if (!is_integer($grupo_id)) return new Response(
      false,
      'Asegurate de proporcionar los datos necesarios para la eliminación del grupo'
    );

    $group = new Grupo($grupo_id);

    $result = $this->groupService->delete($group);

    if (!$result) return new Response(
      false,
      'Ha ocurrido un error al eliminar el grupo, por favor intentelo de nuevo'
    );


    return new Response(true, 'El grupo ha sido eliminado correctamente', [$result]);
  }

  public function getGroupByID(?int $grupo_id): Response
  {
    if (!$grupo_id) return new Response(
      false,
      'Asegurate de proporcionar un nombre para el grupo a crear'
    );

    $result = $this->groupService->getById($grupo_id);

    if (!$result) return new Response(
      false,
      'Ha ocurrido un error al obtener el grupo'
    );

    return new Response(true, 'Grupo obtenido exitosamente', [$result]);
  }

  public function getAllGroups(): Response
  {
    $result = $this->groupService->getAll();

    if (!$result) return new Response(
      false,
      'Ha ocurrido un error al tratar de obtener los grupos.'
    );

    return new Response(true, 'Grupos obtenidos exitosamente.', $result);
  }

  public function deleteAllGroups(): Response
  {
    $result = $this->groupService->deleteAll();

    if (!$result) return new Response(
      false,
      'Ha ocurrido un error al eliminar todos los grupos.'
    );

    return new Response(true, 'Todos los grupos han sido eliminados correctamente.', []);
  }

  public function getGroupByName(string $nombre_grupo): Response
  {
    $result = $this->groupService->getByName($nombre_grupo);

    if (!$result) return new Response(
      false,
      'Ha ocurrido un error al obtener el grupo'
    );

    return new Response(true, 'Grupo obtenido exitosamente', [$result]);
  }
}

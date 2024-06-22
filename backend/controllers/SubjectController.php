<?php

namespace Controllers;

require_once 'schemas/Response.php';
require_once 'schemas/Criterio.php';
require_once 'models/Materias.php';
require_once 'models/Usuarios.php';
require_once 'exceptions/ParameterIsMissingException.php';
require_once 'exceptions/UnauthorizedRequestException.php';
require_once 'exceptions/BadRequestException.php';
require_once 'exceptions/NotFoundException.php';
require_once 'exceptions/InternalServerErrorException.php';

use BadRequestException;
use Criterio;
use InternalServerErrorException;
use Response;
use Materia;
use InternalServerErrorExeception;
use NotFoundException;
use ParameterIsMissingException;
use SubjectModel;
use UnauthorizedRequestException;
use UserModel;
use Usuario;

class SubjectController
{
  private array $requiredParameters = [
    'nombre',
    'porcentaje',
    'porcentaje_individual',
    'porcentaje_grupal',
    'fecha_inicio',
    'fecha_fin',
    'activo',
    'year',
    'tipo'
  ];

  private array $requiredCriterionParameters = [
    'id_materia',
    'criterio',
    'tipo',
    'estado',
    'porcentaje'
  ];

  public function __construct(private $materiaService)
  {
  }

  public function getRequiredParameters()
  {
    return $this->requiredParameters;
  }
  public function getRequiredCriterionParameters()
  {
    return $this->requiredCriterionParameters;
  }

  public function createSubject(array $materia_data, $usuario): Response
  {
    $requiredParameters = $this->getRequiredParameters();

    $response = null;

    foreach ($requiredParameters as $key) {
      if (!array_key_exists($key, $materia_data)) {
        throw new ParameterIsMissingException(
          'Bad Request: Asegurese de proporcionar todos los campos necesarios para agregar una materia',
          400
        );

        break;
      }

      if (empty(trim($materia_data[$key]))) {
        throw new ParameterIsMissingException(
          'Bad Request: Asegurese de que los campos obligatorios no estén vacios',
          400
        );

        break;
      }
    }

    if ($response) return $response;

    $materia = new Materia(
      null,
      $materia_data['nombre'],
      $materia_data['porcentaje'],
      $materia_data['porcentaje_individual'],
      $materia_data['porcentaje_grupal'],
      $materia_data['fecha_inicio'],
      $materia_data['fecha_fin'],
      $materia_data['activo'],
      $materia_data['year'],
      $materia_data['tipo'],
      $usuario
    );

    $result = $this->materiaService->save($materia);

    if ($result instanceof Materia) {
      return new Response(true, 200, 'La materia se ha agregado exitosamente', [$result]);
    } else {
      throw new InternalServerErrorExeception(
        'Internal Server Error: Ha ocurrido un error al agregar la materia, por favor intenta de nuevo.'
      );
    }
  }
  public function addCriterionToSubject(?array $data, $usuario): Response
  {
    $requiredParameters = $this->getRequiredCriterionParameters();

    $response = null;

    foreach ($requiredParameters as $key) {
      if (!array_key_exists($key, $data)) {
        throw new ParameterIsMissingException(
          'Bad Request: Asegurese de proporcionar todos los campos necesarios para agregar una materia',
          400
        );

        break;
      }

      if (empty(trim($data[$key]))) {
        throw new ParameterIsMissingException(
          'Bad Request: Asegurese de que los campos obligatorios no estén vacios',
          400
        );

        break;
      }
    }

    if ($response) return $response;

    $materia = new Criterio(
      null,
      $data['criterio'],
      $data['porcentaje'],
      $data['tipo'],
      $data['estado'],
      $usuario
    );

    $result = $this->materiaService->addCriterion($data['id_materia'], $materia);

    if ($result instanceof Criterio) {
      return new Response(true, 200, 'El criterio se ha agregado exitosamente', [$result]);
    } else {
      throw new InternalServerErrorExeception(
        'Internal Server Error: Ha ocurrido un error al agregar el criterio, por favor intenta de nuevo.'
      );
    }
  }

  public function deleteCriterionById(array $data, $usuario): Response
  {
    $criterion_id = $data['id'] ?? null;

    if (!$criterion_id) {
      throw new BadRequestException(
        'Bad Request: Asegúrate de proporcionar los datos necesarios para la eliminación del.'
      );
    }

    $current_criterion = (new SubjectModel)->getCriterionById($criterion_id);

    if ($current_criterion->creado_por->id !== $usuario->id) {
      throw new UnauthorizedRequestException(
        'Unauthorized Request: Este criterio no fue registrado por tí, no puedes eliminarlo'
      );
    }

    $result = $this->materiaService->deleteCriterion($criterion_id);

    if ($result) {
      return new Response(
        true,
        201,
        'El criterio ha sido eliminado correctamente'
      );
    } else {
      throw new InternalServerErrorExeception(
        'Ha ocurrido un error al eliminar el criterio, por favor intentalo de nuevo'
      );
    }
  }

  public function updateCriterion(array $data, $usuario): Response
  {
    $id_criterio = $data['id'] ?? null;

    if (!$id_criterio) {
      throw new BadRequestException(
        'Bad Request: Asegúrate de proporcionar los datos necesarios para actualizar la materia.'
      );
    }

    $current_criterion = (new SubjectModel)->getById($id_criterio);

    if ($current_criterion->creado_por->id !== $usuario->id) {
      throw new UnauthorizedRequestException(
        'Unauthorized Request: La materia que intentas actualizar no fue creada por ti por lo que esta acción no puede ser realizada'
      );
    }

    $criterio = new Criterio(
      $id_criterio,
      $data['criterio'] ?? null,
      $data['porcentaje'] ?? null,
      $data['tipo'] ?? null,
      $data['estado'] ?? null
    );

    $result = $this->materiaService->updateCriterion($criterio);

    if ($result instanceof criterio) {
      return new Response(true, 201, 'El criterio ha sido actualizado exitosamente');
    } else {
      throw new InternalServerErrorExeception(
        'Internal Server Error: Ha ocurrido un error al actualizar el criterio, por favor intenta de nuevo'
      );
    }
  }

  public function deleteSubject(array $materia_data): Response
  {
    $materiaId = $materia_data['id'] ?? null;

    if ($materia_data['creado_por'] !== $_SESSION['usuario']['id']) {
      throw new UnauthorizedRequestException(
        'Unauthorized Request: Esta materia no fue registrada por tí, no puedes eliminarla'
      );
    }

    if (!$materiaId) {
      throw new BadRequestException(
        'Bad Request: Asegúrate de proporcionar los datos necesarios para la eliminación de la materia.'
      );
    }

    $result = $this->materiaService->delete($materiaId);

    if ($result) {
      return new Response(
        true,
        201,
        'La materia ha sido eliminada correctamente'
      );
    } else {
      throw new InternalServerErrorExeception(
        'Ha ocurrido un error al eliminar la materia, por favor intenta de nuevo'
      );
    }
  }

  public function deleteManySubjects(?array $materias): Response
  {
    if (is_null($materias)) {
      throw new BadRequestException(
        'Bad Request: Asegúrate de proporcionar los datos necesarios para la eliminación de las materias'
      );
    }

    if (!is_array($materias)) {
      throw new BadRequestException(
        'Bad Request: Asegúrate de proporcionar un array de IDs para eliminar las materias'
      );
    }

    foreach ($materias as $materia) {
      if (!is_integer($materia['id'])) {
        throw new BadRequestException(
          'Bad Request: Asegúrate de que todos los IDs proporcionados sean enteros'
        );
      }
      if ($materia['creado_por'] !== $_SESSION['usuario']['id']) {
        throw new BadRequestException(
          'Bad Request: Una o varias de las materias que intentas eliminar no fueron registradas por ti'
        );
      }
    }

    $result = $this->materiaService->deleteMany($materias);

    if (!$result) {
      throw new InternalServerErrorExeception(
        'Internal Server Error: Ha ocurrido un error al eliminar las materias, por favor inténtelo de nuevo'
      );
    }

    return new Response(true, 204, 'Las materias han sido eliminadas correctamente');
  }

  public function updateSubject(array $materia_data, $usuario): Response
  {
    $materiaId = $materia_data['id'] ?? null;



    if (!$materiaId) {
      throw new BadRequestException(
        'Bad Request: Asegúrate de proporcionar los datos necesarios para actualizar la materia.'
      );
    }

    $current_subject = (new SubjectModel)->getById($materiaId);

    if ($current_subject->creado_por->id !== $usuario->id) {
      throw new UnauthorizedRequestException(
        'Unauthorized Request: La materia que intentas actualizar no fue creada por ti por lo que esta acción no puede ser realizada'
      );
    }

    $creado_por = new Usuario(
      $materia_data['creado_por']
    );

    $materia = new Materia(
      $materiaId,
      $materia_data['nombre'] ?? null,
      $materia_data['porcentaje'] ?? null,
      $materia_data['porcentaje_individual'] ?? null,
      $materia_data['porcentaje_grupal'] ?? null,
      $materia_data['fecha_inicio'] ?? null,
      $materia_data['fecha_fin'] ?? null,
      $materia_data['activo'] ?? null,
      $materia_data['year'] ?? null,
      $materia_data['tipo'] ?? null,
      $creado_por
    );

    $result = $this->materiaService->update($materia);

    if ($result instanceof Materia) {
      return new Response(true, 201, 'La materia ha sido actualizada exitosamente');
    } else {
      throw new InternalServerErrorExeception(
        'Internal Server Error: Ha ocurrido un error al actualizar la materia, por favor intenta de nuevo'
      );
    }
  }

  public function getAllSubjects(): Response
  {
    $result = $this->materiaService->getAll();

    if ($result) {
      if (count($result) === 0) throw new NotFoundException(
        'Not Found: No hay registros de materias',
      );

      return new Response(true, 200, 'Materias obtenidas exitosamente', $result);
    } else {
      throw new InternalServerErrorExeception(
        'Internal Server Error: Ha ocurrido un error al obtener las materias, por favor intenta de nuevo'
      );
    }
  }

  public function getSubjectById(?int $materiaId): Response
  {
    if (!$materiaId) {
      throw new BadRequestException(
        'Bad Request: Asegúrate de proporcionar un ID válido para obtener la materia'
      );
    }

    $result = $this->materiaService->getById($materiaId);

    if ($result instanceof Materia) {
      return new Response(true, 200, 'Materia obtenida exitosamente', [$result]);
    } else {
      throw new InternalServerErrorExeception(
        'Internal Server Error: Ha ocurrido un error al obtener la materia, por favor intenta de nuevo'
      );
    }
  }

  public function getSubjectByName(?string $nombre): Response
  {
    if (!$nombre) {
      throw new BadRequestException(
        'Bad Request: Asegúrate de proporcionar un nombre válido para obtener la materia'
      );
    }

    $result = $this->materiaService->getByName($nombre);

    if (is_array($result)) {
      return new Response(true, 200, 'Materias obtenidas exitosamente', $result);
    } else {
      throw new InternalServerErrorException(
        'Internal Server Error: Ha ocurrido un error al obtener las materias, por favor intenta de nuevo'
      );
    }
  }
}

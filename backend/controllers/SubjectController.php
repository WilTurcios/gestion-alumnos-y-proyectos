<?php

namespace Controllers;

require_once 'schemas/Response.php';
require_once 'models/mysql/MateriaModel.php';
require_once 'exceptions/ParameterIsMissingException.php';
require_once 'exceptions/UnauthorizedRequestException.php';
require_once 'exceptions/BadRequestException.php';
require_once 'exceptions/NotFoundException.php';
require_once 'exceptions/InternalServerErrorException.php';

use BadRequestException;
use Response;
use Materia;
use InternalServerErrorExeception;
use NotFoundException;
use ParameterIsMissingException;
use UnauthorizedRequestException;
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
    'creado_por'
  ];

  public function __construct(private $materiaService)
  {
  }

  public function getRequiredParameters()
  {
    return $this->requiredParameters;
  }

  public function createMateria(array $materia_data): Response
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

    $creado_por = new Usuario($materia_data['creado_por']);

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
      $creado_por
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

  public function deleteMateria(array $materia_data): Response
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

  public function deleteManyMaterias(?array $materias): Response
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

  public function updateMateria(array $materia_data): Response
  {
    $materiaId = $materia_data['id'] ?? null;

    if (!$materiaId) {
      throw new BadRequestException(
        'Bad Request: Asegúrate de proporcionar los datos necesarios para actualizar la materia.'
      );
    }

    if ($materia_data['creado_por'] !== $_SESSION['usuario']['id']) {
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

  public function getAllMaterias(): Response
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

  public function getMateriaById(?int $materiaId): Response
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

  public function getMateriaByName(?string $nombre): Response
  {
    if (!$nombre) {
      throw new BadRequestException(
        'Bad Request: Asegúrate de proporcionar un nombre válido para obtener la materia'
      );
    }

    $result = $this->materiaService->getByName($nombre);

    if (is_array($result) && !empty($result)) {
      return new Response(true, 200, 'Materias obtenidas exitosamente', $result);
    } else {
      throw new InternalServerErrorExeception(
        'Internal Server Error: Ha ocurrido un error al obtener las materias, por favor intenta de nuevo'
      );
    }
  }
}

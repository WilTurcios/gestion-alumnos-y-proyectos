<?php

namespace Controllers;

require_once 'schemas/Response.php';
require_once 'models/mysql/Empresas.php';
require_once 'exceptions/ParameterIsMissingException.php';
require_once 'exceptions/UnauthorizedRequestException.php';
require_once 'exceptions/BadRequestException.php';
require_once 'exceptions/NotFoundException.php';
require_once 'exceptions/InternalServerErrorException.php';

use BadRequestException;
use Response;
use Empresa;
use InternalServerErrorException;
use NotFoundException;
use ParameterIsMissingException;
use UnauthorizedRequestException;
use Usuario;

class CompanyController
{
  private array $requiredParameters = [
    'nombre',
    'contacto',
    'direccion',
    'email',
    'telefono',
    'creado_por'
  ];

  public function __construct(private $companyService)
  {
  }

  public function getRequiredParameters()
  {
    return $this->requiredParameters;
  }

  public function createCompany(array $empresa_data): Response
  {
    $requiredParameters = $this->getRequiredParameters();

    $response = null;

    foreach ($requiredParameters as $key) {
      if (!array_key_exists($key, $empresa_data)) {
        throw new ParameterIsMissingException(
          'Bad Request: Asegurese de proporcionar todos los campos necesarios para agregar una empresa',
          400
        );

        break;
      }

      if (empty(trim($empresa_data[$key]))) {
        throw new ParameterIsMissingException(
          'Bad Request: Asegurese de que los campos obligatorios no estén vacios',
          400
        );

        break;
      }
    }

    if ($response) return $response;

    $creado_por = new Usuario($empresa_data['creado_por']);

    $empresa = new Empresa(
      null,
      $empresa_data['nombre'],
      $empresa_data['contacto'],
      $empresa_data['direccion'],
      $empresa_data['email'],
      $empresa_data['telefono'],
      $creado_por
    );

    $result = $this->companyService->save($empresa);

    if ($result instanceof Empresa) {
      return new Response(true, 200, 'La empresa se ha agregado exitosamente', [$result]);
    } else {
      throw new InternalServerErrorException(
        'Internal Server Error: Ha ocurrido un error al agregar la empresa, por favor intenta de nuevo.'
      );
    }
  }

  public function deleteCompany(array $empresa_data): Response
  {
    $empresaId = $empresa_data['id'] ?? null;

    if ($empresa_data['creado_por'] !== $_SESSION['usuario']['id']) {
      throw new UnauthorizedRequestException(
        'Unauthorized Request: Esta empresa no fue registrada por tí, no puedes eliminarla'
      );
    }

    if (!$empresaId) {
      throw new BadRequestException(
        'Bad Request: Asegúrate de proporcionar los datos necesarios para la eliminación de la empresa.'
      );
    }

    $result = $this->companyService->delete($empresaId);

    if ($result) {
      return new Response(
        true,
        201,
        'La empresa ha sido eliminada correctamente'
      );
    } else {
      throw new InternalServerErrorException(
        'Ha ocurrido un error al eliminar la empresa, por favor intenta de nuevo'
      );
    }
  }

  public function deleteManyCompanies(?array $companies): Response
  {
    if (is_null($companies)) {
      throw new BadRequestException(
        'Bad Request: Asegúrate de proporcionar los datos necesarios para la eliminación de las empresas'
      );
    }

    if (!is_array($companies)) {
      throw new BadRequestException(
        'Bad Request: Asegúrate de proporcionar un array de IDs para eliminar las empresas'
      );
    }

    foreach ($companies as $company) {
      if (!is_integer($company['id'])) {
        throw new BadRequestException(
          'Bad Request: Asegúrate de que todos los IDs proporcionados sean enteros'
        );
      }
      if ($company['creado_por'] !== $_SESSION['usuario']['id']) {
        throw new BadRequestException(
          'Bad Request: Uno o varias de las empresas que intentas eliminar no fueron registradas por ti'
        );
      }
    }

    $result = $this->companyService->deleteMany($companies);

    if (!$result) {
      throw new InternalServerErrorException(
        'Internal Server Error: Ha ocurrido un error al eliminar las empresas, por favor inténtelo de nuevo'
      );
    }

    return new Response(true, 204, 'Las empresas han sido eliminados correctamente');
  }

  public function updateCompany(array $empresa_data): Response
  {
    $empresaId = $empresa_data['id'] ?? null;

    if (!$empresaId) {
      throw new BadRequestException(
        'Bad Request: Asegúrate de proporcionar los datos necesarios para actualizar la empresa.'
      );
    }

    if ($empresa_data['creado_por'] !== $_SESSION['usuario']['id']) {
      throw new UnauthorizedRequestException(
        'Unauthorized Request: La empresa que intentas actualizar no fue creada por ti por lo que esta acción no puede ser realizada'
      );
    }

    $creado_por = new Usuario(
      $empresa_data['creado_por']
    );

    $empresa = new Empresa(
      $empresaId,
      $empresa_data['nombre'] ?? null,
      $empresa_data['contacto'] ?? null,
      $empresa_data['direccion'] ?? null,
      $empresa_data['email'] ?? null,
      $empresa_data['telefono'] ?? null,
      $creado_por
    );

    $result = $this->companyService->update($empresa);

    if ($result instanceof Empresa) {
      return new Response(true, 201, 'La empresa ha sido actualizada exitosamente');
    } else {
      throw new InternalServerErrorException(
        'Internal Server Error: Ha ocurrido un error al actualizar la empresa, por favor intenta de nuevo'
      );
    }
  }

  public function getAllCompanies(): Response
  {
    $result = $this->companyService->getAll();

    if ($result) {

      if (count($result) !== 0) throw new NotFoundException(
        'Not Found: No hay registros de empresas',
      );

      return new Response(true, 200, 'Empresas obtenidas exitosamente', $result);
    } else {
      throw new InternalServerErrorException(
        'Internal Server Error: Ha ocurrido un error al obtener las empresas, por favor intenta de nuevo'
      );
    }
  }

  public function getCompanyById(?int $empresaId): Response
  {
    if (!$empresaId) {
      throw new BadRequestException(
        'Bad Request: Asegúrate de proporcionar un ID válido para obtener la empresa'
      );
    }

    $result = $this->companyService->getById($empresaId);

    if ($result instanceof Empresa) {
      return new Response(true, 200, 'Empresa obtenida exitosamente', [$result]);
    } else {
      throw new InternalServerErrorException(
        'Internal Server Error: Ha ocurrido un error al obtener la empresa, por favor intenta de nuevo'
      );
    }
  }

  public function getCompanyByName(?string $nombre): Response
  {
    if (!$nombre) {
      throw new BadRequestException(
        'Bad Request: Asegúrate de proporcionar un nombre válido para obtener la empresa'
      );
    }

    $result = $this->companyService->getByName($nombre);

    if ($result instanceof Empresa) {
      return new Response(true, 200, 'Empresa obtenida exitosamente', [$result]);
    } else {
      throw new InternalServerErrorException(
        'Internal Server Error: Ha ocurrido un error al obtener la empresa, por favor intenta de nuevo'
      );
    }
  }
}

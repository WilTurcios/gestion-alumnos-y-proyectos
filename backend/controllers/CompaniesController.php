<?php

namespace Controllers;

require_once 'schemas/Response.php';
require_once 'models/mysql/Empresas.php';
require_once 'interfaces/ICompanyService.php';

use ICompanyService;
use Response;
use Empresa;

class CompaniesController
{
  public function __construct(private ICompanyService $companyService)
  {
  }

  public function createCompany(array $empresa_data): Response
  {
    $missing_data = false;

    foreach ($empresa_data as $key => $value) {
      if (!array_key_exists($key, $empresa_data) || empty($value)) {
        $missing_data = true;
        break;
      }
    }

    if ($missing_data) {
      return new Response(
        false,
        'Asegúrate de proporcionar la información necesaria para crear una empresa'
      );
    }

    $empresa = new Empresa(
      null,
      $empresa_data['empresa'],
      $empresa_data['contacto'],
      $empresa_data['direccion'],
      $empresa_data['email'],
      $empresa_data['telefono']
    );

    $result = $this->companyService->save($empresa);

    if ($result instanceof Empresa) {
      return new Response(true, 'La empresa se ha creado exitosamente', [$result]);
    } else {
      return new Response(
        false,
        'Ha ocurrido un error al crear la empresa, por favor intenta de nuevo.'
      );
    }
  }

  public function deleteCompany(array $empresa_data): Response
  {
    $empresaId = $empresa_data['id'] ?? null;

    if (!$empresaId) {
      return new Response(
        false,
        'Asegúrate de proporcionar los datos necesarios para la eliminación de la empresa.'
      );
    }

    $result = $this->companyService->delete($empresaId);

    if ($result) {
      return new Response(true, 'La empresa ha sido eliminada correctamente', []);
    } else {
      return new Response(
        false,
        'Ha ocurrido un error al eliminar la empresa, por favor intenta de nuevo'
      );
    }
  }

  public function updateCompany(array $empresa_data): Response
  {
    $empresaId = $empresa_data['id'] ?? null;

    if (!$empresaId) {
      return new Response(
        false,
        'Asegúrate de proporcionar los datos necesarios para actualizar la empresa.'
      );
    }

    $empresa = new Empresa(
      $empresaId,
      $empresa_data['empresa'] ?? null,
      $empresa_data['contacto'] ?? null,
      $empresa_data['direccion'] ?? null,
      $empresa_data['email'] ?? null,
      $empresa_data['telefono'] ?? null
    );

    $result = $this->companyService->update($empresa);

    if ($result instanceof Empresa) {
      return new Response(true, 'La empresa ha sido actualizada exitosamente', [$result]);
    } else {
      return new Response(
        false,
        'Ha ocurrido un error al actualizar la empresa, por favor intenta de nuevo'
      );
    }
  }

  public function getAllCompanies(): Response
  {
    $result = $this->companyService->getAll();

    if ($result) {
      return new Response(true, 'Empresas obtenidas exitosamente', $result);
    } else {
      return new Response(
        false,
        'Ha ocurrido un error al obtener las empresas, por favor intenta de nuevo'
      );
    }
  }

  public function getCompanyById(?int $empresaId): Response
  {
    if (!$empresaId) {
      return new Response(
        false,
        'Asegúrate de proporcionar un ID válido para obtener la empresa'
      );
    }

    $result = $this->companyService->getById($empresaId);

    if ($result instanceof Empresa) {
      return new Response(true, 'Empresa obtenida exitosamente', [$result]);
    } else {
      return new Response(
        false,
        'Ha ocurrido un error al obtener la empresa, por favor intenta de nuevo'
      );
    }
  }

  public function getCompanyByName(?string $nombre): Response
  {
    if (!$nombre) {
      return new Response(
        false,
        'Asegúrate de proporcionar un nombre válido para obtener la empresa'
      );
    }

    $result = $this->companyService->getByName($nombre);

    if ($result instanceof Empresa) {
      return new Response(true, 'Empresa obtenida exitosamente', [$result]);
    } else {
      return new Response(
        false,
        'Ha ocurrido un error al obtener la empresa, por favor intenta de nuevo'
      );
    }
  }
}

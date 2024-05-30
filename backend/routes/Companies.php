<?php

require_once 'controllers/CompaniesController.php';

use Controllers\CompaniesController;

function CompanyRoutes(ICompanyService $companyService)
{
  return function (string $method, ?string $id, ?array $params) use ($companyService) {
    $companiesController = new CompaniesController($companyService);

    $response = array('status' => 'failed', 'message' => 'Invalid Request');

    $json_data = file_get_contents('php://input');
    $data = ($json_data && !empty($json_data)) ? json_decode($json_data, true) : null;

    switch ($method) {
      case 'GET':
        if ($id) {
          $response = $companiesController->getCompanyById($id);
        } elseif ($params && array_key_exists('nombre', $params)) {
          $response = $companiesController->getCompanyByName($params['nombre']);
        } else {
          $response = $companiesController->getAllCompanies();
        }
        break;
      case 'POST':
        $response = $companiesController->createCompany($data);
        break;
      case 'PUT':
        $response = $companiesController->updateCompany($data);
        break;
      case 'DELETE':
        $response = $companiesController->deleteCompany($data);
        break;
      default:
        $response = array('status' => 'failed', 'message' => 'Invalid Request Method');
        break;
    }

    header('Content-Type: application/json');
    http_response_code(200);
    echo json_encode($response);
  };
}

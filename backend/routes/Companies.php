<?php

require_once 'controllers/CompanyController.php';
require_once 'models/Empresas.php';

use Controllers\CompanyController;

$companiesController = new CompanyController(new CompanyModel());

$response = array('status' => 'failed', 'message' => 'Invalid Request');

$json_data = file_get_contents('php://input');
$data = ($json_data && !empty($json_data)) ? json_decode($json_data, true) : null;
$ids = $data && array_key_exists('ids', $data) ?  $data['ids'] : null;


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
    if ($ids !== null) {
      $response = $companiesController->deleteManyCompanies($ids);
    } else {
      $response = $companiesController->deleteCompany($data);
    }
    break;
  default:
    $response = new Response(false, 500, 'Algo salió mal, por favor, intentalo de nuevo más tarde.');
    break;
}

http_response_code($response->status_code);
echo json_encode($response->data);

exit(0);

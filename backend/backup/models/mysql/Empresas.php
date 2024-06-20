<?php

require_once 'schemas/Empresa.php';
require_once 'exceptions/NotFoundException.php';

class MySQLCompaniesService
{
  private $host = 'localhost';
  private $user = 'root';
  private $password = '12345';
  private $db = 'dpwsld';

  private $connection;

  public function __construct()
  {
    try {
      $this->connection = new mysqli(
        $this->host,
        $this->user,
        $this->password,
        $this->db
      );
    } catch (Exception $ex) {
      echo "Ha ocurrido un error: " . $ex->getMessage();
    }
  }

  public function getAll(): array | false
  {
    $query = "SELECT * FROM empresas";
    $result = $this->connection->query($query);

    if (!$result) return false;
    if ($result->num_rows === 0) return [];

    $empresas = [];

    while ($row = $result->fetch_assoc()) {
      $empresa = new Empresa(
        $row['idempresa'],
        $row['empresa'],
        $row['contacto'],
        $row['direccion'],
        $row['email'],
        $row['telefono']
      );

      $empresas[] = $empresa;
    }

    return $empresas;
  }
}

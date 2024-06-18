<?php

require_once 'interfaces/ICompanyService.php';
require_once 'schemas/Empresa.php';

class MySQLCompaniesService implements ICompanyService
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

  public function save(Empresa $empresa): Empresa | false
  {
    $query = "INSERT INTO empresas (empresa, contacto, direccion, email, telefono) VALUES (?, ?, ?, ?, ?)";
    $stmt = $this->connection->prepare($query);

    if (!$stmt) return false;

    $stmt->bind_param(
      "sssss",
      $empresa->empresa,
      $empresa->contacto,
      $empresa->direccion,
      $empresa->email,
      $empresa->telefono
    );

    $result = $stmt->execute();

    if (!$result) return false;

    $empresa->id = $this->connection->insert_id;

    $stmt->close();

    return $empresa;
  }

  public function update(Empresa $empresa): Empresa | false
  {
    $query = "UPDATE empresas SET empresa=?, contacto=?, direccion=?, email=?, telefono=? WHERE idempresa=?";
    $stmt = $this->connection->prepare($query);

    if (!$stmt) return false;

    $stmt->bind_param(
      "sssssi",
      $empresa->empresa,
      $empresa->contacto,
      $empresa->direccion,
      $empresa->email,
      $empresa->telefono,
      $empresa->id
    );

    $result = $stmt->execute();

    if (!$result) return false;

    $stmt->close();

    return $empresa;
  }

  public function delete(?int $empresa_id): bool
  {
    if (!$empresa_id) return false;

    $query = "DELETE FROM empresas WHERE idempresa=?";
    $stmt = $this->connection->prepare($query);

    if (!$stmt) return false;

    $stmt->bind_param("i", $empresa_id);
    $result = $stmt->execute();

    $stmt->close();

    return $result;
  }

  public function deleteAll(): bool
  {
    $query = "DELETE FROM empresas";
    $result = $this->connection->query($query);

    return $result;
  }

  public function getAll(): array | false
  {
    $query = "SELECT * FROM empresas";
    $result = $this->connection->query($query);

    if (!$result) return false;

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

  public function getById(?int $empresa_id): Empresa | false
  {
    if (!$empresa_id) return false;

    $query = "SELECT * FROM empresas WHERE idempresa=?";
    $stmt = $this->connection->prepare($query);

    if (!$stmt) return false;

    $stmt->bind_param("i", $empresa_id);
    $stmt->execute();

    $result = $stmt->get_result();

    if (!$result || $result->num_rows === 0) return false;

    $row = $result->fetch_assoc();

    $empresa = new Empresa(
      $row['idempresa'],
      $row['empresa'],
      $row['contacto'],
      $row['direccion'],
      $row['email'],
      $row['telefono']
    );

    $stmt->close();

    return $empresa;
  }

  public function getByName(?string $nombre): Empresa | false
  {
    if (!$nombre) return false;

    $query = "SELECT * FROM empresas WHERE empresa LIKE ?";
    $stmt = $this->connection->prepare($query);

    if (!$stmt) return false;

    $stmt->bind_param("s", "%$nombre%");
    $stmt->execute();

    $result = $stmt->get_result();

    if (!$result || $result->num_rows === 0) return false;

    $row = $result->fetch_assoc();

    $empresa = new Empresa(
      $row['idempresa'],
      $row['empresa'],
      $row['contacto'],
      $row['direccion'],
      $row['email'],
      $row['telefono']
    );

    $stmt->close();

    return $empresa;
  }
}

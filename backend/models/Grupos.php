<?php

require_once 'schemas/Grupo.php';

class GroupModel
{
  private $host = 'localhost';
  private $user = 'root';
  private $password = '12345';
  private $db = 'dpwsld';
  private $campos = [
    'nombre'
  ];

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

  private function getValoresInsert()
  {
    return '?';
  }

  public function getTotalCount()
  {
    $query = "SELECT COUNT(*) as total FROM grupos";
    $result = $this->connection->query($query);

    $result = $result->fetch_assoc();
    return $result['total'];
  }

  public function save(Grupo $grupo): Grupo | false
  {
    $campos_insert = implode(",", $this->campos);
    $valores_insert = $this->getValoresInsert();
    $query = "
      INSERT INTO grupos 
      ($campos_insert)    
      VALUES 
      ($valores_insert);
    ";

    $stmt = $this->connection->prepare($query);

    if (!$stmt) return false;

    $stmt->bind_param(
      "s",
      $grupo->nombre
    );

    $result = $stmt->execute();

    if (!$result) return false;

    $grupo->id = $this->connection->insert_id;

    $stmt->close();

    return $grupo;
  }

  public function delete(Grupo $grupo): Grupo | false
  {
    $query = "DELETE FROM grupos WHERE id = ?";
    $stmt = $this->connection->prepare($query);

    if (!$stmt) return false;

    $stmt->bind_param("i", $grupo->id);
    $result = $stmt->execute();

    if (!$result) {
      $stmt->close();
      return false;
    }

    $stmt->close();

    return $grupo;
  }

  public function deleteMany(?array $ids): bool
  {
    if (empty($ids)) {
      return false;
    }

    $placeholders = implode(',', array_fill(0, count($ids), '?'));

    $query = "DELETE FROM grupos WHERE id IN ($placeholders)";

    $stmt = $this->connection->prepare($query);

    if (!$stmt) {
      return false;
    }

    $types = str_repeat('i', count($ids));

    $stmt->bind_param($types, ...$ids);

    $result = $stmt->execute();

    $stmt->close();

    return $result;
  }


  public static function getById(?int $grupo_id): Grupo | false
  {
    $connection = (new self())->connection;
    $query = "SELECT * FROM grupos WHERE id = ?;";
    $stmt = $connection->prepare($query);

    if (!$stmt) return false;

    $stmt->bind_param("i", $grupo_id);
    $result = $stmt->execute();

    if (!$result) return false;

    $resultSet = $stmt->get_result();

    if ($resultSet->num_rows === 0) return false;

    $row = $resultSet->fetch_assoc();
    $grupo = new Grupo(
      $row["id"],
      $row["nombre"]
    );

    return $grupo;
  }

  public function update(Grupo $grupo): Grupo | false
  {
    $previous_group = MySQLGroupsService::getById($grupo->id);

    if (!$previous_group) return false;

    $updated_group = new Grupo(
      $grupo->id,
      $grupo->nombre ?? $previous_group->nombre
    );

    $campos_set = implode(",", array_map(function ($campo) {
      return "$campo = ?";
    }, $this->campos));

    $query = "
      UPDATE grupos SET $campos_set WHERE id = ?
    ";

    $stmt = $this->connection->prepare($query);

    if (!$stmt) return false;

    $stmt->bind_param(
      "si",
      $updated_group->nombre,
      $updated_group->id
    );

    $result = $stmt->execute();

    if (!$result) {
      $stmt->close();
      return false;
    }

    $stmt->close();

    return $updated_group;
  }

  public static function getAll(): array | false
  {
    $connection = (new self())->connection;
    $query = "SELECT * FROM grupos;";
    $result = $connection->query($query);

    $grupos = [];

    if (!$result) return [];

    while ($row = $result->fetch_assoc()) {
      $grupo = new Grupo(
        $row["id"],
        $row["nombre"]
      );

      $grupos[] = $grupo;
    }

    return $grupos;
  }

  public static function deleteAll(): bool
  {
    $query = "DELETE FROM grupos;";
    $result = (new self())->connection->query($query);
    return $result;
  }

  public static function getByName(string $nombre): array | false
  {
    $connection = (new self())->connection;
    $query = "SELECT * FROM grupos WHERE nombre LIKE '%$nombre%' ;";
    $result = $connection->query($query);

    $grupos = [];

    if (!$result) return $grupos;

    if (!$result || $result->num_rows === 0) {
      $connection->close();
      return false;
    }

    while ($row = $result->fetch_assoc()) {
      $grupo = new Grupo(
        $row["id"],
        $row["nombre"]
      );

      $grupos[] = $grupo;
    }
    $connection->close();

    return $grupos;
  }
}


// $grupo = GroupModel::getByName('soft');

// echo json_encode($grupo);

<?php

require_once 'schemas/Materia.php';
require_once 'schemas/Usuario.php';
require_once 'models/Usuarios.php';
require_once 'exceptions/NotFoundException.php';

class SubjectModel
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

  public function getTotalCount()
  {
    $query = "SELECT COUNT(*) as total FROM materias";
    $result = $this->connection->query($query);

    $result = $result->fetch_assoc();
    return $result['total'];
  }

  public function save(Materia $materia): Materia | false
  {
    $query = "INSERT INTO materias (nombre, porcentaje, porcentaje_individual, porcentaje_grupal, fecha_inicio, fecha_fin, activo, year, tipo, creado_por) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $this->connection->prepare($query);

    if (!$stmt) return false;

    $stmt->bind_param(
      "siiisssisi",
      $materia->nombre,
      $materia->porcentaje,
      $materia->porcentaje_individual,
      $materia->porcentaje_grupal,
      $materia->fecha_inicio,
      $materia->fecha_fin,
      $materia->activo,
      $materia->year,
      $materia->tipo ?? 'Práctica',
      $materia->creado_por->id
    );

    $result = $stmt->execute();

    if (!$result) return false;

    $materia->id = $this->connection->insert_id;

    $stmt->close();

    return $materia;
  }

  public function addCriterion(int $id_materia, Criterio $criterio): Criterio | false
  {
    $query = "INSERT INTO criterios (id_materia, criterio, porcentaje, tipo, estado, creado_por) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $this->connection->prepare($query);

    if (!$stmt) return false;

    $stmt->bind_param(
      "isissi",
      $id_materia,
      $criterio->criterio,
      $criterio->porcentaje,
      $criterio->tipo,
      $criterio->estado,
      $criterio->creado_por->id
    );

    $result = $stmt->execute();

    if (!$result) return false;

    $criterio->id = $this->connection->insert_id;

    $stmt->close();

    return $criterio;
  }

  public function deleteCriterion(?int $id_criterio): bool
  {
    if (!$id_criterio) return false;

    $query = "DELETE FROM criterios WHERE id=?";
    $stmt = $this->connection->prepare($query);

    if (!$stmt) return false;

    $stmt->bind_param("i", $id_criterio);
    $result = $stmt->execute();

    $stmt->close();

    return $result;
  }

  public function updateCriterion(Criterio $criterio): Criterio | false
  {
    $query = "UPDATE criterios SET criterio=?, porcentaje=?, tipo=?, estado=? WHERE id=?";
    $stmt = $this->connection->prepare($query);

    if (!$stmt) return false;

    $previous_criterion = (new self)->getCriterionById($criterio->id);

    $stmt->bind_param(
      "sissi",
      $criterio->criterio ?? $previous_criterion->criterio,
      $criterio->porcentaje ?? $previous_criterion->porcentaje,
      $criterio->tipo ?? $previous_criterion->tipo,
      $criterio->estado ?? $previous_criterion->estado,
      $criterio->id ?? $previous_criterion->id
    );

    $result = $stmt->execute();

    if (!$result) return false;

    $stmt->close();

    return $criterio;
  }

  public function getCriterionById(int $id)
  {
    if (!$id) return false;

    $query = "SELECT * FROM criterios WHERE id=?";
    $stmt = $this->connection->prepare($query);

    if (!$stmt) return false;

    $stmt->bind_param("i", $id);
    $stmt->execute();

    $result = $stmt->get_result();

    if (!$result || $result->num_rows === 0) return false;

    $row = $result->fetch_assoc();

    $userModel = new UserModel();
    $user = new Usuario($row['creado_por']);

    $creado_por = $userModel->getById($user);
    $criterio = new Criterio(
      $row['id'],
      $row['criterio'],
      $row['porcentaje'],
      $row['tipo'],
      $row['estado'],
      $creado_por
    );

    $stmt->close();

    return $criterio;
  }

  public function update(Materia $materia): Materia | false
  {
    $query = "UPDATE materias SET nombre=?, porcentaje=?, porcentaje_individual=?, porcentaje_grupal=?, fecha_inicio=?, fecha_fin=?, activo=?, year=?, tipo = ? WHERE id=?";
    $stmt = $this->connection->prepare($query);

    $previous_subject = (new SubjectModel)->getById($materia->id);
    if (!$stmt) return false;

    $stmt->bind_param(
      "siiisssisi",
      $materia->nombre ?? $previous_subject->nombre,
      $materia->porcentaje ?? $previous_subject->porcentaje,
      $materia->porcentaje_individual ?? $previous_subject->porcentaje_individual,
      $materia->porcentaje_grupal ?? $previous_subject->porcentaje_grupal,
      $materia->fecha_inicio ?? $previous_subject->fecha_inicio,
      $materia->fecha_fin ?? $previous_subject->fecha_fin,
      $materia->activo ?? $previous_subject->activo,
      $materia->year ?? $previous_subject->year,
      $materia->tipo ?? $previous_subject->tipo,
      $materia->id
    );

    $result = $stmt->execute();

    if (!$result) return false;

    $stmt->close();

    return $materia;
  }

  public function delete(int $materia_id): bool
  {
    $query = "DELETE FROM materias WHERE id=?";
    $stmt = $this->connection->prepare($query);

    if (!$stmt) return false;

    $stmt->bind_param("i", $materia_id);
    $result = $stmt->execute();

    $stmt->close();

    return $result;
  }

  public function deleteMany(?array $ids): bool
  {
    if (empty($ids)) {
      return false;
    }

    $placeholders = implode(',', array_fill(0, count($ids), '?'));

    $query = "DELETE FROM materias WHERE id IN ($placeholders)";
    $stmt = $this->connection->prepare($query);

    if (!$stmt) {
      error_log("Failed to prepare the statement: " . $this->connection->error);
      return false;
    }

    $types = str_repeat('i', count($ids));

    $params = array_merge([$types], $ids);
    $refs = [];
    foreach ($params as $key => $value) {
      $refs[$key] = &$params[$key];
    }

    call_user_func_array([$stmt, 'bind_param'], $refs);

    $result = $stmt->execute();

    $stmt->close();

    return $result;
  }

  public function deleteAll(): bool
  {
    $query = "DELETE FROM materias";
    $result = $this->connection->query($query);

    return $result;
  }

  public function getAll(): array | false
  {
    $query = "SELECT m.*, c.id as id_criterio, c.criterio, c.porcentaje as porcentaje_criterio, c.tipo as tipo_criterio, c.estado, c.creado_por as creado_por_criterio FROM materias m LEFT JOIN criterios c ON m.id = c.id_materia";
    $result = $this->connection->query($query);

    if (!$result) return false;
    if ($result->num_rows === 0) return [];

    $materias = [];
    $userModel = new UserModel();

    while ($row = $result->fetch_assoc()) {
      $materia_id = $row['id'];

      if (!array_key_exists($materia_id, $materias)) {
        $creado_por = $userModel->getById(new Usuario($row['creado_por']));
        $materias[$materia_id] = new Materia(
          $row['id'],
          $row['nombre'],
          $row['porcentaje'],
          $row['porcentaje_individual'],
          $row['porcentaje_grupal'],
          $row['fecha_inicio'],
          $row['fecha_fin'],
          $row['activo'],
          $row['year'],
          $row['tipo'],
          $creado_por,
          []
        );
      }

      if ($row['id_criterio']) {
        $creado_por_criterio = $userModel->getById(new Usuario($row['creado_por_criterio']));
        $criterio = new Criterio(
          $row['id_criterio'],
          $row['criterio'],
          $row['porcentaje_criterio'],
          $row['tipo_criterio'],
          $row['estado'],
          $creado_por_criterio
        );

        $materias[$materia_id]->criterios[] = $criterio;
      }
    }

    return array_values($materias);
  }

  public function getById(int $materia_id): Materia | false
  {
    $query = "SELECT * FROM materias WHERE id=?";
    $stmt = $this->connection->prepare($query);

    if (!$stmt) return false;

    $stmt->bind_param("i", $materia_id);
    $stmt->execute();

    $result = $stmt->get_result();

    if (!$result || $result->num_rows === 0) return false;

    $row = $result->fetch_assoc();

    $userModel = new UserModel();
    $user = new Usuario($row['creado_por']);

    $creado_por = $userModel->getById($user);
    $materia = new Materia(
      $row['id'],
      $row['nombre'],
      $row['porcentaje'],
      $row['porcentaje_individual'],
      $row['porcentaje_grupal'],
      $row['fecha_inicio'],
      $row['fecha_fin'],
      $row['activo'],
      $row['year'],
      $row['tipo'],
      $creado_por
    );

    $stmt->close();

    return $materia;
  }

  public function getByName(?string $nombre): array | false
  {
    if (!$nombre) return false;

    $query = "SELECT * FROM materias WHERE LOWER(nombre) LIKE ?";
    $stmt = $this->connection->prepare($query);

    if (!$stmt) return false;

    $nombre = strtolower($nombre);
    $likeNombre = "%" . $nombre . "%";
    $stmt->bind_param("s", $likeNombre);
    $stmt->execute();

    $result = $stmt->get_result();

    if (!$result || $result->num_rows === 0) return [];

    $materias = [];

    $userModel = new UserModel();

    while ($row = $result->fetch_assoc()) {
      $user = new Usuario($row['creado_por']);

      $creado_por = $userModel->getById($user);
      $materia = new Materia(
        $row['id'],
        $row['nombre'],
        $row['porcentaje'],
        $row['porcentaje_individual'],
        $row['porcentaje_grupal'],
        $row['fecha_inicio'],
        $row['fecha_fin'],
        $row['activo'],
        $row['year'],
        $row['tipo'],
        $creado_por
      );

      $materias[] = $materia;
    }

    $stmt->close();

    return $materias;
  }
}

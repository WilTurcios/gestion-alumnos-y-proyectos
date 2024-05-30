<?php

require_once 'interfaces/IStudentService.php';
require_once 'utils/encryptor.php';

use Utils\encryptor;

class MySQLStudentsService implements IStudentService
{
  private $host = 'localhost';
  private $user = 'root';
  private $password = '12345';
  private $db = 'dpwsld';
  private $campos = [
    'carnet',
    'nombres',
    'apellidos',
    'sexo',
    'email',
    'jornada',
    'direccion',
    'telalumno',
    'responsable',
    'telresponsable',
    'foto',
    'clave',
    'estadoalumno',
    'yearingreso',
    'idgrupo'
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

  private function getInsertValues()
  {
    return implode(",", array_fill(0, count($this->campos), "?"));
  }

  public function save(Estudiante $estudiante): Estudiante | false
  {
    $campos_insert = implode(",", $this->campos);
    $valores_insert = $this->getInsertValues();
    $query = "
      INSERT INTO alumnos 
      ($campos_insert)    
      VALUES 
      ($valores_insert);
    ";

    $stmt = $this->connection->prepare($query);

    $clave_encriptada = encryptor::encrypt($estudiante->clave);

    if (!$stmt) return false;

    $stmt->bind_param(
      "ssssssssssssssi",
      $estudiante->carnet,
      $estudiante->nombres,
      $estudiante->apellidos,
      $estudiante->sexo,
      $estudiante->email,
      $estudiante->jornada,
      $estudiante->direccion,
      $estudiante->tel_alumno,
      $estudiante->responsable,
      $estudiante->tel_responsable,
      $estudiante->foto,
      $clave_encriptada,
      $estudiante->estado_alumno,
      $estudiante->year_ingreso,
      $estudiante->grupo->id
    );

    $result = $stmt->execute();

    if (!$result) return false;

    $estudiante->id = $this->connection->insert_id;

    $group = MySQLGroupsService::getById($estudiante->grupo->id);

    $estudiante->grupo = $group;

    $stmt->close();

    return $estudiante;
  }

  public function update(Estudiante $estudiante): Estudiante | false
  {
    $previous_student = MySQLStudentsService::getById($estudiante);

    if (!$previous_student) return false;

    $updated_student = new Estudiante(
      $estudiante->id,
      $estudiante->carnet ?? $previous_student->carnet,
      $estudiante->nombres ?? $previous_student->nombres,
      $estudiante->apellidos ?? $previous_student->apellidos,
      $estudiante->sexo ?? $previous_student->sexo,
      $estudiante->email ?? $previous_student->email,
      $estudiante->jornada ?? $previous_student->jornada,
      $estudiante->direccion ?? $previous_student->direccion,
      $estudiante->tel_alumno ?? $previous_student->tel_alumno,
      $estudiante->responsable ?? $previous_student->responsable,
      $estudiante->tel_responsable ?? $previous_student->tel_responsable,
      $estudiante->foto ?? $previous_student->foto,
      $estudiante->clave ?? $previous_student->clave,
      $estudiante->estado_alumno ?? $previous_student->estado_alumno,
      $estudiante->year_ingreso ?? $previous_student->year_ingreso,
      $estudiante->grupo ?? $previous_student->grupo
    );

    $campos_set = implode(",", array_map(function ($campo) {
      return "$campo = ?";
    }, $this->campos));

    $query = "
      UPDATE alumnos SET $campos_set WHERE idalumno = ?
    ";

    $stmt = $this->connection->prepare($query);

    if (!$stmt) return false;

    $stmt->bind_param(
      "ssssssssssssssii",
      $updated_student->carnet,
      $updated_student->nombres,
      $updated_student->apellidos,
      $updated_student->sexo,
      $updated_student->email,
      $updated_student->jornada,
      $updated_student->direccion,
      $updated_student->tel_alumno,
      $updated_student->responsable,
      $updated_student->tel_responsable,
      $updated_student->foto,
      $updated_student->clave,
      $updated_student->estado_alumno,
      $updated_student->year_ingreso,
      $updated_student->grupo->id,
      $updated_student->id
    );

    $result = $stmt->execute();

    if (!$result) {
      $stmt->close();
      return false;
    }

    $stmt->close();

    return $updated_student;
  }

  public function delete(Estudiante $estudiante): Estudiante | false
  {
    $query = "DELETE FROM alumnos WHERE idalumno = ?";
    $stmt = $this->connection->prepare($query);

    if (!$stmt) return false;

    $stmt->bind_param("i", $estudiante->id);
    $result = $stmt->execute();

    if (!$result) {
      $stmt->close();
      return false;
    }


    return $estudiante;
  }

  public static function getAll(): array | false
  {
    $connection = (new self())->connection;
    $query = "SELECT * FROM alumnos;";
    $result = $connection->query($query);

    $students = [];

    if (!$result) return false;

    while ($row = $result->fetch_assoc()) {
      $student = new Estudiante(
        $row["idalumno"],
        $row["carnet"],
        $row["nombres"],
        $row["apellidos"],
        $row["sexo"],
        $row["email"],
        $row["jornada"],
        $row["direccion"],
        $row["telalumno"],
        $row["responsable"],
        $row["telresponsable"],
        $row["foto"],
        $row["clave"],
        $row["estadoalumno"],
        $row["yearingreso"]
      );

      $group = MySQLGroupsService::getById($row["idgrupo"]);
      $student->grupo = $group ? $group : null;



      $students[] = $student;
    }

    return $students;
  }

  public static function getByName(
    string $nombres,
    string $apellidos
  ): array | false {
    $students = [];
    $nombre_completo = "$nombres $apellidos";
    $query = "SELECT * FROM alumnos 
      WHERE CONCAT(nombres, ' ', apellidos) LIKE ?";
    $stmt = (new self())->connection->prepare($query);

    if ($stmt) {
      $stmt->bind_param("s", "%$nombre_completo%");
      $stmt->execute();
      $result = $stmt->get_result();

      if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          $student = new Estudiante(
            $row["idalumno"],
            $row["carnet"],
            $row["nombres"],
            $row["apellidos"],
            $row["sexo"],
            $row["email"],
            $row["jornada"],
            $row["direccion"],
            $row["telalumno"],
            $row["responsable"],
            $row["telresponsable"],
            $row["foto"],
            $row["clave"],
            $row["estadoalumno"],
            $row["yearingreso"]
          );

          $group = MySQLGroupsService::getById($row["idgrupo"]);
          $student->grupo = $group ? $group : null;

          $students[] = $student;
        }
      }

      $stmt->close();
      return $students;
    } else {
      echo "Error al preparar la consulta: " . (new self())->connection->error;
      return null;
    }
  }

  public static function getByCarnet(string $carnet): Estudiante | false
  {
    $query = "SELECT * FROM alumnos WHERE carnet = ?";
    $stmt = (new self())->connection->prepare($query);

    if (!$stmt) return false;

    $stmt->bind_param("s", $carnet);
    $stmt->execute();

    $result = $stmt->get_result();

    if (!$result) return false;
    if ($result->num_rows == 0) return false;

    $row = $result->fetch_assoc();

    $student = new Estudiante(
      $row["idalumno"],
      $row["carnet"],
      $row["nombres"],
      $row["apellidos"],
      $row["sexo"],
      $row["email"],
      $row["jornada"],
      $row["direccion"],
      $row["telalumno"],
      $row["responsable"],
      $row["telresponsable"],
      $row["foto"],
      $row["clave"],
      $row["estadoalumno"],
      $row["yearingreso"]
    );

    $group = MySQLGroupsService::getById($row["idgrupo"]);
    $student->grupo = $group ? $group : null;

    $stmt->close();
    return $student;
  }

  public static function getById(Estudiante $estudiante): Estudiante | false
  {
    $connection = (new self())->connection;
    $query = "SELECT * FROM alumnos WHERE idalumno = ?";
    $stmt = $connection->prepare($query);

    if (!$stmt) return false;


    $stmt->bind_param("i", $estudiante->id);
    $stmt->execute();
    $result = $stmt->get_result();

    if (!$result) return false;
    if ($result->num_rows === 0) return false;

    $row = $result->fetch_assoc();
    $student = new Estudiante(
      $row["idalumno"],
      $row["carnet"],
      $row["nombres"],
      $row["apellidos"],
      $row["sexo"],
      $row["email"],
      $row["jornada"],
      $row["direccion"],
      $row["telalumno"],
      $row["responsable"],
      $row["telresponsable"],
      $row["foto"],
      $row["clave"],
      $row["estadoalumno"],
      $row["yearingreso"]
    );

    $group = MySQLGroupsService::getById($row["idgrupo"]);
    $student->grupo = $group ? $group : null;

    $stmt->close();
    return $student;


    return false;
  }

  public static function deleteAll(): bool
  {
    $query = "DELETE FROM alumnos;";
    $result = (new self())->connection->query($query);
    return $result;
  }
}

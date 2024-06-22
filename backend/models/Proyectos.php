<?php

require_once 'schemas/Proyecto.php';
require_once 'schemas/Empresa.php';
require_once 'schemas/Usuario.php';
require_once 'schemas/Estudiante.php';
require_once 'models/Empresas.php';
require_once 'models/Estudiantes.php';
require_once 'exceptions/NotFoundException.php';

class ProjectModel
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
    $query = "SELECT COUNT(*) as total FROM proyectos";
    $result = $this->connection->query($query);

    $result = $result->fetch_assoc();
    return $result['total'];
  }

  public function save(Proyecto $proyecto): Proyecto | false
  {
    $query = "INSERT INTO proyectos (tema, id_empresa, id_asesor, objetivos, alcances_limitantes, observaciones, cd, estado, motivo, justificacion, resultados_esperados, fecha_presentacion, doc, creado_por) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $this->connection->prepare($query);

    if (!$stmt) {
      error_log("Error preparing statement: " . $this->connection->error);
      return false;
    }

    $stmt->bind_param(
      "siisssissssssi",
      $proyecto->tema,
      $proyecto->empresa->id,
      $proyecto->asesor->id,
      $proyecto->objetivos,
      $proyecto->alcances_limitantes,
      $proyecto->observaciones,
      $proyecto->cd,
      $proyecto->estado,
      $proyecto->motivo,
      $proyecto->justificacion,
      $proyecto->resultados_esperados,
      $proyecto->fecha_presentacion,
      $proyecto->doc,
      $proyecto->creado_por->id
    );

    $result = $stmt->execute();

    if (!$result) {
      error_log("Error executing statement: " . $stmt->error);
      return false;
    }

    $proyecto->id = $this->connection->insert_id;

    $stmt->close();

    return $proyecto;
  }


  public function addStudentToProject(int $id_proyecto, int $id_estudiante): bool
  {
    $query = "INSERT INTO alumnosxproyecto (id_proyecto, id_alumno) VALUES (?, ?)";
    $stmt = $this->connection->prepare($query);

    if (!$stmt) {
      error_log("Error preparing statement: " . $this->connection->error);
      return false;
    }

    $stmt->bind_param("ii", $id_proyecto, $id_estudiante);
    $result = $stmt->execute();

    $stmt->close();

    return $result;
  }

  public function deleteStudentFromProject(int $id_estudiante, int $id_proyecto): bool
  {

    $query = "DELETE FROM alumnosxproyecto WHERE id_alumno=? AND id_proyecto = ?;";
    $stmt = $this->connection->prepare($query);

    if (!$stmt) return false;

    $stmt->bind_param("ii", $id_estudiante, $id_proyecto);
    $result = $stmt->execute();

    $stmt->close();

    return $result;
  }

  public function addJudgeToProject(int $id_proyecto, int $id_jurado): bool
  {
    $query = "INSERT INTO juradosxproyecto (id_proyecto, id_jurado) VALUES (?, ?)";
    $stmt = $this->connection->prepare($query);

    if (!$stmt) {
      error_log("Error preparing statement: " . $this->connection->error);
      return false;
    }

    $stmt->bind_param("ii", $id_proyecto, $id_jurado);
    $result = $stmt->execute();

    $stmt->close();

    return $result;
  }

  public function deleteJudgeFromProject(int $id_jurado, int  $id_proyecto): bool
  {
    $query = "DELETE FROM juradosxproyecto WHERE id_jurado=? AND id_proyecto = ?";
    $stmt = $this->connection->prepare($query);

    if (!$stmt) return false;

    $stmt->bind_param("ii", $id_jurado, $id_proyecto);
    $result = $stmt->execute();

    $stmt->close();

    return $result;
  }


  public function update(Proyecto $proyecto): Proyecto | false
  {
    $query = "UPDATE proyectos SET 
                tema = ?, 
                id_empresa = ?, 
                id_asesor = ?, 
                objetivos = ?, 
                alcances_limitantes = ?, 
                observaciones = ?, 
                cd = ?, 
                estado = ?, 
                motivo = ?, 
                justificacion = ?, 
                resultados_esperados = ?, 
                fecha_presentacion = ?, 
                doc = ? 
              WHERE id = ?";

    $stmt = $this->connection->prepare($query);

    if (!$stmt) {
      error_log("Error preparing statement: " . $this->connection->error);
      return false;
    }

    $previousProject = (new ProjectModel())->getById($proyecto->id);

    if (!$previousProject) {
      error_log("Error: Previous project not found.");
      return false;
    }

    $tema = $proyecto->tema ?? $previousProject->tema;
    $idEmpresa = $proyecto->empresa->id ?? $previousProject->empresa->id;
    $idAsesor = $proyecto->asesor->id ?? $previousProject->asesor->id;
    $objetivos = $proyecto->objetivos ?? $previousProject->objetivos;
    $alcancesLimitantes = $proyecto->alcances_limitantes ?? $previousProject->alcances_limitantes;
    $observaciones = $proyecto->observaciones ?? $previousProject->observaciones;
    $cd = $proyecto->cd ?? $previousProject->cd;
    $estado = $proyecto->estado ?? $previousProject->estado;
    $motivo = $proyecto->motivo ?? $previousProject->motivo;
    $justificacion = $proyecto->justificacion ?? $previousProject->justificacion;
    $resultadosEsperados = $proyecto->resultados_esperados ?? $previousProject->resultados_esperados;
    $fechaPresentacion = $proyecto->fecha_presentacion ?? $previousProject->fecha_presentacion;
    $doc = $proyecto->doc ?? $previousProject->doc;

    $stmt->bind_param(
      "siisssssissssi",
      $tema,
      $idEmpresa,
      $idAsesor,
      $objetivos,
      $alcancesLimitantes,
      $observaciones,
      $cd,
      $estado,
      $motivo,
      $justificacion,
      $resultadosEsperados,
      $fechaPresentacion,
      $doc,
      $proyecto->id
    );

    $result = $stmt->execute();

    if (!$result) {
      error_log("Error executing statement: " . $stmt->error);
      $stmt->close();
      return false;
    }

    $stmt->close();
    return $proyecto;
  }


  public function delete(?int $proyecto_id): bool
  {
    if (!$proyecto_id) return false;

    $query = "DELETE FROM proyectos WHERE id=?";
    $stmt = $this->connection->prepare($query);

    if (!$stmt) return false;

    $stmt->bind_param("i", $proyecto_id);
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

    $query = "DELETE FROM proyectos WHERE id IN ($placeholders)";
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

  public function getAll(): array | false
  {
    $query = "
      SELECT p.*, ap.id_alumno, jp.id_jurado FROM proyectos p 
        LEFT JOIN alumnosxproyecto ap ON p.id = ap.id_proyecto
        LEFT JOIN juradosxproyecto jp ON p.id = jp.id_proyecto
    ";
    $result = $this->connection->query($query);

    if (!$result) return false;
    if ($result->num_rows === 0) return [];

    $proyectos = [];
    $ids_jurados = [];
    $ids_estudiantes = [];

    $companyModel = new CompanyModel();
    $userModel = new UserModel();
    $studentModel = new StudentModel();

    while ($row = $result->fetch_assoc()) {
      $proyectoId = $row['id'];

      if (!array_key_exists($proyectoId, $proyectos)) {

        $empresa = $companyModel->getById($row['id_empresa']);
        $asesor = $userModel->getById(new Usuario($row['id_asesor']));
        $creado_por = $userModel->getById(new Usuario($row['creado_por']));

        $proyectos[$proyectoId] = new Proyecto(
          $proyectoId,
          $row['tema'],
          $empresa,
          $asesor,
          $row['objetivos'],
          $row['alcances_limitantes'],
          $row['observaciones'],
          $row['cd'],
          $row['estado'],
          $row['motivo'],
          $row['justificacion'],
          $row['resultados_esperados'],
          $row['fecha_presentacion'],
          $row['doc'],
          $creado_por,
          [], // Inicializamos la lista de estudiantes vacía
          []
        );
        // Inicializamos los sets para evitar duplicados
        $ids_estudiantes[$proyectoId] = [];
        $ids_jurados[$proyectoId] = [];
      }

      if ($row['id_alumno'] && !in_array($row['id_alumno'], $ids_estudiantes[$proyectoId])) {
        $ids_estudiantes[$proyectoId][] = $row['id_alumno'];
        $alumno = $studentModel->getById(new Estudiante($row['id_alumno']));
        $proyectos[$proyectoId]->estudiantes[] = $alumno;
      }

      if ($row['id_jurado'] && !in_array($row['id_jurado'], $ids_jurados[$proyectoId])) {
        $ids_jurados[$proyectoId][] = $row['id_jurado'];
        $jurado = $userModel->getById(new Usuario($row['id_jurado']));
        $proyectos[$proyectoId]->jurados[] = $jurado;
      }
    }

    return array_values($proyectos);
  }



  public function getById(int $id_proyecto): Proyecto | false
  {
    $query = "
      SELECT p.*, ap.id_alumno, jp.id_jurado FROM proyectos p 
        LEFT JOIN alumnosxproyecto ap ON p.id = ap.id_proyecto
        LEFT JOIN juradosxproyecto jp ON p.id = jp.id_proyecto
      WHERE p.id = ? ;
    ";
    $stmt = $this->connection->prepare($query);

    if (!$stmt) return false;

    $stmt->bind_param('i', $id_proyecto);

    if (!$stmt->execute()) return false;

    $result = $stmt->get_result();

    if ($result->num_rows === 0) return false;

    $proyectos = [];
    $ids_jurados = [];
    $ids_estudiantes = [];

    $companyModel = new CompanyModel();
    $userModel = new UserModel();
    $studentModel = new StudentModel();

    while ($row = $result->fetch_assoc()) {
      $proyectoId = $row['id'];

      if (!array_key_exists($proyectoId, $proyectos)) {

        $empresa = $companyModel->getById($row['id_empresa']);
        $asesor = $userModel->getById(new Usuario($row['id_asesor']));
        $creado_por = $userModel->getById(new Usuario($row['creado_por']));

        $proyectos[$proyectoId] = new Proyecto(
          $proyectoId,
          $row['tema'],
          $empresa,
          $asesor,
          $row['objetivos'],
          $row['alcances_limitantes'],
          $row['observaciones'],
          $row['cd'],
          $row['estado'],
          $row['motivo'],
          $row['justificacion'],
          $row['resultados_esperados'],
          $row['fecha_presentacion'],
          $row['doc'],
          $creado_por,
          [], // Inicializamos la lista de estudiantes vacía
          []
        );
        // Inicializamos los sets para evitar duplicados
        $ids_estudiantes[$proyectoId] = [];
        $ids_jurados[$proyectoId] = [];
      }

      if ($row['id_alumno'] && !in_array($row['id_alumno'], $ids_estudiantes[$proyectoId])) {
        $ids_estudiantes[$proyectoId][] = $row['id_alumno'];
        $alumno = $studentModel->getById(new Estudiante($row['id_alumno']));
        $proyectos[$proyectoId]->estudiantes[] = $alumno;
      }

      if ($row['id_jurado'] && !in_array($row['id_jurado'], $ids_jurados[$proyectoId])) {
        $ids_jurados[$proyectoId][] = $row['id_jurado'];
        $jurado = $userModel->getById(new Usuario($row['id_jurado']));
        $proyectos[$proyectoId]->jurados[] = $jurado;
      }
    }

    return array_values($proyectos)[0];
  }

  public function getBySubjectId(int $id_materia): array | false
  {
    $query = "
      SELECT p.*, ap.id_alumno 
      FROM proyectos p
      INNER JOIN alumnosxproyecto ap ON p.id = ap.id_proyecto
      INNER JOIN proyectoxmateria pm ON p.id = pm.id_proyecto
      WHERE pm.id_materia = ?;
    ";

    $stmt = $this->connection->prepare($query);

    if (!$stmt) return false;

    $stmt->bind_param('i', $id_materia);

    if (!$stmt->execute()) return false;

    $result = $stmt->get_result();

    if ($result->num_rows === 0) return false;

    $idsProyectos = [];
    $proyectos = [];

    $companyModel = new CompanyModel();
    $userModel = new UserModel();
    $studentModel = new StudentModel();

    while ($row = $result->fetch_assoc()) {
      if (in_array($row['id'], $idsProyectos)) {
        $proyectos[$row['id']]->estudiantes[] = $studentModel->getById($row['id_alumno']);
      } else {
        $idsProyectos[] = $row['id'];

        $empresa = $companyModel->getById($row['id_empresa']);
        $asesor = $userModel->getById($row['id_asesor']);
        $alumno = $studentModel->getById($row['id_alumno']);
        $creado_por = new Usuario($row['creado_por']);
        $proyecto = new Proyecto(
          $row['id'],
          $row['tema'],
          $empresa,
          $asesor,
          $row['objetivos'],
          $row['alcances_limitantes'],
          $row['observaciones'],
          $row['cd'],
          $row['estado'],
          $row['motivo'],
          $row['justificacion'],
          $row['resultados_esperados'],
          $row['fecha_presentacion'],
          $row['doc'],
          $creado_por,
          [$alumno]
        );

        $proyectos[$row['id']] = $proyecto;
      }
    }

    return $proyectos;
  }

  public function getByTopic(?string $topic): array | false
  {
    if (!$topic) return false;

    $query = "SELECT * FROM proyectos p LEFT JOIN alumnosxproyecto ap ON p.id = ap.id_proyecto WHERE LOWER(p.tema) LIKE ?";
    $stmt = $this->connection->prepare($query);

    if (!$stmt) return false;

    $likeTopic = "%" . $topic . "%";
    $stmt->bind_param("s", $likeTopic);
    $stmt->execute();

    $result = $stmt->get_result();

    if (!$result || $result->num_rows === 0) return [];

    $proyectos = [];
    $companyModel = new CompanyModel();
    $userModel = new UserModel();
    $studentModel = new StudentModel();

    while ($row = $result->fetch_assoc()) {
      $proyectoId = $row['id'];

      if (!array_key_exists($proyectoId, $proyectos)) {

        $empresa = $companyModel->getById($row['id_empresa']);
        $asesor = $userModel->getById(new Usuario($row['id_asesor']));
        $creado_por = $userModel->getById(new Usuario($row['creado_por']));

        $proyectos[$proyectoId] = new Proyecto(
          $proyectoId,
          $row['tema'],
          $empresa,
          $asesor,
          $row['objetivos'],
          $row['alcances_limitantes'],
          $row['observaciones'],
          $row['cd'],
          $row['estado'],
          $row['motivo'],
          $row['justificacion'],
          $row['resultados_esperados'],
          $row['fecha_presentacion'],
          $row['doc'],
          $creado_por,
          []
        );
      }

      if ($row['id_alumno']) {
        $alumno = $studentModel->getById(new Estudiante($row['id_alumno']));
        $proyectos[$proyectoId]->estudiantes[] = $alumno;
      }
    }

    return array_values($proyectos);
  }
}

<?php

namespace Controllers;

require_once 'schemas/Response.php';
require_once 'schemas/Estudiante.php';
require_once 'interfaces/IStudentService.php';

use IStudentService;
use Response;
use Estudiante;
use Grupo;

class StudentsController
{
  public function __construct(private IStudentService $studentService)
  {
  }

  public function createStudent(array $student_data): Response
  {
    $new_group = new Grupo($student_data['id_grupo'] ?? null);

    $missing_data = false;

    foreach ($student_data as $key => $value) {
      if (!array_key_exists($key, $student_data)) {
        $missing_data = true;
        break;
      }
      if (empty($value)) {
        $missing_data = true;
        break;
      }
    }

    if ($missing_data) return new Response(
      false,
      'Asegurate de proporcionar la información necesaria para crear un usuario'
    );

    $new_student = new Estudiante(
      null,
      $student_data['carnet'] ?? '',
      $student_data['nombres'] ?? '',
      $student_data['apellidos'] ?? '',
      $student_data['sexo'] ?? '',
      $student_data['email'] ?? '',
      $student_data['jornada'] ?? '',
      $student_data['direccion'] ?? '',
      $student_data['tel_alumno'] ?? '',
      $student_data['responsable'] ?? '',
      $student_data['tel_responsable'] ?? '',
      $student_data['foto'] ?? '',
      $student_data['clave'] ?? '',
      $student_data['estado_alumno'] ?? '',
      $student_data['year_ingreso'] ?? null,
      $new_group
    );

    $result = $this->studentService->save($new_student);

    if ($result instanceof Estudiante) {
      return new Response(true, 'El estudiante se ha creado exitosamente', [$result]);
    } else {
      return new Response(
        false,
        'Ha ocurrido un error al crear el estudiante, por favor intentelo de nuevo.'
      );
    }
  }

  public function deleteStudent(array $student_data): Response
  {
    $studentId = $student_data['id'] ?? null;

    if (!$studentId) {
      return new Response(
        false,
        'Asegurate de proporcionar los datos necesarios para la eliminación del estudiante.'
      );
    }

    $student = new Estudiante($studentId);

    $result = $this->studentService->delete($student);

    if ($result instanceof Estudiante) {
      return new Response(true, 'El estudiante ha sido eliminado correctamente', [$result]);
    } else {
      return new Response(
        false,
        'Ha ocurrido un error al eliminar el estudiante, por favor intentelo de nuevo'
      );
    }
  }

  public function updateStudent(array $student_data): Response
  {
    $studentId = $student_data['id'] ?? null;

    if (!$studentId) return new Response(
      false,
      'Asegurate de proporcionar los datos necesarios para actualizar al estudiante.'
    );

    $student_to_update = new Estudiante(
      $studentId,
      $student_data['carnet'] ?? null,
      $student_data['nombres'] ?? null,
      $student_data['apellidos'] ?? null,
      $student_data['sexo'] ?? null,
      $student_data['email'] ?? null,
      $student_data['jornada'] ?? null,
      $student_data['direccion'] ?? null,
      $student_data['tel_alumno'] ?? null,
      $student_data['responsable'] ?? null,
      $student_data['tel_responsable'] ?? null,
      $student_data['foto'] ?? null,
      $student_data['clave'] ?? null,
      $student_data['estado_alumno'] ?? null,
      $student_data['year_ingreso'] ?? null,
      $student_data['grupo'] ?? null
    );

    $updated_student = $this->studentService->update($student_to_update);

    if (!$updated_student) return new Response(
      false,
      'Ha ocurrido un error al actualizar el estudiante'
    );

    return new Response(
      true,
      'Estudiante actualizado exitosamente',
      [$updated_student]
    );
  }

  public function getStudents(): Response
  {
    $result = $this->studentService->getAll();

    if (!$result) return new Response(
      false,
      'Ha ocurrido un error al tratar de obtener los estudiantes.'
    );

    return new Response(true, 'Estudiantes obtenidos exitosamente.', $result);
  }

  public function getStudentByName(string $nombres, string $apellidos): Response
  {
    $result = $this->studentService->getByName($nombres, $apellidos);

    if (!$result) return new Response(
      false,
      'Ha ocurrido un error al obtener los estudiantes'
    );

    return new Response(true, 'Registros obtenidos exitosamente', $result);
  }

  public function getStudentByID(?int $id_estudiante): Response
  {
    if (!is_integer($id_estudiante)) return new Response(
      false,
      'Asegurate de proporcionar los datos correctamente'
    );

    $student = new Estudiante($id_estudiante);
    $result = $this->studentService->getById($student);

    if (!$result) return new Response(
      false,
      'Ha ocurrido un error al obtener el estudiante'
    );

    return new Response(true, 'Estudiante obtenido exitosamente', [$result]);
  }

  public function getStudentByCarnet(?string $carnet): Response
  {
    if (!$carnet) return new Response(
      false,
      'Asegurate de proporcionar los datos correctamente'
    );

    $result = $this->studentService->getByCarnet($carnet);

    if (!$result) return new Response(
      false,
      'Ha ocurrido un error al obtener el estudiante'
    );

    return new Response(true, 'Estudiante obtenido exitosamente', [$result]);
  }
}

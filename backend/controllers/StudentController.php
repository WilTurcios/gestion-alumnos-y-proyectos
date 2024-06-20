<?php

namespace Controllers;

require_once 'schemas/Response.php';
require_once 'schemas/Estudiante.php';
require_once 'exceptions/ParameterIsMissingException.php';

use Response;
use Estudiante;
use Grupo;
use ParameterIsMissingException;

class StudentController
{
  private array $requiredParameters = [
    'carnet',
    'nombres',
    'apellidos',
    'sexo',
    'email',
    'jornada',
    'direccion',
    'tel_alumno',
    'responsable',
    'tel_responsable',
    'estado_alumno',
    'year_ingreso',
    'id_grupo'
  ];

  private function getRequiredParameters()
  {
    return $this->requiredParameters;
  }

  public function __construct(private $studentService)
  {
  }

  public function createStudent(array $student_data): Response
  {
    $requiredParameters = $this->getRequiredParameters();

    $response = null;

    foreach ($requiredParameters as $key) {
      if (!array_key_exists($key, $student_data)) {
        // $response = new Response(
        //   false,
        //   400,
        //   'Bad Request: Asegurese de proporcionar todos los campos necesarios para agregar un estudiante'
        // );
        throw new ParameterIsMissingException(
          400,
          'Bad Request: Asegurese de proporcionar todos los campos necesarios para agregar un estudiante'
        );

        break;
      }

      if (empty(trim($student_data[$key]))) {
        // $response = new Response(
        //   false,
        //   400,
        //   'Bad Request: Asegurese de que los campos obligatorios no estén vacios'
        // );
        throw new ParameterIsMissingException(
          400,
          'Bad Request: Asegurese de que los campos obligatorios no estén vacios'
        );

        break;
      }
    }

    if ($response) return $response;

    $new_group = new Grupo($student_data['id_grupo'] ?? null);


    $new_student = new Estudiante(
      null,
      $student_data['carnet'],
      $student_data['nombres'],
      $student_data['apellidos'],
      $student_data['sexo'],
      $student_data['email'],
      $student_data['jornada'],
      $student_data['direccion'],
      $student_data['tel_alumno'],
      $student_data['responsable'],
      $student_data['tel_responsable'],
      $student_data['clave'],
      $student_data['estado_alumno'],
      $student_data['year_ingreso'],
      $new_group
    );

    $result = $this->studentService->save($new_student);

    if ($result instanceof Estudiante) {
      return new Response(true, 200, 'El estudiante se ha creado exitosamente', [$result]);
    } else {
      return new Response(
        false,
        500,
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
      return new Response(
        true,
        204,
        'El estudiante ha sido eliminado correctamente'
      );
    } else {
      return new Response(
        false,
        500,
        'Ha ocurrido un error al eliminar el estudiante, por favor intentelo de nuevo'
      );
    }
  }

  public function updateStudent(array $student_data): Response
  {
    $studentId = $student_data['id'] ?? null;

    if (!$studentId) return new Response(
      false,
      400,
      'Bad Request: Asegurate de proporcionar los datos necesarios para actualizar al estudiante.'
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
      $student_data['clave'] ?? null,
      $student_data['estado_alumno'] ?? null,
      (int)$student_data['year_ingreso'] ?? null,
      $student_data['grupo'] ?? null
    );

    $updated_student = $this->studentService->update($student_to_update);

    if (!$updated_student) return new Response(
      false,
      500,
      'Ha ocurrido un error al actualizar el estudiante'
    );

    return new Response(
      true,
      201,
      'Estudiante actualizado exitosamente'
    );
  }

  public function deleteManyStudents(?array $students_ids): Response
  {
    if (is_null($students_ids)) {
      return new Response(
        false,
        400,
        'Bad Request: Asegúrate de proporcionar los datos necesarios para la eliminación de los estudiantes'
      );
    }

    if (!is_array($students_ids)) {
      return new Response(
        false,
        400,
        'Bad Request: Asegúrate de proporcionar un array de IDs para eliminar los estudiantes'
      );
    }

    foreach ($students_ids as $grupo_id) {
      if (!is_integer($grupo_id)) {
        return new Response(
          false,
          400,
          'Bad Request: Asegúrate de que todos los IDs proporcionados sean enteros'
        );
      }
    }

    $result = $this->studentService->deleteMany($students_ids);

    if (!$result) {
      return new Response(
        false,
        500,
        'Ha ocurrido un error al eliminar los estudiantes, por favor inténtelo de nuevo'
      );
    }

    return new Response(true, 204, 'Los estudiantes han sido eliminados correctamente');
  }

  public function getStudents(): Response
  {
    $result = $this->studentService->getAll();

    if (!$result) return new Response(
      false,
      500,
      'Ha ocurrido un error al tratar de obtener los estudiantes.'
    );

    return new Response(
      true,
      200,
      'Estudiantes obtenidos exitosamente.',
      $result
    );
  }

  public function getStudentByName(string $nombres, string $apellidos): Response
  {
    $result = $this->studentService->getByName($nombres, $apellidos);

    if (!$result) return new Response(
      false,
      500,
      'Ha ocurrido un error al obtener los estudiantes'
    );

    return new Response(
      true,
      200,
      'Registros obtenidos exitosamente',
      $result
    );
  }

  public function getStudentByID(?int $id_estudiante): Response
  {
    if (is_null($id_estudiante)) return new Response(
      false,
      400,
      'Bad Request: Asegurate de proporcionar los datos correctamente'
    );

    $student = new Estudiante($id_estudiante);
    $result = $this->studentService->getById($student);

    if (!$result) return new Response(
      false,
      500,
      'Ha ocurrido un error al obtener el estudiante'
    );

    return new Response(
      true,
      200,
      'Estudiante obtenido exitosamente',
      [$result]
    );
  }

  public function getStudentByCarnet(?string $carnet): Response
  {
    if (!$carnet) return new Response(
      false,
      400,
      'Bad Request: Asegurate de proporcionar los datos correctamente'
    );

    $result = $this->studentService->getByCarnet($carnet);

    if (!$result) return new Response(
      false,
      500,
      'Ha ocurrido un error al obtener el estudiante'
    );

    return new Response(true, 200, 'Estudiante obtenido exitosamente', [$result]);
  }
}

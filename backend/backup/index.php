<?php
session_start();

require_once 'models/mysql/Empresas.php';
require_once 'models/mysql/Estudiantes.php';
require_once 'models/mysql/Grupos.php';
require_once 'models/mysql/usuarios.php';



if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
  header("HTTP/1.1 200 OK");
  header('Access-Control-Allow-Origin: *');
  header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS');
  header('Access-Control-Allow-Headers: Content-Type, Authorization');
  header('Content-Type: application/json');
  exit(0);
}

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
  $companies = new MySQLCompaniesService();
  $students = new MySQLStudentsService();
  $groups = new MySQLGroupsService();
  $users = new MySQLUsersService();

  $empresas = $companies->getAll();
  $estudiantes = $students->getAll();
  $grupos = $groups->getAll();
  $usuarios = $users->getAll();

  function createCsv($filename, $data, $headers)
  {
    if (!is_dir('db')) {
      mkdir('db', 0777, true);
    }

    $file = fopen($filename, 'w');
    if ($file === false) {
      die('Error al abrir el archivo para escritura.');
    }

    fputcsv($file, $headers);
    foreach ($data as $row) {
      fputcsv($file, $row);
    }

    fclose($file);
  }

  // Crear backup de empresas
  if (!empty($empresas)) {
    $empresasData = [];
    foreach ($empresas as $empresa) {
      $empresasData[] = [
        $empresa->id,
        $empresa->empresa,
        $empresa->contacto,
        $empresa->direccion,
        $empresa->email,
        $empresa->telefono
      ];
    }
    createCsv('db/empresas.csv', $empresasData, ['idempresa', 'empresa', 'contacto', 'dirección', 'email', 'teléfono']);
  }

  // Crear backup de estudiantes
  if (!empty($estudiantes)) {
    $estudiantesData = [];
    foreach ($estudiantes as $estudiante) {
      $estudiantesData[] = [
        $estudiante->id,
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
        $estudiante->clave,
        $estudiante->estado_alumno,
        $estudiante->year_ingreso,
        $estudiante->grupo ? $estudiante->grupo->id : null
      ];
    }
    createCsv('db/estudiantes.csv', $estudiantesData, ['idalumno', 'carnet', 'nombres', 'apellidos', 'sexo', 'email', 'jornada', 'dirección', 'telalumno', 'responsable', 'telresponsable', 'clave', 'estadoalumno', 'yearingreso', 'idgrupo']);
  }

  // Crear backup de grupos
  if (!empty($grupos)) {
    $gruposData = [];
    foreach ($grupos as $grupo) {
      $gruposData[] = [
        $grupo->id,
        $grupo->nombre_grupo
      ];
    }
    createCsv('db/grupos.csv', $gruposData, ['idgrupo', 'grupo']);
  }

  // Crear backup de usuarios
  if (!empty($usuarios)) {
    $usuariosData = [];
    foreach ($usuarios as $usuario) {
      $usuariosData[] = [
        $usuario->id,
        $usuario->nombre_usuario,
        $usuario->clave,
        $usuario->nombres,
        $usuario->apellidos,
        $usuario->carnet_docente,
        $usuario->email,
        $usuario->tel,
        $usuario->celular,
        $usuario->es_jurado,
        $usuario->es_asesor,
        $usuario->acceso_sistema,
        $usuario->es_admin
      ];
    }
    createCsv('db/usuarios.csv', $usuariosData, ['id', 'nombreusuario', 'clave', 'nombres', 'apellidos', 'carnetdocente', 'email', 'tel', 'celular', 'esjurado', 'esasesor', 'accesosistema', 'esadmin']);
  }

  echo json_encode(["status" => "Backup realizada con éxito"]);
} else {
  echo json_encode(["status" => "Metodo HTTP no permitido"]);
}

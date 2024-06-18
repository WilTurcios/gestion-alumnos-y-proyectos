<?php

require_once 'interfaces/IUserService.php';
require_once 'utils/encryptor.php';

use Utils\encryptor;

class MySQLUsersService implements IUserService
{
  private $host = 'localhost';
  private $user = 'root';
  private $password = '12345';
  private $db = 'dpwsld';
  private $campos = [
    'nombreusuario',
    'clave',
    'nombres',
    'apellidos',
    'carnetdocente',
    'email',
    'tel',
    'celular',
    'esjurado',
    'esasesor',
    'accesosistema',
    'esadmin'
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
    return implode(",", array_fill(0, count($this->campos), "?"));
  }

  public function save(Usuario $usuario): Usuario | false
  {
    $campos_insert = implode(",", $this->campos);
    $valores_insert = $this->getValoresInsert();
    $query = "
      INSERT INTO usuarios 
      ($campos_insert)    
      VALUES 
      ($valores_insert);
    ";

    $stmt = $this->connection->prepare($query);

    $clave_encriptada = encryptor::encrypt($usuario->clave);

    if ($stmt) {
      $stmt->bind_param(
        "ssssssssiiii",
        $usuario->nombre_usuario,
        $clave_encriptada,
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
      );

      $result = $stmt->execute();

      if ($result) {
        $usuario->id = $this->connection->insert_id;
      } else {
        echo "Error al ejecutar la consulta: " . $stmt->error;
        return null;
      }

      $stmt->close();

      return $usuario;
    } else {
      echo "Error al preparar la consulta: " . $this->connection->error;

      return null;
    }
  }

  public function delete(Usuario $usuario): Usuario | false
  {
    $query = "DELETE FROM usuarios WHERE idusuario = ?";
    $stmt = $this->connection->prepare($query);

    if ($stmt) {
      $stmt->bind_param("i", $usuario->id);
      $result = $stmt->execute();

      if (!$result) {
        $stmt->close();
        return false;
      }

      $stmt->close();

      return $usuario;
    } else {
      echo "Error al preparar la consulta: " . $this->connection->error;
      return null;
    }
  }

  public function update(Usuario $usuario): Usuario | false
  {

    $previous_user = MySQLUsersService::getById($usuario);

    if (!$previous_user) return false;

    $updated_user = new Usuario(
      $usuario->id,
      $usuario->nombre_usuario ?? $previous_user->nombre_usuario,
      $usuario->clave ?? $previous_user->clave,
      $usuario->nombres ?? $previous_user->nombres,
      $usuario->apellidos ?? $previous_user->apellidos,
      $usuario->carnet_docente ?? $previous_user->carnet_docente,
      $usuario->email ?? $previous_user->email,
      $usuario->tel ?? $previous_user->tel,
      $usuario->celular ?? $previous_user->celular,
      $usuario->es_jurado ?? $previous_user->es_jurado,
      $usuario->es_asesor ?? $previous_user->es_asesor,
      $usuario->acceso_sistema ?? $previous_user->acceso_sistema,
      $usuario->es_admin ?? $previous_user->es_admin
    );

    $campos_set = implode(",", array_map(function ($campo) {
      return "$campo = ?";
    }, $this->campos));

    $query = "
      UPDATE usuarios SET $campos_set WHERE idusuario = ?
    ";

    $stmt = $this->connection->prepare($query);

    if (!$stmt) return false;

    $stmt->bind_param(
      "ssssssssiiiii",
      $updated_user->nombre_usuario,
      $updated_user->clave,
      $updated_user->nombres,
      $updated_user->apellidos,
      $updated_user->carnet_docente,
      $updated_user->email,
      $updated_user->tel,
      $updated_user->celular,
      $updated_user->es_jurado,
      $updated_user->es_asesor,
      $updated_user->acceso_sistema,
      $updated_user->es_admin,
      $updated_user->id
    );

    $result = $stmt->execute();

    if (!$result) {
      $stmt->close();
      return false;
    }

    $stmt->close();

    return $updated_user;
  }

  public static function getById(Usuario $usuario): Usuario | false
  {
    $connection = (new self())->connection;
    $query = "SELECT * FROM usuarios WHERE idusuario = ?;";
    $stmt = $connection->prepare($query);

    if (!$stmt) return false;


    $stmt->bind_param("i", $usuario->id);
    $result = $stmt->execute();

    if (!$result) return false;

    $resultSet = $stmt->get_result();

    if ($resultSet->num_rows === 0) return false;

    $row = $resultSet->fetch_assoc();
    $usuario = new Usuario(
      $row["idusuario"],
      $row["nombreusuario"],
      $row["clave"],
      $row["nombres"],
      $row["apellidos"],
      $row["carnetdocente"],
      $row["email"],
      $row["tel"],
      $row["celular"],
      $row["esjurado"],
      $row["esasesor"],
      $row["accesosistema"],
      $row["esadmin"]
    );

    return $usuario;
  }

  public static function getAll(): array | false
  {
    $connection = (new self())->connection;
    $query = "SELECT * FROM usuarios;";
    $result = $connection->query($query);

    $usuarios = [];

    if (!$result) return false;

    while ($row = $result->fetch_assoc()) {
      $user = new Usuario(
        $row["idusuario"],
        $row["nombreusuario"],
        $row["clave"],
        $row["nombres"],
        $row["apellidos"],
        $row["carnetdocente"],
        $row["email"],
        $row["tel"],
        $row["celular"],
        $row["esjurado"],
        $row["esasesor"],
        $row["accesosistema"],
        $row["esadmin"]
      );

      $usuarios[] = $user;
    }

    return $usuarios;
  }


  public static function deleteAll(): bool
  {
    $query = "DELETE FROM usuarios;";
    $result = (new self())->connection->query($query);
    return $result;
  }


  public static function getByName(
    string $nombres,
    string $apellidos
  ): array | false {
    $usuarios = [];
    $nombre_completo = "$nombres $apellidos";

    $query = "SELECT * FROM usuarios 
      WHERE CONCAT(nombres, ' ', apellidos) LIKE %?% ;
    ";
    $stmt = (new self())->connection->prepare($query);

    if (!$stmt) return false;

    $stmt->bind_param("sss", $nombres, $apellidos, $nombre_completo);
    $stmt->execute();
    $result = $stmt->get_result();

    if (!$result || ($result->num_rows === 0)) return false;

    while ($row = $result->fetch_assoc()) {
      $usuario = new Usuario(
        $row["idusuario"],
        $row["nombreusuario"],
        $row["clave"],
        $row["nombres"],
        $row["apellidos"],
        $row["carnetdocente"],
        $row["email"],
        $row["tel"],
        $row["celular"],
        $row["esjurado"],
        $row["esasesor"],
        $row["accesosistema"],
        $row["esadmin"]
      );

      $usuarios[] = $usuario;
    }


    $stmt->close();
    return $usuarios;
  }

  public static function getByUsername(
    string $nombre_usuario
  ): Usuario | false {
    $usuario = new Usuario();
    // $query = "select * from usuarios where nombreusuario = ?;";
    $query = "select * from usuarios where nombreusuario = '$nombre_usuario';";
    // $stmt = (new self())->connection->prepare($query);
    $result = (new self())->connection->query($query);

    // if ($stmt) {
    // $stmt->bind_param("s", $nombre_usuario);
    // $stmt->execute();
    // $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        $usuario->id = $row["idusuario"];
        $usuario->nombre_usuario = $row["nombreusuario"];
        $usuario->clave = $row["clave"];
        $usuario->nombres = $row["nombres"];
        $usuario->apellidos = $row["apellidos"];
        $usuario->carnet_docente = $row["carnetdocente"];
        $usuario->email = $row["email"];
        $usuario->tel = $row["tel"];
        $usuario->celular = $row["celular"];
        $usuario->es_jurado = $row["esjurado"];
        $usuario->es_asesor = $row["esasesor"];
        $usuario->acceso_sistema = $row["accesosistema"];
        $usuario->es_admin = $row["esadmin"];

        break;
      }
    }

    // $stmt->close();
    return $usuario;
    // } else {
    //   return false;
    // }
  }

  public static function authenticate(
    string $nombre_usuario,
    string $clave = null
  ): Usuario | false {

    $usuario = self::getByUsername($nombre_usuario);

    if (!$usuario || !$clave) return false;


    $claveDesencriptada = encryptor::decrypt($usuario->clave);

    if ($clave !== $claveDesencriptada) return false;
    if (!$usuario->es_admin && !$usuario->acceso_sistema) return false;

    return $usuario;
  }
}

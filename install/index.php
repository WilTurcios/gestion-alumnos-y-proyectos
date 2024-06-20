<?php

if (isset($_POST["ok1"])) {
  //informacion para conectarse con la base de datos

  $ip = $_POST["ip"];
  $usuario = $_POST["usuario"];
  $clave = $_POST["clave"];
  $base = $_POST["base"];

  //conexion con el servidor MySQL 
  $link = mysqli_connect($ip, $usuario, $clave);
  if (!$link) {
    echo "Error: No se pudo conectar a MySQL." . PHP_EOL;
    echo "errno de depuración: " . mysqli_connect_errno() . PHP_EOL;
    echo "error de depuración: " . mysqli_connect_error() . PHP_EOL;
    exit;
  }

  //creacion de la base de datos
  $sql = "CREATE DATABASE IF NOT EXISTS " . $base;
  if ($link->query($sql) === TRUE) {
    printf("Proceso terminado.\n");
  }

  //seleccionamos la base  de datos
  $link->select_db($base);

  //creamos un array con el contenido del archivo mibase.sql
  //que tiene los comandos SQL para crear todas las tablas
  //de la base de datos

  //*******IMPORTANTE*******// 
  //en el archivo SQL no se debe de incluir las lineas para la
  //creacion de la base de datos y el uso de esta.
  $sql = explode(";", file_get_contents('dpwsld.sql')); //
  echo "<pre>";
  //var_dump($sql);
  echo "</pre>";

  //recorremos el arreglo y ejecutamos cada sentencia SQL
  $control = 1;
  foreach ($sql as $query) {
    //echo $query;
    //mysqli_query($query,$link);
    if ($control < count($sql)) {
      if ($link->query($query) === TRUE) {
      }
    }

    echo $control++;
  }

  //guardar la informacion en el archivo credenciales.php
  $fp = fopen("../credenciales.php", "w"); //abrimos el archivo para escritura

  $contenido = "<?php" . PHP_EOL;
  $contenido .= "define(\"SERVIDOR\",\"$ip\");" . PHP_EOL;
  $contenido .= "define(\"USUARIO\",\"$usuario\");" . PHP_EOL;
  $contenido .= "define(\"CONTRA\",\"$clave\");" . PHP_EOL;
  $contenido .= "define(\"BASEDATOS\",\"$base\");" . PHP_EOL;
  $contenido .= "?>";

  fwrite($fp, "$contenido");
  fclose($fp); //cerramos la conexión y liberamos la memoria
  //fin archivo credenciales.php

  echo "<h1>PROCESO TERMINADO SE RECOMIENTA<br>ELIMINAR LA CARPETA INSTALL</h1><br>";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN">
<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Documento sin título</title>
</head>
<link rel="stylesheet" type="text/css" href="../tabla.css">
<link rel="stylesheet" type="text/css" href="../botones.css">
<style>
  table.blue-form {
    border: 3px solid #3E83C9;
    margin-top: 0px;
    margin-bottom: 10px;
    font-size: 90%;
    font-weight: bold;
    box-shadow: 0px 5px 10px #666;
  }

  table.blue-form th {
    border-bottom: 3px solid #999;
    padding: 3px;
    background-color: #264fe7;
    color: white;
  }

  table.blue-form td {
    border-bottom: 1px solid #999;
    padding: 3px;
  }

  .button {
    background-color: #4CAF50;
    /* Green */
    border: none;
    color: white;
    padding: 15px 32px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 4px 2px;
    cursor: pointer;
  }

  .button2 {
    background-color: #008CBA;
  }

  /* Blue */
</style>

<body>
  <form method="post">
    <table class="blue-form" align="center">
      <tr>
        <th colspan="2">Información requerida para crear la base de datos</th>
      </tr>
      <tr>
        <td>Introduzca la dirección IP del <br />servidor de base de datos</td>
        <td><input type="text" name="ip" placeholder='0.0.0.0 &oacute; localhost' required /></td>
      </tr>
      <tr>
        <td>Introduzca el nombre del usuario</td>
        <td>
          <input type="text" name="usuario" placeholder='usuario obligatorio default root' required size="30" />
        </td>
      </tr>
      <tr>
        <td>Introduzca la contrase&ntilde;a del usuario</td>
        <td><input type="text" name="clave" placeholder='clave' /></td>
      </tr>
      <tr>
        <td>Introduzca el nombre de la base de datos</td>
        <td><input type="text" name="base" placeholder='nombre de la base de datos' /></td>
      </tr>
      <tr>


        <th colspan="2">
          <input type="submit" name="ok1" class="button button2" value="Instalar base de datos" />
        </th>
      </tr>
    </table>
    <div align="center">
      NOTA: se recomienda eliminar o renombrar la carpeta install despues de crear la base de datos.
    </div>
  </form>
</body>

</html>
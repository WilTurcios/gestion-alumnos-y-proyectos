<?php

class cls_contenido
{
  private $pages = [
    "api/usuarios" => "routes/users.php",
    "api/alumnos" => "routes/students.php",
    "api/empresas" => "routes/companies.php",
    "api/evaluaciones" => "routes/login/login.php",
    "api/proyectos" => "routes/projects.php",
    "api/jurados" => "routes/judges.php",
    "api/auth" => "routes/auth.php"
  ];

  public function mostrar_archivo()
  {
    $url = isset($_GET["url"]) ? $_GET["url"] : null;
    $url = explode('/', $url);
    if (!isset($_SESSION["user"])) return $this->pages["api/auth"];
    if ($url[0] == null) {
      return $this->pages["inicio"];
    }

    if (array_key_exists($url[0], $this->pages)) {
      return $this->pages[$url[0]];
    }

    return $this->pages["404"];;
  }
}

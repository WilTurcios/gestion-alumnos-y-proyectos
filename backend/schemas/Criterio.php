<?php
require_once "Usuario.php";

class Criterio
{
  public function __construct(
    public ?int $id = null,
    public ?string $criterio = null,
    public ?int $porcentaje = null,
    public ?string $tipo = null,
    public ?string $estado = null,
    public ?Usuario  $creado_por = null
  ) {
  }
}

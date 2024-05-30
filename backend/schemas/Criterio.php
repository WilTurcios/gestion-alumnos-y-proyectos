<?php
require_once "Fase.php";

class Criterio
{
  public function __construct(
    public ?int $id_criterio = null,
    public ?Fase $fase = null,
    public ?string $criterio = null,
    public ?int $porcentaje = null,
    public ?int $tipo = null,
    public ?string $estado = null
  ) {
  }
}

<?php

require_once "Proyecto.php";
require_once "Estudiante.php";
require_once "Usuario.php";
require_once "Fase.php";

class Evaluacion
{
  public function __construct(
    public ?int $id = null,
    public ?Proyecto $proyecto = null,
    public ?Fase $fase = null,
    public ?Usuario $jurado = null,
    public ?Estudiante $alumno = null,
    public ?float $nota_ind = null,
    public ?float $nota_grupo = null,
    public ?float $nota_final = null,
    public ?string $fecha = null,
    public ?string $hora = null,
    public ?string $observaciones = null
  ) {
  }
}

<?php
include_once "Grupo.php";

class Estudiante
{
  public function __construct(
    public ?int $id = null,
    public ?string $carnet = null,
    public ?string $nombres = null,
    public ?string $apellidos = null,
    public ?string $sexo = null,
    public ?string $email = null,
    public ?string $jornada = null,
    public ?string $direccion = null,
    public ?string $tel_alumno = null,
    public ?string $responsable = null,
    public ?string $tel_responsable = null,
    public ?string $clave = 'clave',
    public ?string $estado_alumno = 'H',
    public ?int $year_ingreso = null,
    public ?Grupo $grupo = null
  ) {
  }
}

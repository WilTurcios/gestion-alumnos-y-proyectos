<?php
include_once "Usuario.php";

class Materia
{
  public function __construct(
    public ?int $id = null,
    public ?string $nombre = null,
    public ?int $porcentaje = null,
    public ?int $porcentaje_individual = null,
    public ?int $porcentaje_grupal = null,
    public ?string $fecha_inicio = null,
    public ?string $fecha_fin = null,
    public ?string $activo = null,
    public ?int $year = null,
    public ?string $tipo = null,
    public ?Usuario $creado_por = null,
    public ?array $criterios = []
  ) {
  }
}

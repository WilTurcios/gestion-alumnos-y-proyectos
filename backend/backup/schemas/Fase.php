<?php
class Fase
{
  public function __construct(
    public ?int $id_fase = null,
    public ?string $fase = null,
    public ?int $porcentaje = null,
    public ?int $porcentaje_ind = null,
    public ?int $porcentaje_grupo = null,
    public ?string $fecha_inicio = null,
    public ?string $fecha_fin = null,
    public ?string $activo = 's',
    public ?string $tipo = null,
    public ?int $year = null
  ) {
  }
}

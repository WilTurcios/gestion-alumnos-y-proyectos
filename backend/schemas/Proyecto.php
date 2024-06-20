<?php
require_once "Empresa.php";
require_once "Usuario.php";

class Proyecto
{
  public function __construct(
    public ?int $id = null,
    public ?string $tema = null,
    public ?Empresa $empresa = null,
    public ?Usuario $asesor = null,
    public ?string $objetivos = null,
    public ?string $alcances_limitantes = null,
    public ?string $observaciones = null,
    public ?bool $cd = null,
    public ?string $estado = 'Sin evaluar',
    public ?string $motivo = null,
    public ?string $justificacion = null,
    public ?string $resultados_esperados = null,
    public ?string $fecha_presentacion = null,
    public ?string $doc = null,
    public ?Usuario $creado_por = null,
    public ?array $estudiantes = []
  ) {
  }
}

<?php
class Usuario
{
  public function __construct(
    public ?int $id = null,
    public ?string $usuario = null,
    public ?string $clave = null,
    public ?string $nombres = null,
    public ?string $apellidos = null,
    public ?string $carnet_docente = null,
    public ?string $email = null,
    public ?string $telefono = null,
    public ?string $celular = null,
    public ?bool $es_jurado = null,
    public ?bool $es_asesor = null,
    public ?bool $acceso_sistema = null,
    public ?bool $es_admin = null
  ) {
  }
}

<?php
class Empresa
{
  public function __construct(
    public ?int $id = null,
    public ?string $empresa = null,
    public ?string $contacto = null,
    public ?string $direccion = null,
    public ?string $email = null,
    public ?string $telefono = null
  ) {
  }
}

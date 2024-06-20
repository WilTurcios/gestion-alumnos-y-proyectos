<?php
class Grupo
{
  public function __construct(
    public ?int $id = null,
    public ?string $nombre_grupo = null
  ) {
  }
}

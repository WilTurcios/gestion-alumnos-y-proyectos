<?php

interface IGroupService
{
  public function save(Grupo $grupo): Grupo | false;
  public function delete(Grupo $grupo): Grupo | false;
  public function update(Grupo $grupo): Grupo | false;
  public static function getAll(): array | false;
  public static function getByName(string $nombre_grupo): Grupo | false;
  public static function getById(?int $grupo): Grupo | false;
  public static function deleteAll(): bool;
}

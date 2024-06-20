<?php

interface IUserService
{
  public function save(Usuario $usuario): Usuario | false;
  public function update(Usuario $usuario): Usuario | false;
  public function delete(Usuario $usuario): Usuario | false;
  public function deleteMany(?array $ids): bool;
  public static function getAll(): array | false;
  public static function getByName(string $nombres, string $apellidos): array | false;
  public static function getById(Usuario $usuario): Usuario | false;
  public static function getByUsername(string $nombre_usuario): Usuario | false;
  public static function deleteAll(): bool;
  public static function authenticate(
    string $nombres_usuario,
    ?string $clave
  ): Usuario | bool;
}

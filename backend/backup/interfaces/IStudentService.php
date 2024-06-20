<?php

interface IStudentService
{
  public function save(Estudiante $estudiante): Estudiante | false;
  public function update(Estudiante $estudiante): Estudiante | false;
  public function delete(Estudiante $estudiante): Estudiante | false;
  public function deleteMany(?array $ids): bool;
  public static function getAll(): array | false;
  public static function getByName(string $nombres, string $apellidos): array | false;
  public static function getByCarnet(string $carnet): Estudiante | false;
  public static function getById(Estudiante $estudiante): Estudiante | false;
  public static function deleteAll(): bool;
}

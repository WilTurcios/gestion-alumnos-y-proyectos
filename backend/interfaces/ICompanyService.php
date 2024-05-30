<?php

interface ICompanyService
{
  public function save(Empresa $empresa): Empresa | false;
  public function update(Empresa $empresa): Empresa | false;
  public function delete(?int $empresa_id): bool;
  public function deleteAll(): bool;
  public function getAll(): array | false;
  public function getById(?int $empresa_id): Empresa | false;
  public function getByName(?string $nombre): Empresa | false;
}

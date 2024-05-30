<?php

$campos = [
  'nombreusuario',
  'clave',
  'nombres',
  'apellidos',
  'carnetdocente',
  'email',
  'tel',
  'celular',
  'level',
  'esjurado',
  'esasesor',
  'accesosistema',
  'esadmin'
];

$campos_set = implode(",", array_map(function ($campo) {
  return "$campo = ?";
}, $campos));

echo $campos_set;

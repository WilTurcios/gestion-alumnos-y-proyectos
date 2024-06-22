<?php

use \Firebase\JWT\JWT;
use Firebase\JWT\Key;

$key = "d0ed017ad82cd849af073a8aba7135232d0bd108fea3a2b62921721291e7917c";

function createJWT($username)
{
  global $key;
  $payload = [
    'iss' => "itca_gestion_proyectos_api",  // Emisor del token
    'aud' => "itca_gestion_proyectos_interfaz", // Audiencia del token
    'iat' => time(),         // Tiempo en que se emitió el token
    'nbf' => time(),         // Tiempo antes del cual el token no debe ser aceptado
    'exp' => time() + 36000,  // Tiempo en que expira el token (1 hora)
    'data' => [
      'username' => $username
    ]
  ];

  return JWT::encode($payload, $key, 'HS256');
}

function validateJWT($token)
{
  global $key;
  try {
    $decoded = JWT::decode($token, new Key($key, 'HS256'));
    return (array) $decoded;
  } catch (Exception $e) {
    return false;
  }
}

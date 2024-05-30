<?php

namespace Utils;

class encryptor
{
  private static $key = "j5DQY@L6^8V&G2r";


  public static function encrypt(string $password): string|false
  {
    if (!$password) return false;

    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
    $encriptado = openssl_encrypt($password, 'aes-256-cbc', self::$key, 0, $iv);
    return base64_encode($encriptado . '::' . $iv);
  }
  public static function decrypt(string $password): string|false
  {
    if (!$password) return false;

    list($encryptedData, $iv) = explode('::', base64_decode($password), 2);
    return openssl_decrypt($encryptedData, 'aes-256-cbc', self::$key, 0, $iv);
  }
}


// echo encryptor::decrypt("Z0MzQnduYmx3UTZFZ1VvRzBnblN0dz09Ojo4kdDS1di5eYLYhz/IixDI");

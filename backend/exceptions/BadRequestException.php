<?php

class BadRequestException extends Exception
{
  public function __construct(string $message, private int $status_code = 400, Throwable $previous = null)
  {
    parent::__construct($message, $status_code);
  }

  public function getStatusCode()
  {
    return $this->status_code;
  }
}

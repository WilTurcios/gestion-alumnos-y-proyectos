<?php

class Response
{
  public function __construct(
    public bool $ok = false,
    public ?string $message = null,
    public ?array $data = null
  ) {
  }
}

<?php

class Response
{
  public function __construct(
    public bool $ok = false,
    public int $status_code,
    public ?string $message = null,
    public ?array $data = null
  ) {
  }
}

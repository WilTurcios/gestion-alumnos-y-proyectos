<?php

function parsedUrl(string $url): array
{
  $parts = explode('/', $url);
  $args['path'] = $parts[0] . '/' . $parts[1];

  $args['id'] = (isset($parts[2]) || !empty($parts[2])) ? $parts[2] : null;

  return $args;
}

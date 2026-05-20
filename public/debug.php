<?php
echo '<pre>';
echo 'SCRIPT_NAME: ' . $_SERVER['SCRIPT_NAME'] . "\n";
echo 'REQUEST_URI: ' . $_SERVER['REQUEST_URI'] . "\n";
$basePath = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
echo 'basePath: ' . $basePath . "\n";
$uri = $_SERVER['REQUEST_URI'];
if (($pos = strpos($uri, '?')) !== false) $uri = substr($uri, 0, $pos);
if (preg_match('#^(.*)/index\.php/*$#', $uri, $m)) $uri = $m[1] === '' ? '/' : $m[1];
if ($basePath !== '' && str_starts_with($uri, $basePath)) $uri = substr($uri, strlen($basePath));
$uri = '/' . trim($uri, '/');
if ($uri === '') $uri = '/';
echo 'Resolved URI (what router sees): ' . $uri . "\n";
echo 'Expected route: /' . "\n";
echo 'Match: ' . ($uri === '/' ? 'YES ✓' : 'NO ✗') . "\n";
echo '</pre>';

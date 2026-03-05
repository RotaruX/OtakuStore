<?php

define('BASE_PATH', dirname(__DIR__));

$_proto    = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$_host     = $_SERVER['HTTP_HOST']    ?? 'localhost';
$_docRoot  = rtrim(str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']), '/');
$_projRoot = str_replace('\\', '/', BASE_PATH);
$_basePath = str_replace($_docRoot, '', $_projRoot);
define('BASE_URL', $_proto . '://' . $_host . $_basePath . '/');

unset($_proto, $_host, $_docRoot, $_projRoot, $_basePath);

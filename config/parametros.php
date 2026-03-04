<?php
// ═══════════════════════════════════════════════════════
//  PARÁMETROS GLOBALES — OtakuStore
// ═══════════════════════════════════════════════════════

// Ruta absoluta del sistema de archivos a la raíz del proyecto
// Usada en require_once / include para cargar archivos PHP
define('BASE_PATH', dirname(__DIR__));

// URL base del sitio web (detectada automáticamente)
// Usada en href / src de HTML para assets, imágenes, scripts y estilos
// Ejemplo local:   http://localhost/OtakuStore/
// Ejemplo servidor: https://tudominio.com/
$_proto    = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$_host     = $_SERVER['HTTP_HOST']    ?? 'localhost';
$_script   = $_SERVER['SCRIPT_NAME'] ?? '';
$_basePath = rtrim(dirname($_script), '/');
define('BASE_URL', $_proto . '://' . $_host . $_basePath . '/');

// Limpieza de variables temporales
unset($_proto, $_host, $_script, $_basePath);

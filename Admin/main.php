<?php
include_once "ValidarIngresoApp.php"; // Aquí dentro se hace el redireccionamiento a la página de ingreso, en caso de fallar la validación
// La anterior debe ser la primera instrucción por ejecutar en el archivo web

include_once "constantesApp.php";
include_once "CSession.php";

$ObjUsuario = CSession::DemeObjUsuarioSesion();
$NombreUsuario = $ObjUsuario->DemeNombre();

include "interfaz/main.php";
?>

<?php
include_once "constantesApp.php";
include_once "CSession.php";

try
{
    $UsuarioSesionEsAdmin = false;
    $ObjUsuarioSesion = CSession::DemeObjUsuarioSesion();

    if ($ObjUsuarioSesion != NULL)
        $UsuarioSesionEsAdmin = $ObjUsuarioSesion->DemeEsAdministrador();
} // try
catch (Exception $e)
{}

include "interfaz/menuApp.php";
?>

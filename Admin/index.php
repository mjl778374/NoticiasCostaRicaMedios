<?php
$SePretendeIniciarSesion = false;
$SeInicioSesionOK = false;

if (isset($_POST["UsuarioLogin"]))
{
    $SePretendeIniciarSesion = true;
    $Usuario = $_POST["UsuarioLogin"];
    $Contrasena = $_POST["ContrasenaLogin"];
} // if (isset($_POST["UsuarioLogin"]))

$NumError = 0;
$MensajeOtroError = "";

// A continuación el código fuente de la implementación
include_once "CSession.php";
CSession::Inicializar();

include_once "CUsuario.php";
include_once "CUsuarios.php";

if ($SePretendeIniciarSesion)
{
    try
    {
        $Usuarios = new CUsuarios();
        $Usuarios->ValidarLogin($Usuario, $Contrasena, $Existe, $ObjUsuario);

        if (!$Existe)
            $NumError = 2;
        else
        {
            CSession::FijarInicioSesionValido($ObjUsuario);
            $SeInicioSesionOK = true;
        } // else
    } // try
    catch (Exception $e)
    {
        $NumError = 1;
        $MensajeOtroError = $e->getMessage();
    } // catch (Exception $e)
} // if ($SePretendeIniciarSesion)
// El anterior fue el código fuente de la implementación

include "interfaz/index.php";
?>

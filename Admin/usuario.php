<?php
$ValidarUsuarioSesionEsAdmin = 1; // Este es un parámetro que recibe "ValidarIngresoApp.php"
include_once "ValidarIngresoApp.php"; // Aquí dentro se hace el redireccionamiento a la página de ingreso, en caso de fallar la validación
// La anterior debe ser la primera instrucción por ejecutar en el archivo web

$Usuario = "";
$Cedula = "";
$Nombre = "";
$BitEsAdministrador = 0;
$BitBorrarContrasena = 0;
$SePretendeGuardarInformacion = false;
$SeGuardoInformacionExitosamente = false;

if (isset($_POST["Usuario"]))
{
    $SePretendeGuardarInformacion = true;
    $Usuario = $_POST["Usuario"];
    $Cedula = $_POST["Cedula"];
    $Nombre = $_POST["Nombre"];

    if ($_POST["EsAdministrador"] == "on")
        $BitEsAdministrador = 1;

    if ($_POST["BorrarContrasena"] == "on")
        $BitBorrarContrasena = 1;
} // if (isset($_POST["Usuario"]))

try
{
    include_once "ValidarParametroModo.php";
    
    if (strcmp($Modo, $MODO_CAMBIO) == 0)
    {
        $URLFormulario = "usuario.php";
        $NombreCampoId = "IdUsuario";
        // Los anteriores dos son parámetros que recibe "ValidarParametroId.php"
        include_once "ValidarParametroId.php";
        $IdUsuario = $ValorCampoId; // Este es un dato que se obtiene en "ValidarParametroId.php"
    } // if (strcmp($Modo, $MODO_CAMBIO) == 0)
} // try
catch (Exception $e)
{
    $NumError = 1;
    $MensajeOtroError = $e->getMessage();
} // catch (Exception $e)

// A continuación el código fuente de la implementación
try
{
    $ObjUsuario = NULL;
    include_once "CUsuarios.php";

    if ($NumError == 0 && $SePretendeGuardarInformacion)
    {
        $Usuarios = new CUsuarios();

        if (strcmp($Modo, $MODO_ALTA) == 0)
            $Usuarios->AltaUsuario($Usuario, $Cedula, $Nombre, $BitEsAdministrador, $NumError, $ObjUsuario);

        elseif (strcmp($Modo, $MODO_CAMBIO) == 0)
            $Usuarios->CambioUsuario($IdUsuario, $Usuario, $Cedula, $Nombre, $BitEsAdministrador, $BitBorrarContrasena, $NumError, $ObjUsuario);

        $BitBorrarContrasena = 0;

        if ($NumError == 0)
            $SeGuardoInformacionExitosamente = true;
    } // if ($NumError == 0 && $SePretendeGuardarInformacion)

    if ($NumError == 0 && strcmp($Modo, $MODO_CAMBIO) == 0)
    {
        $Usuarios = new CUsuarios();
        $Usuarios->ConsultarXUsuario($IdUsuario, $Existe, $ObjUsuario);

        if (!$Existe)
            $NumError = 2;
    } // if ($NumError == 0 && strcmp($Modo, $MODO_CAMBIO) == 0)
} // try
catch (Exception $e)
{
    $NumError = 1;
    $MensajeOtroError = $e->getMessage();
} // catch (Exception $e)
// El anterior fue el código fuente de la implementación

if ($ObjUsuario != NULL)
{
    $IdUsuario = $ObjUsuario->DemeIdUsuario();
    $Usuario = $ObjUsuario->DemeUsuario();
    $Cedula = $ObjUsuario->DemeCedula();
    $Nombre = $ObjUsuario->DemeNombre();
    $BitEsAdministrador = $ObjUsuario->DemeEsAdministrador();
} // if ($ObjUsuario != NULL)

include "interfaz/usuario.php";
?>

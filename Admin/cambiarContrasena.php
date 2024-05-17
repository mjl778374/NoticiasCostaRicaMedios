<?php
include_once "ValidarIngresoApp.php"; // Aquí dentro se hace el redireccionamiento a la página de ingreso, en caso de fallar la validación
// La anterior debe ser la primera instrucción por ejecutar en el archivo web

$SePretendeCambiarContrasena = false;
$SeCambioContrasenaExitosamente = false;

if (isset($_POST["ContrasenaActual"]))
{
    $SePretendeCambiarContrasena = true;
    $ContrasenaAnterior = $_POST["ContrasenaActual"];
    $NuevaContrasena = $_POST["NuevaContrasena"];
    $ConfirmacionNuevaContrasena = $_POST["ConfirmarNuevaContrasena"];
} // if (isset($_POST["ContrasenaActual"]))

// A continuación el código fuente de la implementación
include_once "CUsuarios.php";

if ($SePretendeCambiarContrasena)
{
    try
    {
        $Usuarios = new CUsuarios();
        $ObjUsuario = CSession::DemeObjUsuarioSesion();

        if ($ObjUsuario != NULL)
        {
            $Usuarios->CambiarContrasena($ObjUsuario->DemeIdUsuario(), $ContrasenaAnterior, $NuevaContrasena, $ConfirmacionNuevaContrasena, $NumError, $LongitudMinimaContrasena, $CaracteresEspeciales);

            if ($NumError == 0)
                $SeCambioContrasenaExitosamente = true;
        } // if ($ObjUsuario != NULL)
    } // try
    catch (Exception $e)
    {
        $NumError = 1;
        $MensajeOtroError = $e->getMessage();
    } // catch (Exception $e)
} // if ($SePretendeCambiarContrasena)

include "interfaz/cambiarContrasena.php";
// El anterior fue el código fuente de la implementación
?>

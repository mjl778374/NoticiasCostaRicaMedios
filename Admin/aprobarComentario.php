<?php
include_once "ValidarIngresoApp.php"; // Aquí dentro se hace el redireccionamiento a la página de ingreso, en caso de fallar la validación
// La anterior debe ser la primera instrucción por ejecutar en el archivo web

$Estatus = "";
$FechaHoraEnvio = "";
$Comentario = "";

if (isset($_POST["Estatus"]))
{
    $SePretendeGuardarInformacion = true;
    $Estatus = $_POST["Estatus"];
    $FechaHoraEnvio = $_POST["FechaHoraEnvio"];
    $Comentario = $_POST["Comentario"];
} // if (isset($_POST["Estatus"]))

try
{
    $URLFormulario = "aprobarComentario.php";
    $NombreCampoId = "IdNoticia";
    $IncorporarParametroModo = 0;
    $ArregloOtrosParametros = array("Consecutivo", $_GET["Consecutivo"]);
    // Los anteriores dos son parámetros que recibe "ValidarParametroId.php"
    include "ValidarParametroId.php";
    $IdNoticia = $ValorCampoId; // Este es un dato que se obtiene en "ValidarParametroId.php"

    $URLFormulario = "aprobarComentario.php";
    $NombreCampoId = "Consecutivo";
    $IncorporarParametroModo = 0;    
    $ArregloOtrosParametros = array("IdNoticia", $IdNoticia);
    // Los anteriores dos son parámetros que recibe "ValidarParametroId.php"
    include "ValidarParametroId.php";
    $ConsecutivoComentario = $ValorCampoId; // Este es un dato que se obtiene en "ValidarParametroId.php"
} // try
catch (Exception $e)
{
    $NumError = 1;
    $MensajeOtroError = $e->getMessage();
} // catch (Exception $e)

// A continuación el código fuente de la implementación
try
{
    include "CEstatusComentarios.php";
    $EstatusComentarios = new CEstatusComentarios();
    $ListadoEstatus = $EstatusComentarios->DemeTodosEstatus();

    $ObjComentario = NULL;
    include_once "CComentarios.php";

    if ($NumError == 0 && $SePretendeGuardarInformacion)
    {
        $Comentarios = new CComentarios();
        $Comentarios->CambioEstatusComentario($IdNoticia, $ConsecutivoComentario, $Estatus, $NumError);

        if ($NumError == 0)
            $SeGuardoInformacionExitosamente = true;
    } // if ($NumError == 0 && $SePretendeGuardarInformacion)
        
    if ($NumError == 0)
    {
        $Comentarios = new CComentarios();    
        $Comentarios->ConsultarXComentario($IdNoticia, $ConsecutivoComentario, $Existe, $ObjComentario);
        
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

if ($ObjComentario != NULL)
{
    $Estatus = $ObjComentario->DemeEstatus();
    $FechaHoraEnvio = $ObjComentario->DemeFechaHoraEnvio();
    $Comentario = $ObjComentario->DemeComentario();
} // if ($ObjComentario != NULL)

include "interfaz/aprobarComentario.php";
?>

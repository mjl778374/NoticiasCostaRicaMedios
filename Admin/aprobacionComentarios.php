<?php
include_once "ValidarIngresoApp.php"; // Aquí dentro se hace el redireccionamiento a la página de ingreso, en caso de fallar la validación
// La anterior debe ser la primera instrucción por ejecutar en el archivo web

$FechaEnvio = "";
$Estatus = "";

if (isset($_GET["FechaEnvio"]) || isset($_GET["Estatus"]))
{
    $SePretendeConsultarInformacion = true;
    $FechaEnvio = $_GET["FechaEnvio"];
    $Estatus = $_GET["Estatus"];
} // if (isset($_GET["FechaEnvio"]) || isset($_GET["Estatus"]))

// A continuación el código fuente de la implementación
try
{
    include "constantesApp.php";
    include_once "CComentario.php"; 
            
    include "CEstatusComentarios.php";
    $EstatusComentarios = new CEstatusComentarios();
    $ListadoEstatus = $EstatusComentarios->DemeTodosEstatus();

    include_once "CFechasHoras.php";
    $FechasHoras = new CFechasHoras();
    
    if (!$FechasHoras->EsFechaValida($FechaEnvio))
        $FechaEnvio = $FechasHoras->DemeFechaHoy($FORMATO_FECHAS_SQL);

    if ($SePretendeConsultarInformacion)
    {
        include_once "CComentarios.php";    
        $Comentarios = new CComentarios();
        $ListadoComentarios = $Comentarios->DemeTodosComentarios($FechaEnvio, $Estatus);
    } // if ($SePretendeConsultarInformacion)
} // try
catch (Exception $e)
{
    $NumError = 1;
    $MensajeOtroError = $e->getMessage();
} // catch (Exception $e)
// El anterior fue el código fuente de la implementación

include "interfaz/aprobacionComentarios.php";
?>

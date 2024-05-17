<?php
include_once "CFormateadorMensajes.php";

if ($NumError != 0)
{
    if ($NumError == 1)
        $MensajeXDesglosar = CFormateadorMensajes::FormatearMensajeError($MensajeOtroError);
} // if ($NumError != 0)
?>
<!DOCTYPE html>
<html>
<?php
$IncluirEncabezadosFecha = 1; // Este es un parámetro que recibe "encabezados.php"
include "encabezados.php";
?>
<body>
<?php
$FormularioActivo = "AprobacionComentarios"; // Este es un parámetro que recibe "menuApp.php"
include "menuApp.php";

include "FuncionesUtiles.php";
?>
<form method="get">
    <div class="container mt-4">
        <div class="form-row justify-content-center">
            <div class="form-group col-8 col-md-6 col-lg-4">
                <input type="hidden" name="FechaEnvio" id="FechaEnvio"></text>
                <label for="ControlFechaEnvio">Fecha de Envío</label>
                <div id="ControlFechaEnvio" name="ControlFechaEnvio"></div>
            </div>
        </div>    
        <div class="form-row justify-content-center">
            <div class="form-group col-8 col-md-6 col-lg-4">
                <label for="IdEstatus">Estado</label>
<?php
$PrimerItemListaSeleccion = [];
$ItemesListaSeleccion = $ListadoEstatus;
$PrimerItemListaSeleccion[] = array($ID_ITEM_NO_SELECCIONADO_EN_LISTA_SELECCION, "Cualquier estado...");
$ItemesListaSeleccion = array_merge($PrimerItemListaSeleccion, $ItemesListaSeleccion);
$IdItemSeleccionado = $Estatus;
// Los anteriores son parámetros que se le envían a la lista de selección
?>
                <?php $IdListaSeleccion="Estatus"; $NombreListaSeleccion="Estatus"; include "componenteListaSeleccion.php" ?>
            </div>
        </div>        
        <div class="form-row justify-content-center mt-4">
            <div class="form-group col-8 col-md-6 col-lg-4">
                <button type="submit" class="btn btn-primary">Consultar</button>
            </div>
        </div>
    </div>
<?php

$URLFormulario = "aprobacionComentarios.php";  // Este es un parámetro que recibe "AfinarParametrosListado.php"
$ParametrosURL = "?FechaEnvio=" . $FechaEnvio . "&Estatus=" . $Estatus; // Este es un parámetro que recibe "AfinarParametrosListado.php"
$ListadoDatos = $ListadoComentarios; // Este es un parámetro que reciben "AfinarParametrosListado.php" y "DesglosarTablaDatos.php"
CComentario::FijarVistaDatos(1); // Este es un dato que necesita "DesglosarTablaDatos.php"
include_once "AfinarParametrosListado.php";
include_once "DesglosarTablaDatos.php";

$URL = "aprobacionComentarios.php";
$ParametrosURL = "?FechaEnvio=" . $FechaEnvio . "&Estatus=" . $Estatus;
// Los anteriores son parámetros que recibe "componentePaginacion.php"

if ($NumPaginas > 0)
    include "componentePaginacion.php";
    
if ($MensajeXDesglosar != "")
    echo $MensajeXDesglosar;
?>    
</form>
</body>
<?php
$IdControl = "ControlFechaEnvio"; $FormatoFecha = $FORMATO_FECHAS_CONTROLES_FECHA; $FechaInicial = $FechaEnvio; $IdControlCopia="FechaEnvio"; include "componenteFecha.php";
?>
</html>

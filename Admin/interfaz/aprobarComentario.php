<?php
include_once "CFormateadorMensajes.php";

$ErrorNoExisteComentarioConIdEspecificado = "No existe el comentario con el id de noticia " . $IdNoticia . " y consecutivo " . $ConsecutivoComentario . " .";
$ErrorDebeSeleccionarEstatus = "Debe seleccionar un estado";

if ($NumError != 0)
{
    if ($NumError == 1)
        $MensajeXDesglosar = CFormateadorMensajes::FormatearMensajeError($MensajeOtroError);
    elseif ($NumError == 2)
        $MensajeXDesglosar = CFormateadorMensajes::FormatearMensajeError($ErrorNoExisteComentarioConIdEspecificado);
    elseif ($NumError == 3)
        $MensajeXDesglosar = CFormateadorMensajes::FormatearMensajeError($ErrorDebeSeleccionarEstatus);
    elseif ($NumError == 1001)
        $MensajeXDesglosar = CFormateadorMensajes::FormatearMensajeError($ErrorNoExisteComentarioConIdEspecificado);
    elseif ($NumError != 0)
        $MensajeXDesglosar = CFormateadorMensajes::FormatearMensajeError("No se manejó el error número " . $NumError . " en el proceso 'CambioEstatusComentarioNoticia'.");
    } // elseif ($NumError != 0)
elseif ($SeGuardoInformacionExitosamente)
    $MensajeXDesglosar = CFormateadorMensajes::FormatearMensajeOK("Se cambió el estado del comentario exitosamente.");

$Comentario = htmlspecialchars($Comentario);
?>
<!DOCTYPE html>
<html>
<?php
include "encabezados.php";
?>
<body>
<?php
$FormularioActivo = "AprobacionComentarios"; // Este es un parámetro que recibe "menuApp.php"
include "menuApp.php";

include "FuncionesUtiles.php";
?>
<form method="post">
    <div class="container mt-4">
        <div class="form-row justify-content-center">
            <div class="form-group col-8 col-md-6 col-lg-4">
                <label for="FechaHoraEnvio">Fecha y Hora de Envio</label>
                <input type="text" class="form-control" id="FechaHoraEnvio" name="FechaHoraEnvio" value="<?php echo $FechaHoraEnvio; ?>" readonly>
            </div>
        </div>    
        <div class="form-row justify-content-center">
            <div class="form-group col-8 col-md-6 col-lg-4">
                <label for="Comentario">Comentario</label>
                <textarea class="form-control" id="Comentario" name="Comentario" rows="20" readonly><?php echo $Comentario; ?></textarea>
            </div>
        </div>        
        <div class="form-row justify-content-center">
            <div class="form-group col-8 col-md-6 col-lg-4">
                <label for="Estatus">Estado</label>
<?php
$PrimerItemListaSeleccion = [];
$ItemesListaSeleccion = $ListadoEstatus;
$PrimerItemListaSeleccion[] = array($ID_ITEM_NO_SELECCIONADO_EN_LISTA_SELECCION, "Seleccione un estado...");
$ItemesListaSeleccion = array_merge($PrimerItemListaSeleccion, $ItemesListaSeleccion);
$IdItemSeleccionado = $Estatus;
// Los anteriores son parámetros que se le envían a la lista de selección
?>
                <?php $IdListaSeleccion="Estatus"; $NombreListaSeleccion="Estatus"; include "componenteListaSeleccion.php" ?>
            </div>
        </div>        
        <div class="form-row justify-content-center mt-4">
            <div class="form-group col-8 col-md-6 col-lg-4">
                <button type="submit" class="btn btn-primary">Guardar</button>
                <button type="button" class="btn btn-primary" onclick="window.location.href='aprobacionComentarios.php';">Regresar</button>
            </div>
        </div>
    </div>
<?php

if ($MensajeXDesglosar != "")
    echo $MensajeXDesglosar;
?>
</form>
</body>
</html>

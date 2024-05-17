<?php
if ($SeGuardoInformacionExitosamente && strcmp($Modo, $MODO_ALTA) == 0)
{    
    echo "<script>window.location.href = 'noticia.php?Modo=" . $MODO_CAMBIO . "&IdNoticia=" . $IdNoticia . "';</script>"; // Se carga la noticia guardada.
    exit;
} // if ($SeGuardoInformacionExitosamente && strcmp($Modo, $MODO_ALTA) == 0)

include_once "CFormateadorMensajes.php";
include_once "CPalabras.php";

$ErrorNoExisteNoticiaConIdEspecificado = "No existe la noticia con el id " . $IdNoticia . ".";
$ErrorTituloInvalido = "El titulo debe tener al menos uno de los siguientes caracteres " . CPalabras::DemeCaracteresValidos();
$ErrorResumenInvalido = "El resumen debe tener al menos uno de los siguientes caracteres " . CPalabras::DemeCaracteresValidos();
$ErrorURLRelativaInvalido = "La URL relativa debe tener al menos uno de los siguientes caracteres " . CPalabras::DemeCaracteresValidos();
$ErrorDebeSeleccionarUsuarioAutor = "Debe seleccionar un usuario autor";
$ErrorNoExisteUsuarioAutor = "El usuario autor seleccionado no existe";

if ($NumError != 0)
{
    if ($NumError == 1)
        $MensajeXDesglosar = CFormateadorMensajes::FormatearMensajeError($MensajeOtroError);
    elseif ($NumError == 2)
        $MensajeXDesglosar = CFormateadorMensajes::FormatearMensajeError($ErrorNoExisteNoticiaConIdEspecificado);
    else
    {
        if (strcmp($Modo, $MODO_ALTA) == 0)
        {
            if ($NumError == 1001)
                $MensajeXDesglosar = CFormateadorMensajes::FormatearMensajeError($ErrorTituloInvalido);
            elseif ($NumError == 1002)
                $MensajeXDesglosar = CFormateadorMensajes::FormatearMensajeError($ErrorResumenInvalido);
            elseif ($NumError == 1003)
                $MensajeXDesglosar = CFormateadorMensajes::FormatearMensajeError($ErrorURLRelativaInvalido);
            elseif ($NumError == 1004)
            {
                if ($IdUsuarioAutor == $ID_ITEM_NO_SELECCIONADO_EN_LISTA_SELECCION)
                    $MensajeXDesglosar = CFormateadorMensajes::FormatearMensajeError($ErrorDebeSeleccionarUsuarioAutor);
                else
                    $MensajeXDesglosar = CFormateadorMensajes::FormatearMensajeError($ErrorNoExisteUsuarioAutor);
            } // elseif ($NumError == 1004)
            elseif ($NumError != 0)
                $MensajeXDesglosar = CFormateadorMensajes::FormatearMensajeError("No se manejó el error número " . $NumError . " en el proceso 'AltaNoticia'.");
        } // if (strcmp($Modo, $MODO_ALTA) == 0)

        elseif (strcmp($Modo, $MODO_CAMBIO) == 0)
        {
            if ($NumError == 1001)
                $MensajeXDesglosar = CFormateadorMensajes::FormatearMensajeError($ErrorNoExisteNoticiaConIdEspecificado);
            elseif ($NumError == 2001)
                $MensajeXDesglosar = CFormateadorMensajes::FormatearMensajeError($ErrorTituloInvalido);
            elseif ($NumError == 2002)
                $MensajeXDesglosar = CFormateadorMensajes::FormatearMensajeError($ErrorResumenInvalido);
            elseif ($NumError == 2003)
                $MensajeXDesglosar = CFormateadorMensajes::FormatearMensajeError($ErrorURLRelativaInvalido);
            elseif ($NumError == 2004)
            {
                if ($IdUsuarioAutor == $ID_ITEM_NO_SELECCIONADO_EN_LISTA_SELECCION)
                    $MensajeXDesglosar = CFormateadorMensajes::FormatearMensajeError($ErrorDebeSeleccionarUsuarioAutor);
                else
                    $MensajeXDesglosar = CFormateadorMensajes::FormatearMensajeError($ErrorNoExisteUsuarioAutor);
            } // elseif ($NumError == 2004)
            elseif ($NumError != 0)
                $MensajeXDesglosar = CFormateadorMensajes::FormatearMensajeError("No se manejó el error número " . $NumError . " en el proceso 'CambioNoticia'.");
        } // elseif (strcmp($Modo, $MODO_CAMBIO) == 0)
    } // else
} // if ($NumError != 0)
elseif ($SeGuardoInformacionExitosamente)
    $MensajeXDesglosar = CFormateadorMensajes::FormatearMensajeOK("Se guardó la noticia exitosamente.");

$Titulo = htmlspecialchars($Titulo);
$Resumen = htmlspecialchars($Resumen);
$URLRelativa = htmlspecialchars($URLRelativa);

$HabilitarBorradoContrasena = "";
$BorradoContrasenaSeleccionado = "";

$MaximoTamanoCampoTitulo = CNoticias::MAXIMO_TAMANO_CAMPO_TITULO;
$MaximoTamanoCampoResumen = CNoticias::MAXIMO_TAMANO_CAMPO_RESUMEN;
$MaximoTamanoCampoURLRelativa = CNoticias::MAXIMO_TAMANO_CAMPO_URL_RELATIVA;

$URLCarpetaNoticiasServidor = CNoticia::URL_CARPETA_NOTICIAS_SERVIDOR;

?>
<!DOCTYPE html>
<html>
<?php
$IncluirEncabezadosFecha = 1; // Este es un parámetro que recibe "encabezados.php"
include "encabezados.php";
?>
<body>
<?php
$FormularioActivo = "Noticia"; // Este es un parámetro que recibe "menuApp.php"
include "menuApp.php";

include "FuncionesUtiles.php";
?>
<form method="post">
    <div class="container mt-4">
        <div class="form-row justify-content-center">
            <div class="form-group col-8 col-md-6 col-lg-4">
                <label for="Titulo">Título de la Noticia</label>
                <input type="text" class="form-control" id="Titulo" name="Titulo" placeholder="Ingrese el título de la noticia" value="<?php echo $Titulo; ?>" maxlength="<?php echo $MaximoTamanoCampoTitulo;?>">
            </div>
        </div>    
        <div class="form-row justify-content-center">
            <div class="form-group col-8 col-md-6 col-lg-4">
                <input type="hidden" name="FechaPublicacion" id="FechaPublicacion"></text>
                <label for="ControlFechaPublicacion">Fecha de Publicación</label>
                <div id="ControlFechaPublicacion" name="ControlFechaPublicacion"></div>
            </div>
        </div>    
        <div class="form-row justify-content-center">
            <div class="form-group col-8 col-md-6 col-lg-4">
                <label for="Resumen">Resumen</label>
                <textarea class="form-control" id="Resumen" name="Resumen" rows="6" placeholder="Ingrese el resumen de la noticia" maxlength="<?php echo $MaximoTamanoCampoResumen;?>"><?php echo $Resumen; ?></textarea>
            </div>
        </div>        
        <div class="form-row justify-content-center">
            <div class="form-group col-8 col-md-6 col-lg-4">
                <label for="URLRelativa">URL Relativa</label>
                <input type="text" class="form-control" id="URLRelativa" name="URLRelativa" placeholder="Ingrese la URL relativa de la noticia" value="<?php echo $URLRelativa; ?>" maxlength="<?php echo $MaximoTamanoCampoURLRelativa;?>">
                <button type="button" class="btn btn-primary mt-2" onclick="AbrirURL('<?php echo FormatearTextoJS($URLCarpetaNoticiasServidor)?>', document.getElementById('URLRelativa').value);">Probar</button>                
            </div>
        </div>            
        <div class="form-row justify-content-center">
            <div class="form-group col-8 col-md-6 col-lg-4">
                <label for="IdUsuarioAutor">Autor de la Noticia</label>
<?php
$PrimerItemListaSeleccion = [];
$ItemesListaSeleccion = $ListadoUsuarios;
$PrimerItemListaSeleccion[] = array($ID_ITEM_NO_SELECCIONADO_EN_LISTA_SELECCION, "Seleccione un usuario...");
$ItemesListaSeleccion = array_merge($PrimerItemListaSeleccion, $ItemesListaSeleccion);
$IdItemSeleccionado = $IdUsuarioAutor;
// Los anteriores son parámetros que se le envían a la lista de selección
?>
                <?php $IdListaSeleccion="IdUsuarioAutor"; $NombreListaSeleccion="IdUsuarioAutor"; include "componenteListaSeleccion.php" ?>
            </div>
        </div>        
        <div class="form-row justify-content-center mt-4">
            <div class="form-group col-8 col-md-6 col-lg-4">
                <button type="submit" class="btn btn-primary">Guardar</button>
                <button type="button" class="btn btn-primary" onclick="window.location.href='noticias.php';">Regresar</button>
            </div>
        </div>
    </div>
<?php

if ($MensajeXDesglosar != "")
    echo $MensajeXDesglosar;
?>
</form>
</body>
<?php
$IdControl = "ControlFechaPublicacion"; $FormatoFecha = $FORMATO_FECHAS_CONTROLES_FECHA; $FechaInicial = $FechaPublicacion; $IdControlCopia="FechaPublicacion"; include "componenteFecha.php";
?>
</html>

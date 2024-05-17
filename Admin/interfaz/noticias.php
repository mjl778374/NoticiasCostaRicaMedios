<!DOCTYPE html>
<html>
<?php
include "encabezados.php";
?>
<body>
<?php
$FormularioActivo = "Noticias";
$URLFormularioActivo = "noticias.php";
// Los anteriores son parámetros que recibe "menuApp.php"
include "menuApp.php";

$URLFormulario = "noticias.php";  // Este es un parámetro que recibe "AfinarParametrosListado.php"
$ListadoDatos = $ListadoNoticias; // Este es un parámetro que reciben "AfinarParametrosListado.php" y "DesglosarTablaDatos.php"
CNoticia::FijarVistaDatos(1); // Este es un dato que necesita "DesglosarTablaDatos.php"
include_once "AfinarParametrosListado.php";
include_once "DesglosarTablaDatos.php";

include_once "CFormateadorMensajes.php";
$MensajeXDesglosar = "";

if ($NumError == 1)
    $MensajeXDesglosar = CFormateadorMensajes::FormatearMensajeError($MensajeOtroError);
?>
<div class="container mt-4 mb-4">
<a href="noticia.php?Modo=<?php echo $MODO_ALTA;?>" class="btn btn-primary" role="button" aria-pressed="true">Agregar Noticia</a>
</div>

<?php
$URL = "noticias.php";
// Los anteriores son parámetros que recibe "componentePaginacion.php"

if ($NumPaginas > 0)
    include "componentePaginacion.php";
?>
<?php
if ($MensajeXDesglosar != "")
    echo $MensajeXDesglosar;
?>
</body>
</html>

<!DOCTYPE html>
<html>
<?php
include "encabezados.php";
?>
<body>
<?php
$FormularioActivo = "Noticias";
$URLFormularioActivo = "busquedaNoticias.php";
// Los anteriores son parámetros que recibe "menuApp.php"
include "menuApp.php";

$URLFormulario = "busquedaNoticias.php";  // Este es un parámetro que recibe "AfinarParametrosListado.php"
$ListadoDatos = $ListadoNoticias; // Este es un parámetro que reciben "AfinarParametrosListado.php" y "DesglosarTablaDatos.php"
CNoticia::FijarVistaDatos(2); // Este es un dato que necesita "DesglosarTablaDatos.php"
include_once "AfinarParametrosListado.php";
include_once "DesglosarTablaDatos.php";

include_once "CFormateadorMensajes.php";
$MensajeXDesglosar = "";

if ($NumError == 1)
    $MensajeXDesglosar = CFormateadorMensajes::FormatearMensajeError($MensajeOtroError);
?>
<?php
$URL = "busquedaNoticias.php";
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

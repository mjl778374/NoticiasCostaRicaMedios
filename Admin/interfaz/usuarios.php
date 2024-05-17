<!DOCTYPE html>
<html>
<?php
include "encabezados.php";
?>
<body>
<?php
$FormularioActivo = "Usuarios";
$URLFormularioActivo = "usuarios.php";
// Los anteriores son parámetros que recibe "menuApp.php"
include "menuApp.php";

$URLFormulario = "usuarios.php";  // Este es un parámetro que recibe "AfinarParametrosListado.php"
$ListadoDatos = $ListadoUsuarios; // Este es un parámetro que reciben "AfinarParametrosListado.php" y "DesglosarTablaDatos.php"
include_once "AfinarParametrosListado.php";
include_once "DesglosarTablaDatos.php";

include_once "CFormateadorMensajes.php";
$MensajeXDesglosar = "";

if ($NumError == 1)
    $MensajeXDesglosar = CFormateadorMensajes::FormatearMensajeError($MensajeOtroError);
?>
<div class="container mt-4 mb-4">
<a href="usuario.php?Modo=<?php echo $MODO_ALTA;?>" class="btn btn-primary" role="button" aria-pressed="true">Agregar Usuario</a>
</div>

<?php
$URL = "usuarios.php";
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

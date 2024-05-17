<?php
include_once "CFormateadorMensajes.php";
$MensajeXDesglosar = "";

if ($NumError != 0)
{
    if ($NumError == 1)
        $MensajeXDesglosar = CFormateadorMensajes::FormatearMensajeError($MensajeOtroError);
    elseif ($NumError == 1001)
        $MensajeXDesglosar = CFormateadorMensajes::FormatearMensajeError("La contraseña actual es incorrecta.");
    elseif ($NumError == 2001)
        $MensajeXDesglosar = CFormateadorMensajes::FormatearMensajeError("La nueva contraseña no coincide con su confirmación.");
    elseif ($NumError == 3001)
        $MensajeXDesglosar = CFormateadorMensajes::FormatearMensajeError("La nueva contraseña debe tener al menos " . $LongitudMinimaContrasena . " caracteres.");
    elseif ($NumError == 3002)
        $MensajeXDesglosar = CFormateadorMensajes::FormatearMensajeError("La nueva contraseña debe estar conformada por al menos un caracter alfabético en mayúscula, un caracter alfabético en minúscula, un dígito decimal y un caracter especial entre " . $CaracteresEspeciales);
    else
        $MensajeXDesglosar = CFormateadorMensajes::FormatearMensajeError("No se manejó el error número " . $NumError . ".");
} // if ($NumError != 0)
elseif ($SeCambioContrasenaExitosamente)
    $MensajeXDesglosar = CFormateadorMensajes::FormatearMensajeOK("Se cambió la contraseña exitosamente.");

$MaximoTamanoCampoContrasena = CUsuarios::MAXIMO_TAMANO_CAMPO_CONTRASENA;
?>
<!DOCTYPE html>
<html>
<?php
include "encabezados.php";
?>
<body>
<?php
$FormularioActivo = "CambiarContrasena"; // Este es un parámetro que recibe "menuApp.php"
include "menuApp.php";
?>
<form method="post">
    <div class="container mt-4">
        <div class="form-row justify-content-center">
            <div class="form-group col-8 col-md-6 col-lg-4">
                <label for="ContrasenaActual">Contraseña Actual</label>
                <input type="password" class="form-control" id="ContrasenaActual" name="ContrasenaActual" placeholder="Ingrese su contraseña actual" maxlength="<?php echo $MaximoTamanoCampoContrasena;?>">
            </div>
        </div>
        <div class="form-row justify-content-center">
            <div class="form-group col-8 col-md-6 col-lg-4">
                <label for="NuevaContrasena">Nueva Contraseña</label>
                <input type="password" class="form-control" id="NuevaContrasena" name="NuevaContrasena" placeholder="Ingrese su nueva contraseña" maxlength="<?php echo $MaximoTamanoCampoContrasena;?>">
            </div>
        </div>
        <div class="form-row justify-content-center">
            <div class="form-group col-8 col-md-6 col-lg-4">
                <label for="ConfirmarNuevaContrasena">Confirmación de Nueva Contraseña</label>
                <input type="password" class="form-control" id="ConfirmarNuevaContrasena" name="ConfirmarNuevaContrasena" placeholder="Confirme su nueva contraseña" maxlength="<?php echo $MaximoTamanoCampoContrasena;?>">
            </div>
        </div>
        <div class="form-row justify-content-center">
            <div class="form-group col-8 col-md-6 col-lg-4">
                <button type="submit" class="btn btn-primary">Enviar</button>
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

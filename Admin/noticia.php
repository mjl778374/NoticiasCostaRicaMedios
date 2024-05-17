<?php
include_once "ValidarIngresoApp.php"; // Aquí dentro se hace el redireccionamiento a la página de ingreso, en caso de fallar la validación
// La anterior debe ser la primera instrucción por ejecutar en el archivo web

$FechaPublicacion = "";
$Titulo = "";
$Resumen = "";
$URLRelativa = "";
$IdUsuarioAutor = 0;

if (isset($_POST["FechaPublicacion"]))
{
    $SePretendeGuardarInformacion = true;
    $FechaPublicacion = $_POST["FechaPublicacion"];
    $Titulo = $_POST["Titulo"];
    $Resumen = $_POST["Resumen"];
    $URLRelativa = $_POST["URLRelativa"];    
    $IdUsuarioAutor = $_POST["IdUsuarioAutor"];    
} // if (isset($_POST["FechaPublicacion"]))

try
{
    include_once "ValidarParametroModo.php";
    
    if (strcmp($Modo, $MODO_CAMBIO) == 0)
    {
        $URLFormulario = "noticia.php";
        $NombreCampoId = "IdNoticia";
        // Los anteriores dos son parámetros que recibe "ValidarParametroId.php"
        include_once "ValidarParametroId.php";
        $IdNoticia = $ValorCampoId; // Este es un dato que se obtiene en "ValidarParametroId.php"
    } // if (strcmp($Modo, $MODO_CAMBIO) == 0)
} // try
catch (Exception $e)
{
    $NumError = 1;
    $MensajeOtroError = $e->getMessage();
} // catch (Exception $e)

// A continuación el código fuente de la implementación
try
{
    include_once "CUsuarios.php";
    $Usuarios = new CUsuarios();
    $ListadoUsuarios = $Usuarios->DemeTodosUsuarios();

    $ObjNoticia = NULL;
    include_once "CNoticias.php";

    if ($NumError == 0 && $SePretendeGuardarInformacion)
    {
        $Noticias = new CNoticias();

        if (strcmp($Modo, $MODO_ALTA) == 0)
            $Noticias->AltaNoticia($FechaPublicacion, $Titulo, $Resumen, $URLRelativa, $IdUsuarioAutor, $NumError, $ObjNoticia);

        elseif (strcmp($Modo, $MODO_CAMBIO) == 0)
            $Noticias->CambioNoticia($IdNoticia, $FechaPublicacion, $Titulo, $Resumen, $URLRelativa, $IdUsuarioAutor, $NumError, $ObjNoticia);

        if ($NumError == 0)
            $SeGuardoInformacionExitosamente = true;
    } // if ($NumError == 0 && $SePretendeGuardarInformacion)
    
    if ($NumError == 0 && !$SePretendeGuardarInformacion)
    {
        include_once "CFechasHoras.php";
        $FechasHoras = new CFechasHoras();
        $FechaPublicacion = $FechasHoras->DemeFechaHoy($FORMATO_FECHAS_SQL);
    } // if ($NumError == 0 && !$SePretendeGuardarInformacion)
    
    if ($NumError == 0 && strcmp($Modo, $MODO_CAMBIO) == 0)
    {
        $Noticias = new CNoticias();
        $Noticias->ConsultarXNoticia($IdNoticia, $Existe, $ObjNoticia);

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

if ($ObjNoticia != NULL)
{
    $IdNoticia = $ObjNoticia->DemeIdNoticia();
    $FechaPublicacion = $ObjNoticia->DemeFechaPublicacion();
    $Titulo = $ObjNoticia->DemeTitulo();
    $Resumen = $ObjNoticia->DemeResumen();
    $URLRelativa = $ObjNoticia->DemeURLRelativa();
    $IdUsuarioAutor = $ObjNoticia->DemeIdUsuarioAutor();
} // if ($ObjNoticia != NULL)

include "interfaz/noticia.php";
?>

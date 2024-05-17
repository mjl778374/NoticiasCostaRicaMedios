<?php
$ValidarUsuarioSesionEsAdmin = 1; // Este es un parámetro que recibe "ValidarIngresoApp.php"
include_once "ValidarIngresoApp.php"; // Aquí dentro se hace el redireccionamiento a la página de ingreso, en caso de fallar la validación
// La anterior debe ser la primera instrucción por ejecutar en el archivo web

$SeDebeIndexarTodo = false;
$MensajesXDesglosar = [];
$MENSAJE_OK = 1;
$MENSAJE_ERROR = 2;

$IdMensajeSeVanIndexarTodosUsuarios = 1;
$IdMensajeSeIndexaronTodosUsuarios = 2;

$IdMensajeSeVanIndexarTodasNoticias = 3;
$IdMensajeSeIndexaronTodasNoticias = 4;

$IdMensajeSeIndexoTodoExitosamente = 0;

if (isset($_POST["IndexarTodo"]))
{
    $SeDebeIndexarTodo = true;
} // if (isset($_POST["IndexarTodo"]))

// A continuación el código fuente de la implementación
try
{
    if ($SeDebeIndexarTodo)
    {
        $MensajesXDesglosar[] = array($MENSAJE_OK, $IdMensajeSeVanIndexarTodosUsuarios);
        include_once "CUsuarios.php";
        $Usuarios = new CUsuarios();
        $Usuarios->IndexarTodo();
        $MensajesXDesglosar[] = array($MENSAJE_OK, $IdMensajeSeIndexaronTodosUsuarios);

        $MensajesXDesglosar[] = array($MENSAJE_OK, $IdMensajeSeVanIndexarTodasNoticias);
        include_once "CNoticias.php";
        $Noticias = new CNoticias();
        $Noticias->IndexarTodo();
        $MensajesXDesglosar[] = array($MENSAJE_OK, $IdMensajeSeIndexaronTodasNoticias);

        $MensajesXDesglosar[] = array($MENSAJE_OK, $IdMensajeSeIndexoTodoExitosamente);
    } // if ($SeDebeIndexarTodo)
} // try
catch (Exception $e)
{
    $MensajesXDesglosar[] = array($MENSAJE_ERROR, $e->getMessage());
} // catch (Exception $e)
// El anterior fue el código fuente de la implementación

include "interfaz/indexarTodo.php";
?>

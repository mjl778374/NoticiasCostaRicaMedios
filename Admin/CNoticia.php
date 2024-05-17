<?php
class CNoticia
{    
    public const URL_CARPETA_NOTICIAS_SERVIDOR = "https://costaricamedios.cr";
    
    private $IdNoticia = NULL;
    private $FechaPublicacion = NULL;
    private $Titulo = NULL;
    private $Resumen = NULL;
    private $URLRelativa = NULL;    
    private $IdUsuarioAutor = NULL;        
    private $UsuarioAutor = NULL;            
    private $UsuarioModificoRegistro = NULL;
    private $FechaHoraModificacionRegistro = NULL;
    private static $VistaDatos = NULL;    

    function __construct($IdNoticia, $FechaPublicacion, $Titulo, $Resumen, $URLRelativa, $IdUsuarioAutor, $UsuarioAutor, $NombreUsuarioAutor, $UsuarioModificoRegistro, $FechaHoraModificacionRegistro)
    {
        $this->IdNoticia = $IdNoticia;
        $this->FechaPublicacion = $FechaPublicacion;
        $this->Titulo = $Titulo;
        $this->Resumen = $Resumen;
        $this->URLRelativa = $URLRelativa; 
        $this->IdUsuarioAutor = $IdUsuarioAutor;
        $this->UsuarioAutor = $UsuarioAutor;        
        $this->NombreUsuarioAutor = $NombreUsuarioAutor;                
        $this->UsuarioModificoRegistro = $UsuarioModificoRegistro;
        $this->FechaHoraModificacionRegistro = $FechaHoraModificacionRegistro;
    } // function __construct($IdNoticia, $FechaPublicacion, $Titulo, $Resumen, $URLRelativa, $IdUsuarioAutor, $UsuarioAutor, $NombreUsuarioAutor, $UsuarioModificoRegistro, $FechaHoraModificacionRegistro)

    public static function FijarVistaDatos($VistaDatos)
    {
        self::$VistaDatos = $VistaDatos;
    } //  public static function FijarVistaDatos($VistaDatos)

    public function DemeIdNoticia()
    {
        return $this->IdNoticia;
    } // public function DemeIdNoticia()

    public function DemeFechaPublicacion()
    {
        return $this->FechaPublicacion;
    } // public function DemeFechaPublicacion()

    public function DemeTitulo()
    {
        return $this->Titulo;
    } // public function DemeTitulo()

    public function DemeResumen()
    {
        return $this->Resumen;
    } // public function DemeResumen()

    public function DemeURLRelativa()
    {
        return $this->URLRelativa;
    } // public function DemeURLRelativa()

    public function DemeURLAbsoluta()
    {
        $URL = self::URL_CARPETA_NOTICIAS_SERVIDOR . "/" . $this->DemeURLRelativa();
        return $URL;
    } // public function DemeURLAbsoluta()
  
    public function DemeIdUsuarioAutor()
    {
        return $this->IdUsuarioAutor;
    } // public function DemeIdUsuarioAutor()

    public function DemeUsuarioAutor()
    {
        return $this->UsuarioAutor;
    } // public function DemeUsuarioAutor()

    public function DemeNombreUsuarioAutor()
    {
        return $this->NombreUsuarioAutor;
    } // public function DemeNombreUsuarioAutor()

    public function DemeUsuarioModificoRegistro()
    {
        return $this->UsuarioModificoRegistro;
    } // public function DemeUsuarioModificoRegistro()

    public function DemeFechaModificacionRegistro()
    {
        return $this->FechaModificacionRegistro;
    } // public function DemeFechaModificacionRegistro()
    
    public static function DemeTitulares()
    {
        if (self::$VistaDatos == 1)
            return array("Título", "Resumen", "URL Relativa", "Fecha de Publicación");

        else if (self::$VistaDatos == 2)
            return array("Título", "Resumen", "Fecha de Publicación", "Autor");
    } // public static function DemeTitulares()
    
    public function DemeArregloDatos()
    {
        include "constantesApp.php";
        
        if (self::$VistaDatos == 1)
            return array("noticia.php?Modo=" . $MODO_CAMBIO . "&IdNoticia=" . $this->DemeIdNoticia(), $this->DemeTitulo(), $this->DemeResumen(), $this->DemeURLRelativa(), $this->DemeFechaPublicacion());

        else if (self::$VistaDatos == 2)
        {
            include_once "interfaz/FuncionesUtiles.php";
            $URLAbsoluta = FormatearTextoJS($this->DemeURLAbsoluta());

            return array("javascript:AbrirURLAbsoluta('" . $URLAbsoluta . "')", $this->DemeTitulo(), $this->DemeResumen(), $this->DemeFechaPublicacion(), $this->DemeIdUsuarioAutor() . " - " . $this->DemeUsuarioAutor() . " - " . $this->DemeNombreUsuarioAutor());
        } // else if (self::$VistaDatos == 2)
    } // public function DemeArregloDatos()
} // class CNoticia
?>

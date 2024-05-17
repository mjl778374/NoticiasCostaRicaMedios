<?php
class CComentario
{    
    public const URL_CARPETA_NOTICIAS_SERVIDOR = "https://costaricamedios.cr";
    
    private $IdNoticia = NULL;
    private $Consecutivo = NULL;
    private $FechaHoraEnvio = NULL;
    private $Comentario = NULL;
    private $NombreComentarista = NULL;        
    private $EmailComentarista = NULL;            
    private $IdEstatus = NULL;        
    private static $VistaDatos = NULL;    

    function __construct($IdNoticia, $ConsecutivoComentario, $FechaHoraEnvio, $Comentario, $NombreComentarista, $EmailComentarista, $IdEstatus)
    {
        $this->IdNoticia = $IdNoticia;
        $this->Consecutivo = $ConsecutivoComentario;        
        $this->FechaHoraEnvio = $FechaHoraEnvio;
        $this->Comentario = $Comentario;
        $this->NombreComentarista = $NombreComentarista; 
        $this->EmailComentarista = $EmailComentarista;
        $this->IdEstatus = $IdEstatus;        
    } // function __construct($IdNoticia, $ConsecutivoComentario, $FechaHoraEnvio, $Comentario, $NombreComentarista, $EmailComentarista, $IdEstatus)

    public static function FijarVistaDatos($VistaDatos)
    {
        self::$VistaDatos = $VistaDatos;
    } //  public static function FijarVistaDatos($VistaDatos)

    public function DemeIdNoticia()
    {
        return $this->IdNoticia;
    } // public function DemeIdNoticia()

    public function DemeConsecutivo()
    {
        return $this->Consecutivo;
    } // public function DemeConsecutivo()

    public function DemeFechaHoraEnvio()
    {
        return $this->FechaHoraEnvio;
    } // public function DemeFechaHoraEnvio()

    public function DemeComentario()
    {
        return $this->Comentario;
    } // public function DemeComentario()

    public function DemeNombreComentarista()
    {
        return $this->NombreComentarista;
    } // public function DemeNombreComentarista()

    public function DemeEmailComentarista()
    {
        return $this->EmailComentarista;
    } // public function DemeEmailComentarista()

    public function DemeEstatus()
    {
        return $this->IdEstatus;
    } // public function DemeEstatus()
  
    public function DemeDescripcionEstatus()
    {
        include_once "CEstatusComentarios.php";
        $EstatusComentarios = new CEstatusComentarios();
        return $EstatusComentarios->DemeDescripcionEstatus($this->IdEstatus);
    } // public function DemeDescripcionEstatus()
    
    public static function DemeTitulares()
    {
        if (self::$VistaDatos == 1)
            return array("Fecha y Hora de Envío", "Comentario", "Estado");
    } // public static function DemeTitulares()
    
    public function DemeArregloDatos()
    {
        include "constantesApp.php";
        
        if (self::$VistaDatos == 1)
            return array("aprobarComentario.php?IdNoticia=" . $this->DemeIdNoticia() . "&Consecutivo=" . $this->DemeConsecutivo(), $this->DemeFechaHoraEnvio(), $this->DemeComentario(), $this->DemeDescripcionEstatus());
    } // public function DemeArregloDatos()
} // class CComentario
?>

<?php
include_once "CSQL.php";

class CPalabras extends CSQL
{
    public const SEPARADOR_PALABRAS = ' ';

    function __construct()
    {
        parent::__construct();
    } // function __construct()

    public static function DemeCaracteresValidos()
    {
        return "abcdefghijklmnñopqrstuvwxyzáéíóúü0123456789";
    } // public static function DemeCaracteresValidos()

    public function DemeSiguientePalabra($HileraXAnalizar, $IndiceActual, &$NuevoIndice)
    {
        include "constantesApp.php";
        $SiguientePalabra = "";
        $Consulta = "CALL DemeSiguientePalabra(?, ?, ?, @SiguientePalabra, @NuevoIndice, ?, 1);";
        $ConsultaEjecutadaExitosamente = $this->EjecutarConsulta($Consulta, 'ssii', array($HileraXAnalizar, self::DemeCaracteresValidos(), $IndiceActual, $TAMANO_MAXIMO_PALABRAS));

        if ($ConsultaEjecutadaExitosamente)
        {
            $ResultadoConsulta = $this->DemeSiguienteResultadoConsulta();

            if ($ResultadoConsulta != NULL)
            {
                $SiguientePalabra = $ResultadoConsulta[0];
                $NuevoIndice = $ResultadoConsulta[1];
            } // if ($ResultadoConsulta != NULL)

            $this->LiberarConjuntoResultados();
        } // if ($ConsultaEjecutadaExitosamente)

        return $SiguientePalabra;
    } // public function DemeSiguientePalabra($HileraXAnalizar, &$Indice)

    function __destruct()
    {
        parent::__destruct();
    } // function __destruct()
} // class CPalabras
?>

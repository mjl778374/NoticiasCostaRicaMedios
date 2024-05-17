<?php
include_once "CSQL.php";
include_once "CComentario.php";

class CComentarios extends CSQL
{
    function __construct()
    {
        parent::__construct();
    } // function __construct()

    public function ConsultarXComentario($IdNoticia, $ConsecutivoComentario, &$Existe, &$ObjComentario)
    {
        include "constantesApp.php";

        $Consulta = "SELECT a.IdNoticia, a.Consecutivo, DATE_FORMAT(a.FechaHoraEnvio, ?), a.Comentario, c.Estatus";
        $Consulta = $Consulta . " FROM ComentariosNoticias a, EstatusComentariosNoticias b, HistoricoEstatusComentariosNoticias c";
        $Consulta = $Consulta . " WHERE a.IdNoticia = b.IdNoticia";
        $Consulta = $Consulta . " AND a.Consecutivo = b.ConsecutivoComentario";        
        $Consulta = $Consulta . " AND b.IdNoticia = c.IdNoticia";
        $Consulta = $Consulta . " AND b.ConsecutivoComentario = c.ConsecutivoComentario";        
        $Consulta = $Consulta . " AND b.ConsecutivoEstatus = c.ConsecutivoEstatus";         
        $Consulta = $Consulta . " AND a.IdNoticia = ?";
        $Consulta = $Consulta . " AND a.Consecutivo = ?";        

        $ConsultaEjecutadaExitosamente = $this->EjecutarConsulta($Consulta, "sii", array($FORMATO_FECHAS_HORAS_DESGLOSE, $IdNoticia, $ConsecutivoComentario));
                    
        $Existe = false;
        $ObjNoticia = NULL;

        if ($ConsultaEjecutadaExitosamente)
        {
            $ResultadoConsulta = $this->DemeSiguienteResultadoConsulta();

            if ($ResultadoConsulta != NULL)
            {
                $Existe = true;
                $IdNoticia = $ResultadoConsulta[0];
                $ConsecutivoComentario = $ResultadoConsulta[1];
                $FechaHoraEnvio = $ResultadoConsulta[2];
                $Comentario = $ResultadoConsulta[3];
                $Estatus = $ResultadoConsulta[4];
                $ObjComentario = new CComentario($IdNoticia, $ConsecutivoComentario, $FechaHoraEnvio, $Comentario, "", "", $Estatus);
            } // if ($ResultadoConsulta != NULL)

            $this->LiberarConjuntoResultados();
        } // if ($ConsultaEjecutadaExitosamente)
    } // public function ConsultarXComentario($IdNoticia, $ConsecutivoComentario, &$Existe, &$ObjComentario)

    public function DemeTodosComentarios($Fecha, $Estatus)
    {
        include "constantesApp.php";        
        
        include_once "CEstatusComentarios.php";    
        $EstatusComentarios = new CEstatusComentarios();
        $EsEstatusValido = $EstatusComentarios->EsEstatusValido($Estatus);
    
        $Consulta = "SELECT a.IdNoticia, a.Consecutivo, DATE_FORMAT(a.FechaHoraEnvio, ?), a.Comentario, c.Estatus";
        $Consulta = $Consulta . " FROM ComentariosNoticias a, EstatusComentariosNoticias b, HistoricoEstatusComentariosNoticias c";
        $Consulta = $Consulta . " WHERE a.IdNoticia = b.IdNoticia";
        $Consulta = $Consulta . " AND a.Consecutivo = b.ConsecutivoComentario";        
        $Consulta = $Consulta . " AND b.IdNoticia = c.IdNoticia";
        $Consulta = $Consulta . " AND b.ConsecutivoComentario = c.ConsecutivoComentario";        
        $Consulta = $Consulta . " AND b.ConsecutivoEstatus = c.ConsecutivoEstatus";         
        $Consulta = $Consulta . " AND DATE_FORMAT(a.FechaHoraEnvio, ?) = ?";        
        
        $TiposParametros = "sss";
        $ArregloParametros = array($FORMATO_FECHAS_HORAS_DESGLOSE, $FORMATO_FECHAS_SQL, $Fecha);
        
        if ($EsEstatusValido)
        {
            $TiposParametros = $TiposParametros . "i";
            $ArregloParametros[] = $Estatus;
            $Consulta = $Consulta . " AND c.Estatus = ?";
        } // if ($EsEstatusValido)
        
        $Consulta = $Consulta . " ORDER BY a.FechaHoraEnvio ASC";
                
        $ConsultaEjecutadaExitosamente = $this->EjecutarConsulta($Consulta, $TiposParametros, $ArregloParametros);
        $Retorno = [];

        if ($ConsultaEjecutadaExitosamente)
        {
            $ResultadoConsulta = $this->DemeSiguienteResultadoConsulta();

            while ($ResultadoConsulta != NULL)
            {
                $IdNoticia = $ResultadoConsulta[0];
                $ConsecutivoComentario = $ResultadoConsulta[1];
                $FechaHoraEnvio = $ResultadoConsulta[2];
                $Comentario = $ResultadoConsulta[3];
                $Estatus = $ResultadoConsulta[4];
                $ObjComentario = new CComentario($IdNoticia, $ConsecutivoComentario, $FechaHoraEnvio, $Comentario, "", "", $Estatus);
                $Retorno[] = $ObjComentario;
                $ResultadoConsulta = $this->DemeSiguienteResultadoConsulta();
            } // while ($ResultadoConsulta != NULL)

            $this->LiberarConjuntoResultados();
        } // if ($ConsultaEjecutadaExitosamente)

        return $Retorno;
    } // public function DemeTodosComentarios($Fecha, $Estatus)

    public function CambioEstatusComentario($IdNoticia, $ConsecutivoComentario, $Estatus, &$NumError)
    {
        include "constantesApp.php";    
        include_once "CSession.php";        
        include_once "CEstatusComentarios.php";    
        
        $NumError = 0;
        
        $EstatusComentarios = new CEstatusComentarios();
        $EsEstatusValido = $EstatusComentarios->EsEstatusValido($Estatus);

        if (!$EsEstatusValido)
            $NumError = 3;
            
        if ($NumError == 0)
        {
            $Consulta = "CALL CambioEstatusComentarioNoticia(?, ?, ?, ?, 1);";
echo "Estatus: " . $Estatus;
            $ConsultaEjecutadaExitosamente = $this->EjecutarConsulta($Consulta, 'iiii', array($IdNoticia, $ConsecutivoComentario, CSession::DemeIdUsuarioSesion(), $Estatus));

            if ($ConsultaEjecutadaExitosamente)
            {
                $ResultadoConsulta = $this->DemeSiguienteResultadoConsulta();

                if ($ResultadoConsulta != NULL)
                    $NumError = $ResultadoConsulta[0];

                $this->LiberarConjuntoResultados();
            } // if ($ConsultaEjecutadaExitosamente)
        } // if ($NumError == 0)
    } // public function CambioEstatusComentario($IdNoticia, $ConsecutivoComentario, $Estatus, &$NumError)

    function __destruct()
    {
        parent::__destruct();
    } // function __destruct()
} // class CComentarios extends CSQLs
?>

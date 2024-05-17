<?php
include_once "CSQL.php";
include_once "CNoticia.php";

class CNoticias extends CSQL
{
    public const MAXIMO_TAMANO_CAMPO_TITULO = 100;
    public const MAXIMO_TAMANO_CAMPO_RESUMEN = 200;
    public const MAXIMO_TAMANO_CAMPO_URL_RELATIVA = 100;

    function __construct()
    {
        parent::__construct();
    } // function __construct()

    public function ConsultarXNoticia($IdNoticia, &$Existe, &$ObjNoticia)
    {
        include "constantesApp.php";
            
        $Consulta = "SELECT DATE_FORMAT(b.FechaPublicacionNoticia, ?), b.Titulo, b.Resumen, b.URLRelativaNoticia, b.IdUsuarioAutor FROM NoticiasActivas a, HistoricoNoticias b WHERE a.IdNoticia = b.IdNoticia AND a.Consecutivo = b.Consecutivo AND a.IdNoticia = ?";
        $ConsultaEjecutadaExitosamente = $this->EjecutarConsulta($Consulta, 'si', array($FORMATO_FECHAS_SQL, $IdNoticia));
        $Existe = false;
        $ObjNoticia = NULL;

        if ($ConsultaEjecutadaExitosamente)
        {
            $ResultadoConsulta = $this->DemeSiguienteResultadoConsulta();

            if ($ResultadoConsulta != NULL)
            {
                $Existe = true;
                $FechaPublicacion = $ResultadoConsulta[0];
                $Titulo = $ResultadoConsulta[1];
                $Resumen = $ResultadoConsulta[2];
                $URLRelativa = $ResultadoConsulta[3];
                $IdUsuarioAutor = $ResultadoConsulta[4];
                $ObjNoticia = new CNoticia($IdNoticia, $FechaPublicacion, $Titulo, $Resumen, $URLRelativa, $IdUsuarioAutor, "", "", "", "");
            } // if ($ResultadoConsulta != NULL)

            $this->LiberarConjuntoResultados();
        } // if ($ConsultaEjecutadaExitosamente)
    } // public function ConsultarXNoticia($IdNoticia, &$Existe, &$ObjNoticia)

    private function DemePalabrasMasParecidas($PalabrasBusqueda)
    {
        include_once "CPalabrasSemejantes.php";

        $PalabrasSemejantes = new CPalabrasSemejantes();
        $Retorno = $PalabrasSemejantes->DemePalabrasMasParecidas($PalabrasBusqueda, "PalabrasXNoticiaActiva");

        return $Retorno;
    } // private function DemePalabrasMasParecidas($PalabrasBusqueda)

    public function ConsultarXTodasNoticias($PalabrasBusqueda)
    {
        include "constantesApp.php";
            
        $Retorno = [];
        $PalabrasMasParecidas = $this->DemePalabrasMasParecidas($PalabrasBusqueda);

        $Consulta = "SELECT COUNT(1) as NumAciertos, a.IdNoticia, DATE_FORMAT(b.FechaPublicacionNoticia, ?), b.Titulo, b.Resumen, b.URLRelativaNoticia";
        $Consulta = $Consulta . " FROM NoticiasActivas a, HistoricoNoticias b, PalabrasXNoticiaActiva c";
        $Consulta = $Consulta . " WHERE a.IdNoticia = b.IdNoticia";
        $Consulta = $Consulta . " AND a.Consecutivo = b.Consecutivo";
        $Consulta = $Consulta . " AND a.IdNoticia = c.IdNoticia";        
        $Consulta = $Consulta . " AND c.IdPalabra IN (";
        $Consulta = $Consulta . "     SELECT d.IdPalabra";
        $Consulta = $Consulta . "     FROM Palabras d";
        $Consulta = $Consulta . "     WHERE (1 = 0";

        $TiposParametros = "";
        $ArregloParametros = [];
        
        $TiposParametros = $TiposParametros . "s";        
        $ArregloParametros[] = $FORMATO_FECHAS_DESGLOSE;

        for($i = 0; $i < count($PalabrasMasParecidas); $i++)
        {
            $ArregloParametros[] = $PalabrasMasParecidas[$i];
            $TiposParametros = $TiposParametros . "i";
            $Consulta = $Consulta . " OR d.IdPalabraSemejante = ?";
        } // for($i = 0; $i < count($PalabrasMasParecidas); $i++)

        $Consulta = $Consulta . ")";
        $Consulta = $Consulta . ")";
        $Consulta = $Consulta . " GROUP BY a.IdNoticia, b.FechaPublicacionNoticia, b.Titulo, b.Resumen, b.URLRelativaNoticia";
        $Consulta = $Consulta . " ORDER BY NumAciertos DESC, b.Titulo ASC, b.FechaPublicacionNoticia ASC";

        $ConsultaEjecutadaExitosamente = $this->EjecutarConsulta($Consulta, $TiposParametros, $ArregloParametros);

        if ($ConsultaEjecutadaExitosamente)
        {
            $ResultadoConsulta = $this->DemeSiguienteResultadoConsulta();

            while ($ResultadoConsulta != NULL)
            {
                $IdNoticia = $ResultadoConsulta[1];
                $FechaPublicacion = $ResultadoConsulta[2];
                $Titulo = $ResultadoConsulta[3];
                $Resumen = $ResultadoConsulta[4];
                $URLRelativa = $ResultadoConsulta[5];
                $ObjNoticia = new CNoticia($IdNoticia, $FechaPublicacion, $Titulo, $Resumen, $URLRelativa, "", "", "", "", "");
                $Retorno[] = $ObjNoticia;
                $ResultadoConsulta = $this->DemeSiguienteResultadoConsulta();
            } // while ($ResultadoConsulta != NULL)
        } // if ($ConsultaEjecutadaExitosamente)

        return $Retorno;
    } // public function ConsultarXTodasNoticias($PalabrasBusqueda)

    public function ConsultarXTodasNoticias2($PalabrasBusqueda)
    {
        include "constantesApp.php";
        include_once "CPalabras.php";
        include_once "CPalabrasSemejantes.php";        
            
        $Retorno = [];

        $Consulta = "SELECT COUNT(1) as NumAciertos, a.IdNoticia, DATE_FORMAT(b.FechaPublicacionNoticia, ?), b.Titulo, b.Resumen, b.URLRelativaNoticia, f.IdUsuario, f.Usuario, f.Nombre";
        $Consulta = $Consulta . " FROM NoticiasActivas a, HistoricoNoticias b, PalabrasXNoticiaActiva c, Palabras d, PalabrasSemejantes e, Usuarios f";
        $Consulta = $Consulta . " WHERE a.IdNoticia = b.IdNoticia";
        $Consulta = $Consulta . " AND a.Consecutivo = b.Consecutivo";
        $Consulta = $Consulta . " AND a.IdNoticia = c.IdNoticia";        
        $Consulta = $Consulta . " AND c.IdPalabra = d.IdPalabra";                
        $Consulta = $Consulta . " AND d.IdPalabraSemejante = e.IdPalabraSemejante";                        
        $Consulta = $Consulta . " AND b.IdUsuarioAutor = f.IdUsuario";        
        $Consulta = $Consulta . " AND (1 = 0";                                

        $TiposParametros = "";
        $ArregloParametros = [];

        $TiposParametros = $TiposParametros . "s";        
        $ArregloParametros[] = $FORMATO_FECHAS_DESGLOSE;

        $NuevoIndice = 0;
        $Palabras = new CPalabras();
        $SiguientePalabra = $Palabras->DemeSiguientePalabra($PalabrasBusqueda, 1, $NuevoIndice);

        while (strlen($SiguientePalabra) > 0)
        {
            $PalabrasSemejantes = new CPalabrasSemejantes();
            $PalabraSemejante = $PalabrasSemejantes->DemePalabraSemejante($SiguientePalabra);
            $Palabras = new CPalabras();
            $SiguientePalabra = $Palabras->DemeSiguientePalabra($PalabrasBusqueda, $NuevoIndice, $NuevoIndice);
        
            $Consulta = $Consulta . " OR e.PalabraSemejante like ?";                                                    
            $TiposParametros = $TiposParametros . "s";        
            $ArregloParametros[] = $PalabraSemejante . "%";
        } // while (strlen($SiguientePalabra) > 0)

        $Consulta = $Consulta . " )";                                
        $Consulta = $Consulta . " GROUP BY a.IdNoticia, b.FechaPublicacionNoticia, b.Titulo, b.Resumen, b.URLRelativaNoticia, f.IdUsuario, f.Usuario, f.Nombre";
        $Consulta = $Consulta . " ORDER BY NumAciertos DESC, b.FechaPublicacionNoticia DESC, b.Titulo ASC";

        $ConsultaEjecutadaExitosamente = $this->EjecutarConsulta($Consulta, $TiposParametros, $ArregloParametros);

        if ($ConsultaEjecutadaExitosamente)
        {
            $ResultadoConsulta = $this->DemeSiguienteResultadoConsulta();

            while ($ResultadoConsulta != NULL)
            {
                $IdNoticia = $ResultadoConsulta[1];
                $FechaPublicacion = $ResultadoConsulta[2];
                $Titulo = $ResultadoConsulta[3];
                $Resumen = $ResultadoConsulta[4];
                $URLRelativa = $ResultadoConsulta[5];
                $IdUsuarioAutor = $ResultadoConsulta[6];                 
                $UsuarioAutor = $ResultadoConsulta[7];                
                $NombreUsuarioAutor = $ResultadoConsulta[8];                                
                $ObjNoticia = new CNoticia($IdNoticia, $FechaPublicacion, $Titulo, $Resumen, $URLRelativa, $IdUsuarioAutor, $UsuarioAutor, $NombreUsuarioAutor, "", "");
                $Retorno[] = $ObjNoticia;
                $ResultadoConsulta = $this->DemeSiguienteResultadoConsulta();
            } // while ($ResultadoConsulta != NULL)
        } // if ($ConsultaEjecutadaExitosamente)

        return $Retorno;
    } // public function ConsultarXTodasNoticias2($PalabrasBusqueda)

    public function AltaNoticia($FechaPublicacion, $Titulo, $Resumen, $URLRelativa, $IdUsuarioAutor, &$NumError, &$ObjNoticia)
    {
        include "constantesApp.php";
        include_once "CSession.php"; 
        include_once "CPalabras.php";
        include_once "CPalabrasSemejantes.php";

        $Consulta = "CALL AltaNoticia(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1);";

        $ConsultaEjecutadaExitosamente = $this->EjecutarConsulta($Consulta, 'ssssiisssssi', array($FechaPublicacion, $Titulo, $Resumen, $URLRelativa, $IdUsuarioAutor, CSession::DemeIdUsuarioSesion(), CPalabras::DemeCaracteresValidos(), CPalabrasSemejantes::DemeTuplasReemplazo(), CPalabrasSemejantes::SEPARADOR_TUPLAS, CPalabrasSemejantes::SEPARADOR_COLUMNAS, CPalabras::SEPARADOR_PALABRAS, $TAMANO_MAXIMO_PALABRAS));

        $ObjNoticia = NULL;

        if ($ConsultaEjecutadaExitosamente)
        {
            $ResultadoConsulta = $this->DemeSiguienteResultadoConsulta();

            if ($ResultadoConsulta != NULL)
            {
                $NumError = $ResultadoConsulta[0];
                $IdNoticia = $ResultadoConsulta[1];
                $ObjNoticia = new CNoticia($IdNoticia, $FechaPublicacion, $Titulo, $Resumen, $URLRelativa, $IdUsuarioAutor, "", "", "", "");
            } // if ($ResultadoConsulta != NULL)

            $this->LiberarConjuntoResultados();
        } // if ($ConsultaEjecutadaExitosamente)
    } // public function AltaNoticia($FechaPublicacion, $Titulo, $Resumen, $URLRelativa, $IdUsuarioAutor, &$NumError, &$ObjNoticia)

    public function CambioNoticia($IdNoticia, $FechaPublicacion, $Titulo, $Resumen, $URLRelativa, $IdUsuarioAutor, &$NumError, &$ObjNoticia)
    {
        include "constantesApp.php";    
        include_once "CSession.php";        
        include_once "CPalabras.php";
        include_once "CPalabrasSemejantes.php";

        $Consulta = "CALL CambioNoticia(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1);";

        $ConsultaEjecutadaExitosamente = $this->EjecutarConsulta($Consulta, 'issssiisssssi', array($IdNoticia, $FechaPublicacion, $Titulo, $Resumen, $URLRelativa, $IdUsuarioAutor, CSession::DemeIdUsuarioSesion(), CPalabras::DemeCaracteresValidos(), CPalabrasSemejantes::DemeTuplasReemplazo(), CPalabrasSemejantes::SEPARADOR_TUPLAS, CPalabrasSemejantes::SEPARADOR_COLUMNAS, CPalabras::SEPARADOR_PALABRAS, $TAMANO_MAXIMO_PALABRAS));

        $ObjNoticia = NULL;

        if ($ConsultaEjecutadaExitosamente)
        {
            $ResultadoConsulta = $this->DemeSiguienteResultadoConsulta();

            if ($ResultadoConsulta != NULL)
                $NumError = $ResultadoConsulta[0];

            $ObjNoticia = new CNoticia($IdNoticia, $FechaPublicacion, $Titulo, $Resumen, $URLRelativa, $IdUsuarioAutor, "", "", "", "");
            $this->LiberarConjuntoResultados();
        } // if ($ConsultaEjecutadaExitosamente)
    } // public function CambioNoticia($IdNoticia, $FechaPublicacion, $Titulo, $Resumen, $URLRelativa, $IdUsuarioAutor, &$NumError, &$ObjNoticia)

    public function IndexarTodo()
    {
        include "constantesApp.php";
        include_once "CPalabras.php";
        include_once "CPalabrasSemejantes.php";
        $Consulta = "CALL IndexarTodasNoticias(?, ?, ?, ?, ?, ?, 0);";
        $this->EjecutarConsulta($Consulta, 'sssssi', array(CPalabras::DemeCaracteresValidos(), CPalabrasSemejantes::DemeTuplasReemplazo(), CPalabrasSemejantes::SEPARADOR_TUPLAS, CPalabrasSemejantes::SEPARADOR_COLUMNAS, CPalabras::SEPARADOR_PALABRAS, $TAMANO_MAXIMO_PALABRAS));
    } // public function IndexarTodo()

    function __destruct()
    {
        parent::__destruct();
    } // function __destruct()
} // class CNoticias extends CSQL
?>

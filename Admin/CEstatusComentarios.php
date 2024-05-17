<?php
class CEstatusComentarios
{
    public const ID_ESTATUS_PENDIENTE = 1;
    public const ID_ESTATUS_APROBADO = 2;
    public const ID_ESTATUS_REPROBADO = 3;
            
    public function DemeDescripcionEstatus($IdEstatus)
    {
        if ($IdEstatus == self::ID_ESTATUS_PENDIENTE)
            return "Pendiente";
        else if ($IdEstatus == self::ID_ESTATUS_APROBADO)
            return "Aprobado";
        else if ($IdEstatus == self::ID_ESTATUS_REPROBADO)
            return "Reprobado";
        else
            return "Inválido";        
    } // public function DemeDescripcionEstatus($IdEstatus)

    public function DemeTodosEstatus()
    {
        $Retorno = [];
        $Retorno[] = array(self::ID_ESTATUS_PENDIENTE, $this->DemeDescripcionEstatus(self::ID_ESTATUS_PENDIENTE));
        $Retorno[] = array(self::ID_ESTATUS_APROBADO, $this->DemeDescripcionEstatus(self::ID_ESTATUS_APROBADO));
        $Retorno[] = array(self::ID_ESTATUS_REPROBADO, $this->DemeDescripcionEstatus(self::ID_ESTATUS_REPROBADO));

        return $Retorno;
    } // public function DemeTodosEstatus()
    
    public function EsEstatusValido($IdEstatus)
    {
        $EstatusValidos = array(self::ID_ESTATUS_PENDIENTE, self::ID_ESTATUS_APROBADO, self::ID_ESTATUS_REPROBADO);
        return in_array($IdEstatus, $EstatusValidos);
    } // public function EsEstatusValido($IdEstatus)
} // class CEstatusComentarios
?>

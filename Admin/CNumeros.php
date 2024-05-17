<?php
class CNumeros
{
    public static function EsNumero($Texto)
    {
        return is_numeric($Texto);
    } // public static function EsNumero($Texto)

    public static function EsNumeroEntero($Texto)
    {
        if (!self::EsNumero($Texto))
            return false;
        elseif (ceil($Texto) != floor($Texto))
            return false;
        else
            return true;
    } // public static function EsNumeroEntero($Texto
} // class CNumeros
?>

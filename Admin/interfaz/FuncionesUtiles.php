<?php
    function FormatearTextoJS($TextoXFormatear)
    {
        $TextoXFormatear = str_replace("\\", "\\\\", $TextoXFormatear);
        $TextoXFormatear = str_replace("'", "\'", $TextoXFormatear);
        $TextoXFormatear = str_replace('"', '\"', $TextoXFormatear);
        $TextoXFormatear = str_replace(chr(10), ' ', $TextoXFormatear);
        $TextoXFormatear = str_replace(chr(13), ' ', $TextoXFormatear);
        return $TextoXFormatear;
    } // function FormatearTextoJS()

    function FormatearTextoURL($TextoXFormatear)
    {
        $TextoXFormatear = str_replace("'", "%27", $TextoXFormatear);
        $TextoXFormatear = str_replace('"', '%22', $TextoXFormatear);
        return $TextoXFormatear;
    } // function FormatearTextoURL($TextoXFormatear)
?>

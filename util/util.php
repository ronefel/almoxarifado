<?php
header ('Content-type: text/html; charset=UTF-8',true);
class util {

    public static function dateToBR($dataUS) {
        if ($dataUS) {
            $d = explode("-", $dataUS);
            return $d[2] . "/" . $d[1] . "/" . $d[0];
        }
    }

    public static function dateToUS($dataBR) {
        if ($dataBR) {
            $d = explode("/", $dataBR);
            return $d[2] . "-" . $d[1] . "-" . $d[0];
        }
    }

    public static function colocaBarra($string) {

        $s = str_replace("\"", "\\\"", $string);
        return str_replace("'", "&#39;", $s);
    }

    public static function tiraBarra($string) {

        $s = str_replace("\\\"", "\"", $string);
        return str_replace("&#39;", "'", $s);
    }

    public static function tiraAspas($string) {

        return htmlentities($string, ENT_QUOTES,'UTF-8');
    }

    public static function dataValida($dat) {
        $data = explode("/", "$dat");
        $d = $data[0];
        $m = $data[1];
        $y = $data[2];

        return checkdate($m, $d, $y);
    }

    public static function getData($dias = 0) {

        date_default_timezone_set('America/Manaus');

        $dia = date('d') + $dias;
        $mes = date('m');
        $ano = date('Y');
        $data = date("d/m/Y", mktime(0, 0, 0, $mes, $dia, $ano));

        return $data;
    }

    public static function getTime() {

        return mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"));
    }

    public static function gerarPDF($relatorio, $titulo, $pagina="A4") {

        require_once "../report/mPDF/mpdf60/mpdf.php";

        $time = util::getTime();

        //(charset, formato, '', '', left,right,top,bottom,header,footer)
        $mpdf = new mPDF('UTF-8', $pagina, '', '', 10, 8, 28, 10, 5, 3);
        $mpdf->useOnlyCoreFonts = true;    // false is default
        $mpdf->SetProtection(array('print'));
        $mpdf->SetTitle($titulo);
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->WriteHTML($relatorio);
        $mpdf->Output("../report/tmp/{$time}.pdf", "F");
        $mpdf->displayDefaultOrientation = true;

        if (file_exists("../report/tmp/{$time}.pdf")) {

            return "sucesso={$time}.pdf";
        } else {
            
            return "erro=Ouve um erro na geração do relatório.";
        }
    }
    
    /**
     * Retorna o diretório root do sistema
     * 
     * @return string
     */
    public static function getRoot(){
        
        /**
         * Sempre começar com barra se o sistema estiver dentro de outro
         * Caso contrario use somente a barra
         */
        return "/almoxarifado";
    }

}

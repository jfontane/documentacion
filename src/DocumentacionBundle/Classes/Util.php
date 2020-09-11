<?php

namespace Jubilaciones\DeclaracionesBundle\Classes;

/**
 * Util
 *
 * @author Javier Fontanellaz
 */
class Util {

    /**
     * Devuelve el slug de la cadena de texto que se le pasa.
     * Código copiado del método urlize() de Doctrine 1.
     *
     * @param string $archivo ruta al archivo importa.dat
     * @return string Slug calculado para la cadena original
     */
    public static function totaliza($archivo) {
        $totalRemunerativo = $totalNoRemunerativo = $totalApPersonal = $totalApPatronal = 0;
        $totalApIAPOS = $totalApIAPOSsolidario = $totalAdicional = $totalComputoPrivilegio = 0;
        $totalRecCPriv = $totalRecSer = $totalDispPol = $totalPasividad = $totalLicEnf = 0;
        $totalLicSinSueldo = $totalLicMayor30Dias = $totalInasisSusp = $totalMultasTardanzas = 0;
        $totalOrgDeficit = $totalTareasRiesgoza = $totalOtrosAp = $totalUnifAportes = $totalCompDif = 0;
        $importesOtros=0;
        $cantidad_empleados = 0;
        $lineas = count($archivo);
        $primerLinea = substr($archivo[0], 0, 255);
        $codigoOrgArch = substr($archivo[0], 1, 10);
        $periodoOrgArch = substr($archivo[0], 30, 6);
        $tipoLiqOrgArch = substr($archivo[0], 82, 3);
        //echo "$codigoOrg**$codigoOrgArch**$periodoOrg**$periodoOrgArch**$tipoLiq**$tipoLiqOrgArch";
        for ($i = 1; $i < $lineas; $i++) {
            $totalRemunerativo = $totalRemunerativo + (float) (self::convierte(substr($archivo[$i], 106, 11)));
            $totalNoRemunerativo = $totalNoRemunerativo + (float) (self::convierte(substr($archivo[$i], 117, 11)));
            $totalApIAPOS = $totalApIAPOS + (float) (self::convierte(substr($archivo[$i], 139, 11)));
            $totalApIAPOSsolidario = $totalApIAPOSsolidario + (float) (self::convierte(substr($archivo[$i], 150, 11)));

            $totalApPersonal = $totalApPersonal + (float) (self::convierte(substr($archivo[$i], 161, 11)));
            $totalApPatronal = $totalApPatronal + (float) (self::convierte(substr($archivo[$i], 172, 11)));
            //Aca va el otros


            $totalAdicional = $totalAdicional + (float) (self::convierte(substr($archivo[$i], 183, 11)));
            $totalComputoPrivilegio = $totalComputoPrivilegio + (float) (self::convierte(substr($archivo[$i], 194, 8)));
            $totalRecCPriv = $totalRecCPriv + (float) (self::convierte(substr($archivo[$i], 202, 8)));
            $totalRecSer = $totalRecSer + (float) (self::convierte(substr($archivo[$i], 210, 8)));
            $totalDispPol = $totalDispPol + (float) (self::convierte(substr($archivo[$i], 218, 8)));
            $totalPasividad = $totalPasividad + (float) (self::convierte(substr($archivo[$i], 226, 8)));
            $totalLicEnf = $totalLicEnf + (float) (self::convierte(substr($archivo[$i], 234, 8)));
            $totalLicSinSueldo = $totalLicSinSueldo + (float) (self::convierte(substr($archivo[$i], 242, 8)));
            $totalLicMayor30Dias = $totalLicMayor30Dias + (float) (self::convierte(substr($archivo[$i], 250, 8)));
            $totalInasisSusp = $totalInasisSusp + (float) (self::convierte(substr($archivo[$i], 258, 8)));
            $totalMultasTardanzas = $totalMultasTardanzas + (float) (self::convierte(substr($archivo[$i], 266, 8)));
            $totalOrgDeficit = $totalOrgDeficit + (float) (self::convierte(substr($archivo[$i], 274, 8)));
            $totalTareasRiesgoza = $totalTareasRiesgoza + (float) (self::convierte(substr($archivo[$i], 282, 8)));
            $totalOtrosAp = $totalOtrosAp + (float) (self::convierte(substr($archivo[$i], 290, 8)));
            $totalUnifAportes = $totalUnifAportes + (float) (self::convierte(substr($archivo[$i], 311, 8)));
            $totalCompDif = $totalCompDif + (float) (self::convierte(substr($archivo[$i], 320, 8)));
        }; // end for
        $cantidad_empleados = $i - 1;
        $importesOtros=$totalAdicional+$totalComputoPrivilegio+$totalRecCPriv+$totalRecSer+
                       $totalDispPol+$totalPasividad+$totalLicEnf+$totalLicSinSueldo+
                       $totalLicMayor30Dias+$totalInasisSusp+$totalMultasTardanzas+$totalOrgDeficit+
                       $totalTareasRiesgoza+$totalOtrosAp+$totalUnifAportes+$totalCompDif;
        return Array('totalRemunerativo' => $totalRemunerativo, 'totalNoRemunerativo' => $totalNoRemunerativo, 'totalApPersonal' => $totalApPersonal,
            'totalApPatronal' => $totalApPatronal, 'totalApIAPOS' => $totalApIAPOS, 'totalApIAPOSsolidario' => $totalApIAPOSsolidario,
            'totalAdicional' => $totalAdicional, 'totalComputoPrivilegio' => $totalComputoPrivilegio, 'totalRecCPriv' => $totalRecCPriv,
            'totalRecSer' => $totalRecSer, 'totalDispPol' => $totalDispPol, 'totalPasividad' => $totalPasividad, 'totalLicEnf' => $totalLicEnf,
            'totalLicSinSueldo' => $totalLicSinSueldo, 'totalLicMayor30Dias' => $totalLicMayor30Dias, 'totalInasisSusp' => $totalInasisSusp, 'totalLicEnf' => $totalLicEnf,
            'totalMultasTardanzas' => $totalMultasTardanzas, 'totalOrgDeficit' => $totalOrgDeficit, 'totalTareasRiesgoza' => $totalTareasRiesgoza, 'totalOtrosAp' => $totalOtrosAp,
            'totalUnifAportes' => $totalCompDif, 'cantidad_empleados' => $cantidad_empleados,
            'totalImportesOtros' => $importesOtros);
    }

    private static function convierte($valor) {
        $parteDecimalPrimerDigito = substr($valor, strlen($valor) - 2, 1);
        $parteDecimalUltimoDigito = substr($valor, strlen($valor) - 1, 1);
        switch ($parteDecimalUltimoDigito) {
            case '{': $ultimoDigito = 0;
                $signo = '';
                break;
            case 'A': $ultimoDigito = 1;
                $signo = '';
                break;
            case 'B': $ultimoDigito = 2;
                $signo = '';
                break;
            case 'C': $ultimoDigito = 3;
                $signo = '';
                break;
            case 'D': $ultimoDigito = 4;
                $signo = '';
                break;
            case 'E': $ultimoDigito = 5;
                $signo = '';
                break;
            case 'F': $ultimoDigito = 6;
                $signo = '';
                break;
            case 'G': $ultimoDigito = 7;
                $signo = '';
                break;
            case 'H': $ultimoDigito = 8;
                $signo = '';
                break;
            case 'I': $ultimoDigito = 9;
                $signo = '';
                break;
            case '0': $ultimoDigito = 0;
                $signo = '';
                break;
            case '1': $ultimoDigito = 1;
                $signo = '';
                break;
            case '2': $ultimoDigito = 2;
                $signo = '';
                break;
            case '3': $ultimoDigito = 3;
                $signo = '';
                break;
            case '4': $ultimoDigito = 4;
                $signo = '';
                break;
            case '5': $ultimoDigito = 5;
                $signo = '';
                break;
            case '6': $ultimoDigito = 6;
                $signo = '';
                break;
            case '7': $ultimoDigito = 7;
                $signo = '';
                break;
            case '8': $ultimoDigito = 8;
                $signo = '';
                break;
            case '9': $ultimoDigito = 9;
                $signo = '';
                break;
            case '}': $ultimoDigito = 0;
                $signo = '-';
                break;
            case 'J': $ultimoDigito = 1;
                $signo = '-';
                break;
            case 'K': $ultimoDigito = 2;
                $signo = '-';
                break;
            case 'L': $ultimoDigito = 3;
                $signo = '-';
                break;
            case 'M': $ultimoDigito = 4;
                $signo = '-';
                break;
            case 'N': $ultimoDigito = 5;
                $signo = '-';
                break;
            case 'O': $ultimoDigito = 6;
                $signo = '-';
                break;
            case 'P': $ultimoDigito = 7;
                $signo = '-';
                break;
            case 'Q': $ultimoDigito = 8;
                $signo = '-';
                break;
            case 'R': $ultimoDigito = 9;
                $signo = '-';
                break;
        };
        $parteEntera = substr($valor, 0, strlen($valor) - 2);
        return $signo . $parteEntera . '.' . $parteDecimalPrimerDigito . $ultimoDigito;
    }

    public function getTipoLiquidacion($periodoMes, $tipoLiquidacion) {
        $tipoLiq = 0;
        if (($periodoMes == '13') or ( $periodoMes == '14')) {
            if ($tipoLiquidacion == '1')
                $tipoLiq = '211';
            else if ($tipoLiquidacion == '2')
                $tipoLiq = '212';
            else if ($tipoLiquidacion == '3')
                $tipoLiq = '301';
            else if ($tipoLiquidacion == '4')
                $tipoLiq = '302';
            else if ($tipoLiquidacion == '5')
                $tipoLiq = '303';
            else if ($tipoLiquidacion == '6')
                $tipoLiq = '304';
        } else if (( $periodoMes == '01' ) or
                   ( $periodoMes == '02' ) or
                   ( $periodoMes == '03' ) or
                   ( $periodoMes == '04' ) or
                   ( $periodoMes == '05' ) or
                   ( $periodoMes == '06' ) or
                   ( $periodoMes == '07' ) or
                   ( $periodoMes == '08' ) or
                   ( $periodoMes == '09' ) or
                   ( $periodoMes == '10' ) or
                   ( $periodoMes == '11' ) or
                   ( $periodoMes == '12' )) {
            if ($tipoLiquidacion == '1')
                $tipoLiq = '111';
            else if ($tipoLiquidacion == '2')
                $tipoLiq = '112';
            else if ($tipoLiquidacion == '3')
                $tipoLiq = '301';
            else if ($tipoLiquidacion == '4')
                $tipoLiq = '302';
            else if ($tipoLiquidacion == '5')
                $tipoLiq = '303';
            else if ($tipoLiquidacion == '6')
                $tipoLiq = '304';
        }
        return $tipoLiq;
    }

    public static function verificarEncabezadoJubiDat($org,$anio,$mes,$tliq,$encabezado) {
        $str_encabezado_organismo = substr($encabezado,1,10).'-'.substr($encabezado,30,4).'-'.substr($encabezado,34,2).'-'.substr($encabezado,82,3);
        $str_encabezado_formulario = $org.'-'.$anio.'-'.$mes.'-'.$tliq;
        //dump($str_encabezado_organismo.'***'.$str_encabezado_formulario);die;
        if ($str_encabezado_organismo==$str_encabezado_formulario) return true;
        else return false;




    }

}

<?php

namespace Jubilaciones\DeclaracionesBundle\Classes;

use Jubilaciones\DeclaracionesBundle\Entity\Declaracionjurada;

//use FPDI;
//use setasign\Fpdi\Fpdi as FPDI;
//class ReciboPDF extends FPDI	{
class OrganismoDeclaracionListarPdf extends \TCPDF {

    private $ruta;

    public function setRuta($ruta) {
        $this->ruta = $ruta;
    }

    public function getRuta() {
        return $this->ruta;
    }

    /**
     * include a background template for every page
     */
    function Header() {
        // set bacground image
        $img_file = $this->getRuta() . '/logo.png';
        $this->Image($img_file, 10, 12, 190, 16, '', '', '', false, 300, '', false, false, 0);
    }

    function __construct($ruta, $orientation = 'P', $unit = 'mm', $format = 'A4', $unicode = true, $encoding = 'UTF-8', $diskcache = false, $pdfa = false) {
        parent::__construct($orientation, $unit, $format, $unicode, $encoding, $diskcache, $pdfa);

        $this->setCreator(PDF_CREATOR);
        $this->setAuthor('Caja de Jubilaciones y Pensiones de la Provincia de Santa Fe');
        $this->setSubject('Declaraciones Juradas');
        $this->setKeywords('');
        $this->setAutoPageBreak(0); // cantidad de paginas del listado
        $this->setRuta($ruta);
    }

    public function render($declaraciones) {
        $mes = 12;
        $this->setTitle("Listado Declaraciones Juradas");
        /* TODO: Una vez que este todo listo, contemplar el caso de que haya mas
          concetos que filas */

        $this->setMargins(10, 10, 10, 10);
        $this->addPage();

        $this->setFontSize(12);
        $this->SetFont('helvetica', '');

        //Seccion renderizado de los conceptos
        $primerFilaY = 30.65;
        $altoCeldaEncabezado = 4.60;
        $pseudoMargen = 12;
        //Periodo Liq


        $cantidad_ddjj = count($declaraciones);
        //$this->SetFont('Arial', 'I', 8);
        //dump($cantidad_ddjj);
        $this->SetY(37);
        $this->setFontSize(12);
        $this->SetFont('helvetica', 'B');
        $this->cell(190, $altoCeldaEncabezado + 0.16, "LISTADO DE DECLARACIONES JURADAS ENTREGADAS", 0, 1, 'C');
        $this->cell(190, $altoCeldaEncabezado + 0.16, "", 0, 1, 'C');
        $this->cell(30, $altoCeldaEncabezado + 0.16, "Periodo", 1, 0, 'C');
        $this->cell(40, $altoCeldaEncabezado + 0.16, "T.Liquidacion", 1, 0, 'C');
        $this->cell(40, $altoCeldaEncabezado + 0.16, "F.Entrega", 1, 0, 'C');
        $this->cell(40, $altoCeldaEncabezado + 0.16, "F.Dictaminacion", 1, 0, 'C');
        $this->cell(40, $altoCeldaEncabezado + 0.16, "Resultado", 1, 1, 'C');
        $this->setFontSize(10);
        $this->SetFont('helvetica', '');
        for ($i = 0; $i < $cantidad_ddjj; ++$i) {
            $dmyFE = $declaraciones[$i]->getFechaEntrega()->format('Y-m-d');
            $dmyFD = $declaraciones[$i]->getFechaIngreso() != null ? $declaraciones[$i]->getFechaIngreso()->format('Y-m-d') : "";

            $this->cell(30, $altoCeldaEncabezado + 0.16, $declaraciones[$i]->getPeriodoAnio() . "/" . $declaraciones[$i]->getPeriodoMes(), 1, 0, 'C');
            $this->cell(40, $altoCeldaEncabezado + 0.16, $declaraciones[$i]->getTipoLiquidacion(), 1, 0, 'C');
            $this->cell(40, $altoCeldaEncabezado + 0.16, $dmyFE, 1, 0, 'C');
            $this->cell(40, $altoCeldaEncabezado + 0.16, $dmyFD, 1, 0, 'C');
            $this->cell(40, $altoCeldaEncabezado + 0.16, $declaraciones[$i]->getEstado(), 1, 1, 'C');
            if (($i > 0) && ($i % 45) == 0) {
                $this->setMargins(10, 10, 10, 10);
                $this->AddPage();
                $this->SetY(37);
                $this->setFontSize(12);
                $this->SetFont('helvetica', 'B');
                $this->cell(190, $altoCeldaEncabezado + 0.16, "LISTADO DE DECLARACIONES JURADAS ENTREGADAS", 0, 1, 'C');
                $this->cell(190, $altoCeldaEncabezado + 0.16, "", 0, 1, 'C');
                $this->cell(30, $altoCeldaEncabezado + 0.16, "Periodo", 1, 0, 'C');
                $this->cell(40, $altoCeldaEncabezado + 0.16, "T.Liquidacion", 1, 0, 'C');
                $this->cell(40, $altoCeldaEncabezado + 0.16, "F.Entrega", 1, 0, 'C');
                $this->cell(40, $altoCeldaEncabezado + 0.16, "F.Dictaminacion", 1, 0, 'C');
                $this->cell(40, $altoCeldaEncabezado + 0.16, "Resultado", 1, 1, 'C');
                $this->setFontSize(10);
                $this->SetFont('helvetica', '');
            }
        }
        
    }

    function Footer() {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Page number
        $this->cell(0, 10, 'Pag. ' . $this->PageNo() . '/' . $this->getAliasNbPages(), 0, 0, 'C');
    }

}

<?php

namespace AppBundle\Service;

use AppBundle\Entity\Person;
use Doctrine\ORM\EntityManagerInterface;
use WhiteOctober\TCPDFBundle\Controller\TCPDFController;

/**
 * Class PortfolioPdfService.
 */
class PortfolioPdfService
{
    /** @var EntityManagerInterface $entityManager */
    protected $entityManager;
    /** @var TCPDFController $tcPdf */
    protected $tcPdf;

    /**
     * PortfolioPdfService constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param TCPDFController        $tcPdfController
     */
    public function __construct(EntityManagerInterface $entityManager, TCPDFController $tcPdfController)
    {
        $this->entityManager = $entityManager;
        $this->tcPdf = $tcPdfController;
    }

    /**
     * @param string $title
     *
     * @return \TCPDF
     */
    public function initPdf($title)
    {
        /** @var \TCPDF $pdf */
        $pdf = $this->tcPdf->create(
            'PORTRAIT',
            PDF_UNIT,
            PDF_PAGE_FORMAT,
            true,
            'UTF-8',
            false
        );
        $pdf->SetAuthor('ICT Scouts / Campus');
        $pdf->SetTitle($title);
        $pdf->SetKeywords('ICT Scouts, Talent, Portfolio');
        $pdf->setFontSubsetting(true);

        $pdf->SetFont('helvetica', '', 11, '', true);
        $pdf->AddPage();

        return $pdf;
    }

    /**
     * @param \TCPDF $pdf
     * @param Person $person
     *
     * @return \TCPDF
     */
    public function createPortfolio(\TCPDF $pdf, Person $person)
    {
        $html = '<h1>ICT Scouts / Campus</h1><h2>'.$person->getGivenName().' '.$person->getFamilyName().'</h2>';

        $html .= '<table><tr><th><strong>Modul</strong></th><th><strong>Datum</strong></th><th><strong>Kommentar</strong></th></tr>';

        /* ToDo: Get Talent Portfolio */
        $html .= '<tr><td>Scratch - Tutorial</td><td>28.02.2017</td><td>-</td></tr><tr><td>Scratch - Spiel</td><td>28.02.2017</td><td>-</td></tr><tr><td>Scratch - Fussball</td><td>28.02.2017</td><td>-</td></tr><tr><td>HTML - Tutorial</td><td>01.04.2017</td><td>-</td></tr><tr><td colspan="3" align="center">...</td></tr><tr><td colspan="3" align="center"><strong>Aktuell werden nur Demodaten ausgegeben.</strong></td></tr>';

        $html .= '</table>';

        $pdf->writeHTMLCell(
            $w = 0,
            $h = 0,
            $x = '',
            $y = '',
            $html,
            $border = 0,
            $ln = 1,
            $fill = 0,
            $reseth = true,
            $align = '',
            $autopadding = true
        );

        return $pdf;
    }
}

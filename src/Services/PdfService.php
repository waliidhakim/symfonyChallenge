<?php

namespace App\Services;

use Dompdf\Dompdf;
use Dompdf\Options;

class PdfService
{
    private $dompdf;
    public function __construct()
    {
        $this->dompdf = new Dompdf();

        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Garamond');

        $this->dompdf->setOptions($pdfOptions);
    }


    public function showPdfFile($html)
    {
        $this->dompdf->loadHtml($html);
        $this->dompdf->render();
        $this->dompdf->stream('details.pdf', [
            'Atachement' => false
        ]);
    }
    public function generateBinaryPDF($html)
    {
        $this->dompdf->loadHtml($html);
        $this->dompdf->render();
        $this->dompdf->output();
    }
}
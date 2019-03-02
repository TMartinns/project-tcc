<?php

namespace HXPHP\System\Services\Pdf;

use Dompdf\Dompdf;

class Pdf
{
    private $dompdf;

    public function __construct($size, $orientation = 'portrait')
    {
        $this->dompdf = new Dompdf();

        $this->dompdf->setPaper($size, $orientation);
    }

    public function byHtml($html, $filename = 'document.pdf', $attachment = false)
    {
        $this->dompdf->loadHtml($html);

        $this->dompdf->render();

        $this->dompdf->stream(
            $filename,
            array(
                'Attachment' => $attachment
            )
        );
    }
}
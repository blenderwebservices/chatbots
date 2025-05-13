<?php

namespace App\Actions;

use Illuminate\Support\Facades\Storage;
use Spatie\PdfToText\Pdf;

class ExtractTextFromPdfAction
{
    public function execute(string $pdfPath)
    {
        $fullPath = Storage::path($pdfPath);

        return Pdf::getText($fullPath);
    }
}

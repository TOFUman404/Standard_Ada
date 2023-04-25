<?php

require __DIR__ . '/vendor/autoload.php';
use mikehaertl\wkhtmlto\Pdf;

// You can pass a filename, a HTML string, an URL or an options array to the constructor
$pdf = new Pdf('AAA');

// On some systems you may have to set the path to the wkhtmltopdf executable
$pdf->binary = dirname(__FILE__)."\wkhtmltox\bin\wkhtmltopdf.exe";

if ($pdf->saveAs('../Test.pdf')) {
//  $error = $pdf->getError();
    echo "AAA";
}else{
    echo $pdf->getError();
}

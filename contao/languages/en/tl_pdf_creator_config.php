<?php

/*
 * Copyright (c) 2022 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

use HeimrichHannot\PdfCreator\AbstractPdfCreator;
use HeimrichHannot\PdfCreator\Concrete\DompdfCreator;
use HeimrichHannot\PdfCreator\Concrete\MpdfCreator;
use HeimrichHannot\PdfCreator\Concrete\TcpdfCreator;

$lang = &$GLOBALS['TL_LANG']['tl_pdf_creator_config'];

/*
 * Legends
 */
$lang['type_legend'] = 'Type settings';
$lang['file_legend'] = 'File settings';
$lang['page_legend'] = 'Page settings';

/*
 * Operations
 */
$lang['edit'] = ['Edit config with ID: %s', 'Edit config with ID: %s'];
$lang['copy'] = ['Copy config with ID: %s', 'Copy config with ID: %s'];
$lang['delete'] = ['Delete config with ID: %s', 'Delete config with ID: %s'];
$lang['show'] = ['Show config with ID: %s', 'Show config with ID: %s'];

/*
 * Fields
 */
$lang['title'] = ['Name', 'Set a name for the configuration.'];
$lang['type'] = [
    0 => 'PDF library',
    1 => 'Choose a pdf library to render the pdf files.',
    DompdfCreator::getType() => 'Dompdf',
    MpdfCreator::getType() => 'mPDF',
    TcpdfCreator::getType() => 'TCPDF',
];
$lang['filename'] = ['File name', 'Set a file name for the generated pdf files. You can use the %title% placeholder to use the title of the content to export in the file name.'];
$lang['orientation'] = [
    0 => 'Page orientation',
    1 => 'Choose a page orientation.',
    AbstractPdfCreator::ORIENTATION_LANDSCAPE => 'Landscape',
    AbstractPdfCreator::ORIENTATION_PORTRAIT => 'Portrait',
];
$lang['outputMode'] = [
    0 => 'Output mode',
    1 => 'Choose how to output the pdf.',
    AbstractPdfCreator::OUTPUT_MODE_DOWNLOAD => 'Download',
    AbstractPdfCreator::OUTPUT_MODE_FILE => 'File',
    AbstractPdfCreator::OUTPUT_MODE_INLINE => 'Inline',
    AbstractPdfCreator::OUTPUT_MODE_STRING => 'String',
];
$lang['format'] = ['Page format', 'Set a page format. This could be a standardized format like A3, A4, A5 or Legal, otherwise you can specify the format in millimeter (width x height, seperated by comma, for example 180,210).'];
$lang['fonts'] = [
    0 => 'Fonts',
    1 => 'Specify all fonts that you use in the pdf.',
    'filepath' => ['File path', 'Set the path to the font file relative to the project root.'],
    'family' => ['Font family', 'Set the font family name.'],
    'style' => ['Font style', 'Set the font style.'],
    'weight' => ['Font weight', 'Set the font weight.'],
];
$lang['pageMargins'] = ['Page margins', 'Set the page margins.'];
$lang['masterTemplate'] = ['PDF template', 'Choose a pdf template (master template), which will be the base template for the generated pdf files.'];
$lang['filePath'] = ['Storage folder', 'Choose a folder where rendered pdfs should be stored.'];

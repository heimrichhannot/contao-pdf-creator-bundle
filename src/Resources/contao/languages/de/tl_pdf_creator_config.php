<?php

/*
 * Copyright (c) 2021 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

$lang = &$GLOBALS['TL_LANG']['tl_pdf_creator_config'];

/*
 * Legends
 */
$lang['type_legend'] = 'Typ-Einstellungen';
$lang['file_legend'] = 'Datei-Einstellungen';
$lang['page_legend'] = 'Seiten-Einstellungen';

/*
 * Operations
 */
$lang['edit'] = ['Konfiguration mit ID: %s bearbeiten', 'Konfiguration mit ID: %s bearbeiten'];
$lang['copy'] = ['Konfiguration mit ID: %s kopieren', 'Konfiguration mit ID: %s kopieren'];
$lang['delete'] = ['Konfiguration mit ID: %s löschen', 'Konfiguration mit ID: %s löschen'];
$lang['show'] = ['Konfiguration mit ID: %s ansehen', 'Konfiguration mit ID: %s ansehen'];

/*
 * Fields
 */
$lang['title'] = ['Name', 'Geben Sie einen Namen für die Konfiguration ein.'];
$lang['type'] = [
    0 => 'PDF-Bibliothek',
    1 => 'Wählen Sie eine Bibliothek aus, welche zum Rendern der PDF-Dateien verwendet werden soll.',
    \HeimrichHannot\PdfCreator\Concrete\MpdfCreator::getType() => 'mPDF',
];
$lang['filename'] = ['Dateiname', 'Geben Sie einen Dateinamen für die zu erzeugenden PDF-Dateien an. Sie können den Platzhalter %title% verwenden, um den Titel des zu exportierenden Inhaltes im Dateinamen zu erhalten.'];
$lang['orientation'] = [
    0 => 'Seiten-Orientierung',
    1 => 'Wählen Sie die Seitenorientierung aus.',
    \HeimrichHannot\PdfCreator\AbstractPdfCreator::ORIENTATION_LANDSCAPE => 'Querformat',
    \HeimrichHannot\PdfCreator\AbstractPdfCreator::ORIENTATION_PORTRAIT => 'Hochformat',
];
$lang['outputMode'] = [
    0 => 'Ausgabemodus',
    1 => 'Geben Sie an, wie die PDF ausgegeben werden soll.',
    \HeimrichHannot\UtilsBundle\PdfCreator\AbstractPdfCreator::OUTPUT_MODE_DOWNLOAD => 'Download',
    \HeimrichHannot\UtilsBundle\PdfCreator\AbstractPdfCreator::OUTPUT_MODE_FILE => 'Datei',
    \HeimrichHannot\UtilsBundle\PdfCreator\AbstractPdfCreator::OUTPUT_MODE_INLINE => 'Inline',
    \HeimrichHannot\UtilsBundle\PdfCreator\AbstractPdfCreator::OUTPUT_MODE_STRING => 'String',
];
$lang['format'] = ['Seitenformat', 'Geben Sie das Seitenformat an. Dies kann ein standardisiertes Format wie A3,A4, A5 oder Legal sein oder eine Millimeter-Angabe (Breite x Höhe kommegetrennt, bspw. 180,210).'];
$lang['fonts'] = [
    0 => 'Schriftarten',
    1 => 'Geben Sie hier alle Schriftarten an, welche im Dokument verwendent werden.',
    'filepath' => ['Datei-Pfad', 'Geben Sie hier den Pfad zur Font-Datei relativ zu Projekt-Pfad an.'],
    'family' => ['Schrift-Familie', 'Geben Sie den Namen der Schriftfamilie (font-family) an.'],
    'style' => ['Schrift-Stil', 'Geben Sie hier den Schrift-Stil (font-style) der Font-Datei an.'],
    'weight' => ['Schrift-Stärke', 'Geben Sie hier die Stärke der Schrift (font-weight) an.'],
];
$lang['pageMargins'] = ['Seitenabstände', 'Geben Sie hier die Seitenabstände ein.'];
$lang['masterTemplate'] = ['PDF-Template', 'Wählen Sie ein PDF-Template (Master-Template), welches als Grundlage für die erzeugten PDF-Dateien genutzt werden soll.'];

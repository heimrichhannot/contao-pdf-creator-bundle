<?php

/*
 * Copyright (c) 2021 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

use Heimrichhannot\PdfCreatorBundle\DataContainer\PdfCreatorConfigContainer;
use HeimrichHannot\UtilsBundle\PdfCreator\Concrete\MpdfCreator;

$GLOBALS['TL_DCA']['tl_pdf_creator_config'] = [
    'config' => [
        'dataContainer' => 'Table',
        'enableVersioning' => true,
        'sql' => [
            'keys' => [
                'id' => 'primary',
            ],
        ],
        'onload_callback' => [
            [PdfCreatorConfigContainer::class, 'onLoadCallback'],
        ],
    ],
    'list' => [
        'sorting' => [
            'mode' => 2,
            'fields' => ['title'],
            'flag' => 1,
            'panelLayout' => 'filter;sort,search,limit',
        ],
        'label' => [
            'fields' => ['title'],
            'format' => '%s',
            'label_callback' => [PdfCreatorConfigContainer::class, 'onLabelCallback'],
        ],
        'global_operations' => [
            'all' => [
                'label' => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href' => 'act=select',
                'class' => 'header_edit_all',
                'attributes' => 'onclick="Backend.getScrollOffset()" accesskey="e"',
            ],
        ],
        'operations' => [
            'edit' => [
                'label' => &$GLOBALS['TL_LANG']['tl_pdf_creator_config']['edit'],
                'href' => 'act=edit',
                'icon' => 'edit.gif',
            ],
            'copy' => [
                'label' => &$GLOBALS['TL_LANG']['tl_pdf_creator_config']['copy'],
                'href' => 'act=copy',
                'icon' => 'copy.gif',
            ],
            'delete' => [
                'label' => &$GLOBALS['TL_LANG']['tl_pdf_creator_config']['delete'],
                'href' => 'act=delete',
                'icon' => 'delete.gif',
                'attributes' => 'onclick="if(!confirm(\''.$GLOBALS['TL_LANG']['MSC']['deleteConfirm'].'\'))return false;Backend.getScrollOffset()"',
            ],
            'show' => [
                'label' => &$GLOBALS['TL_LANG']['tl_pdf_creator_config']['show'],
                'href' => 'act=show',
                'icon' => 'show.gif',
                'attributes' => 'style="margin-right:3px"',
            ],
        ],
    ],
    'palettes' => [
        'default' => '{type_legend},title,type;{file_legend},filename,outputMode;{page_legend},orientation,format',
    ],
    'fields' => [
        'id' => [
            'sql' => 'int(10) unsigned NOT NULL auto_increment',
        ],
        'tstamp' => [
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],
        'title' => [
            'label' => &$GLOBALS['TL_LANG']['tl_pdf_creator_config']['title'],
            'inputType' => 'text',
            'exclude' => false,
            'search' => true,
            'filter' => false,
            'sorting' => true,
            'flag' => 1,
            'eval' => ['mandatory' => true, 'maxlength' => 255, 'tl_class' => 'w50'],
            'sql' => "varchar(255) NOT NULL default ''",
        ],
        'type' => [
            'label' => &$GLOBALS['TL_LANG']['tl_pdf_creator_config']['type'],
            'inputType' => 'select',
            'default' => MpdfCreator::getType(),
            'exclude' => false,
            'search' => false,
            'filter' => true,
            'sorting' => true,
            'reference' => $GLOBALS['TL_LANG']['tl_pdf_creator_config']['type'],
            'options_callback' => [PdfCreatorConfigContainer::class, 'onTypeOptionsCallback'],
            'eval' => [
                'includeBlankOption' => false,
                'tl_class' => 'w50',
                'submitOnChange' => true,
                'mandatory' => true,
            ],
            'sql' => "varchar(64) NOT NULL default ''",
        ],
        'filename' => [
            'label' => &$GLOBALS['TL_LANG']['tl_pdf_creator_config']['filename'],
            'inputType' => 'text',
            'default' => '%title%.pdf',
            'exclude' => false,
            'search' => false,
            'filter' => false,
            'sorting' => false,
            'eval' => ['mandatory' => true, 'maxlength' => 255, 'tl_class' => 'w50'],
            'sql' => "varchar(255) NOT NULL default ''",
        ],
        'orientation' => [
            'label' => &$GLOBALS['TL_LANG']['tl_pdf_creator_config']['orientation'],
            'inputType' => 'radio',
            'exclude' => false,
            'search' => false,
            'filter' => true,
            'sorting' => true,
            'options' => [
                \HeimrichHannot\PdfCreator\AbstractPdfCreator::ORIENTATION_PORTRAIT,
                \HeimrichHannot\PdfCreator\AbstractPdfCreator::ORIENTATION_LANDSCAPE,
            ],
            'reference' => $GLOBALS['TL_LANG']['tl_pdf_creator_config']['orientation'],
            'eval' => [
                'includeBlankOption' => false,
                'tl_class' => 'w50 clr',
                'mandatory' => true,
            ],
            'sql' => "varchar(16) NOT NULL default ''",
        ],
        'outputMode' => [
            'label' => &$GLOBALS['TL_LANG']['tl_pdf_creator_config']['outputMode'],
            'inputType' => 'select',
            'exclude' => false,
            'search' => false,
            'filter' => true,
            'sorting' => true,
            'reference' => $GLOBALS['TL_LANG']['tl_pdf_creator_config']['outputMode'],
            'options_callback' => [PdfCreatorConfigContainer::class, 'onOutputModeOptionsCallback'],
            'eval' => [
                'includeBlankOption' => false,
                'tl_class' => 'w50',
                'mandatory' => true,
            ],
            'sql' => "varchar(16) NOT NULL default ''",
        ],
        'format' => [
            'label' => &$GLOBALS['TL_LANG']['tl_pdf_creator_config']['format'],
            'inputType' => 'text',
            'default' => 'A4',
            'exclude' => false,
            'search' => true,
            'filter' => true,
            'sorting' => false,
            'eval' => ['mandatory' => true, 'maxlength' => 64, 'tl_class' => 'w50'],
            'sql' => "varchar(64) NOT NULL default ''",
        ],
        'fonts' => [
            'label' => &$GLOBALS['TL_LANG']['tl_pdf_creator_config']['fonts'],
            'inputType' => 'multiColumnEditor',
            'exclude' => false,
            'search' => false,
            'filter' => false,
            'sorting' => false,
            'eval' => [
                'tl_class' => 'clr',
                'multiColumnEditor' => [
                    'minRowCount' => 0,
                    'fields' => [
                        'filepath' => [
                            'label' => &$GLOBALS['TL_LANG']['tl_pdf_creator_config']['fonts']['filepath'],
                            'inputType' => 'text',
                            'eval' => ['mandatory' => true, 'groupStyle' => 'width:300px'],
                        ],
                        'family' => [
                            'label' => &$GLOBALS['TL_LANG']['tl_pdf_creator_config']['fonts']['family'],
                            'inputType' => 'text',
                            'eval' => ['mandatory' => true, 'groupStyle' => 'width:200px'],
                        ],
                        'style' => [
                            'label' => &$GLOBALS['TL_LANG']['tl_pdf_creator_config']['fonts']['style'],
                            'inputType' => 'text',
                            'eval' => ['mandatory' => true, 'groupStyle' => 'width:200px'],
                        ],
                        'weight' => [
                            'label' => &$GLOBALS['TL_LANG']['tl_pdf_creator_config']['fonts']['weight'],
                            'inputType' => 'text',
                            'eval' => ['mandatory' => true, 'groupStyle' => 'width:200px'],
                        ],
                    ],
                ],
            ],
            'sql' => 'blob NULL',
        ],
        'pageMargins' => [
            'label' => &$GLOBALS['TL_LANG']['tl_pdf_creator_config']['pageMargins'],
            'exclude' => false,
            'search' => false,
            'filter' => false,
            'sorting' => false,
            'inputType' => 'trbl',
            'default' => [
                'bottom' => '15',
                'left' => '15',
                'right' => '15',
                'top' => '15',
                'unit' => 'mm',
            ],
            'options' => [
                'mm',
            ],
            'eval' => ['includeBlankOption' => true, 'tl_class' => 'w50 clr'],
            'sql' => "varchar(128) NOT NULL default ''",
        ],
        'masterTemplate' => [
            'label' => &$GLOBALS['TL_LANG']['tl_pdf_creator_config']['masterTemplate'],
            'inputType' => 'fileTree',
            'exclude' => false,
            'search' => false,
            'filter' => false,
            'sorting' => false,
            'eval' => [
                'filesOnly' => true,
                'extensions' => 'pdf',
                'fieldType' => 'radio',
                'tl_class' => 'w50 clr',
            ],
            'sql' => 'binary(16) NULL',
        ],
    ],
];

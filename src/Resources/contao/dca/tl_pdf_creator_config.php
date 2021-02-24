<?php

/*
 * Copyright (c) 2021 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

$GLOBALS['TL_DCA']['tl_pdf_creator_config'] = [
    'config' => [
        'dataContainer' => 'Table',
        'enableVersioning' => true,
        'sql' => [
            'keys' => [
                'id' => 'primary',
            ],
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
        'default' => '{first_legend},title,type,filename,orientation,outputMode,format,fonts,pageMargins,masterTemplate',
    ],
    'fields' => [
        'id' => [
            'sql' => 'int(10) unsigned NOT NULL auto_increment',
        ],
        'tstamp' => [
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],
        'title' => [
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
            'inputType' => 'select',
            'exclude' => false,
            'search' => false,
            'filter' => true,
            'sorting' => true,
//            'reference' => $GLOBALS['TL_LANG']['tl_pdf_creator_config'],
            'options_callback' => [\Heimrichhannot\PdfCreatorBundle\DataContainer\PdfCreatorConfigContainer::class, 'onTypeOptionsCallback'],
            'eval' => [
                'includeBlankOption' => false,
                'tl_class' => 'w50',
                'submitOnChange' => true,
            ],
            'sql' => "varchar(64) NOT NULL default ''",
        ],
        'filename' => [
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
            'inputType' => 'select',
            'exclude' => false,
            'search' => false,
            'filter' => false,
            'sorting' => false,
            'options' => [
                \HeimrichHannot\PdfCreator\AbstractPdfCreator::ORIENTATION_PORTRAIT,
                \HeimrichHannot\PdfCreator\AbstractPdfCreator::ORIENTATION_LANDSCAPE,
            ],
//            'reference' => $GLOBALS['TL_LANG']['tl_pdf_creator_config'],
            'eval' => [
                'includeBlankOption' => false,
                'tl_class' => 'w50',
            ],
            'sql' => "varchar(16) NOT NULL default ''",
        ],
        'outputMode' => [
            'inputType' => 'select',
            'exclude' => false,
            'search' => false,
            'filter' => false,
            'sorting' => false,
//            'reference' => $GLOBALS['TL_LANG']['tl_pdf_creator_config'],
            'options_callback' => [\Heimrichhannot\PdfCreatorBundle\DataContainer\PdfCreatorConfigContainer::class, 'onOutputModeOptionsCallback'],
            'eval' => [
                'includeBlankOption' => false,
                'tl_class' => 'w50',
            ],
            'sql' => "varchar(16) NOT NULL default ''",
        ],
        'format' => [
            'inputType' => 'text',
            'default' => 'A4',
            'exclude' => false,
            'search' => false,
            'filter' => false,
            'sorting' => false,
            'eval' => ['mandatory' => true, 'maxlength' => 64, 'tl_class' => 'w50'],
            'sql' => "varchar(64) NOT NULL default ''",
        ],
        'fonts' => [
            'inputType' => 'multiColumnEditor',
            'eval' => [
                'multiColumnEditor' => [
                    'minRowCount' => 0,
                    'fields' => [
                        'filepath' => [
                            'inputType' => 'text',
                            'eval' => ['mandatory' => true],
                        ],
                        'family' => [
                            'inputType' => 'text',
                            'eval' => ['mandatory' => true],
                        ],
                        'style' => [
                            'inputType' => 'text',
                            'eval' => ['mandatory' => true],
                        ],
                        'weight' => [
                            'inputType' => 'text',
                            'eval' => ['mandatory' => true],
                        ],
                    ],
                ],
            ],
            'sql' => 'blob NULL',
        ],
        'pageMargins' => [
            'exclude' => true,
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
            'eval' => ['includeBlankOption' => true, 'tl_class' => 'w50'],
            'sql' => "varchar(128) NOT NULL default ''",
        ],
        'masterTemplate' => [
            'inputType' => 'fileTree',
            'exclude' => true,
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

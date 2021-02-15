<?php

/*
 * Copyright (c) 2021 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

use Contao\Backend;
use Contao\DC_Table;
use Contao\Input;

/*
 * Table tl_pdf_creator_config
 */
$GLOBALS['TL_DCA']['tl_pdf_creator_config'] = [
    // Config
    'config' => [
        'dataContainer' => 'Table',
        'enableVersioning' => true,
        'sql' => [
            'keys' => [
                'id' => 'primary',
            ],
        ],
    ],
    'edit' => [
        'buttons_callback' => [
            ['tl_pdf_creator_config', 'buttonsCallback'],
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
    // Palettes
    'palettes' => [
        '__selector__' => ['addSubpalette'],
        'default' => '{first_legend},title,type,filename,orientation,outputMode,format',
    ],
    // Subpalettes
    'subpalettes' => [
        'addSubpalette' => 'textareaField',
    ],
    // Fields
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
//
//        'selectField'    => array(
//            'inputType' => 'select',
//            'exclude'   => true,
//            'search'    => true,
//            'filter'    => true,
//            'sorting'   => true,
//            'reference' => $GLOBALS['TL_LANG']['tl_pdf_creator_config'],
//            'options'   => array('firstoption', 'secondoption'),
//            //'foreignKey'            => 'tl_user.name',
//            //'options_callback'      => array('CLASS', 'METHOD'),
//            'eval'      => array('includeBlankOption' => true, 'tl_class' => 'w50'),
//            'sql'       => "varchar(255) NOT NULL default ''",
//            //'relation'  => array('type' => 'hasOne', 'load' => 'lazy')
//        ),
//        'checkboxField'  => array(
//            'inputType' => 'select',
//            'exclude'   => true,
//            'search'    => true,
//            'filter'    => true,
//            'sorting'   => true,
//            'reference' => $GLOBALS['TL_LANG']['tl_pdf_creator_config'],
//            'options'   => array('firstoption', 'secondoption'),
//            //'foreignKey'            => 'tl_user.name',
//            //'options_callback'      => array('CLASS', 'METHOD'),
//            'eval'      => array('includeBlankOption' => true, 'chosen' => true, 'tl_class' => 'w50'),
//            'sql'       => "varchar(255) NOT NULL default ''",
//            //'relation'  => array('type' => 'hasOne', 'load' => 'lazy')
//        ),
//        'multitextField' => array(
//            'inputType' => 'text',
//            'exclude'   => true,
//            'search'    => true,
//            'filter'    => true,
//            'sorting'   => true,
//            'eval'      => array('multiple' => true, 'size' => 4, 'decodeEntities' => true, 'tl_class' => 'w50'),
//            'sql'       => "varchar(255) NOT NULL default ''"
//        ),
//        'addSubpalette'  => array(
//            'exclude'   => true,
//            'inputType' => 'checkbox',
//            'eval'      => array('submitOnChange' => true, 'tl_class' => 'w50 clr'),
//            'sql'       => "char(1) NOT NULL default ''"
//        ),
//        'textareaField'  => array(
//            'inputType' => 'textarea',
//            'exclude'   => true,
//            'search'    => true,
//            'filter'    => true,
//            'sorting'   => true,
//            'eval'      => array('rte' => 'tinyMCE', 'tl_class' => 'clr'),
//            'sql'       => 'text NOT NULL'
//        )
    ],
];

/**
 * Class tl_pdf_creator_config.
 */
class tl_pdf_creator_config extends Backend
{
    /**
     * @param $arrButtons
     *
     * @return mixed
     */
    public function buttonsCallback($arrButtons, DC_Table $dc)
    {
        if ('edit' === Input::get('act')) {
            $arrButtons['customButton'] = '<button type="submit" name="customButton" id="customButton" class="tl_submit customButton" accesskey="x">'.$GLOBALS['TL_LANG']['tl_pdf_creator_config']['customButton'].'</button>';
        }

        return $arrButtons;
    }
}

<?php

/*
 * Copyright (c) 2021 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace Heimrichhannot\PdfCreatorBundle\DataContainer;

use Contao\DC_Table;
use HeimrichHannot\PdfCreator\PdfCreatorFactory;
use Heimrichhannot\PdfCreatorBundle\Exception\PdfCreatorConfigurationNotFoundException;
use Heimrichhannot\PdfCreatorBundle\Model\PdfCreatorConfigModel;

class PdfCreatorConfigContainer
{
    public function onLabelCallback($row, $label, $dc, $args): array
    {
        $label .= ' <span style="color:#b3b3b3; padding-left:3px; display: inline;">['
            .($GLOBALS['TL_LANG']['tl_pdf_creator_config']['type'][$row['type']] ?: $row['type'])
            .', '.$row['format']
            .', '.($GLOBALS['TL_LANG']['tl_pdf_creator_config']['orientation'][$row['orientation']] ?: $row['orientation'])
            .', '.($GLOBALS['TL_LANG']['tl_pdf_creator_config']['outputMode'][$row['outputMode']] ?: $row['outputMode'])
            .']</span>';

        return [$label];
    }

    public function onTypeOptionsCallback($dc): array
    {
        return PdfCreatorFactory::getTypes();
    }

    /**
     * @param DC_Table $dc
     *
     * @throws PdfCreatorConfigurationNotFoundException
     */
    public function onOutputModeOptionsCallback($dc): array
    {
        if (!$dc || !($configuration = PdfCreatorConfigModel::findByPk($dc->id))) {
            return [];
        }

        $type = PdfCreatorFactory::createInstance($configuration->type);

        if (!$type) {
            return [];
        }

        return $type->getSupportedOutputModes();
    }
}

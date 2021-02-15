<?php

/*
 * Copyright (c) 2021 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace Heimrichhannot\ContaoPdfCreatorBundle\DataContainer;

use Contao\DC_Table;
use Heimrichhannot\ContaoPdfCreatorBundle\Exception\PdfCreatorConfigurationNotFoundException;
use Heimrichhannot\ContaoPdfCreatorBundle\Model\PdfCreatorConfigModel;
use HeimrichHannot\PdfCreator\PdfCreatorFactory;

class PdfCreatorConfigContainer
{
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

<?php

/*
 * Copyright (c) 2021 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace Heimrichhannot\PdfCreatorBundle\Generator;

use Contao\Model\Collection;
use Heimrichhannot\PdfCreatorBundle\Model\PdfCreatorConfigModel;

class PdfConfigOptionsGenerator
{
    public function getPdfCreatorConfigOptions(): array
    {
        $options = [];
        /** @var PdfCreatorConfigModel[]|Collection $configurations */
        $configurations = PdfCreatorConfigModel::findAll();

        if (!$configurations) {
            return $options;
        }

        foreach ($configurations as $configuration) {
            $options[$configuration->id] = $configuration->title;
        }

        return $options;
    }
}

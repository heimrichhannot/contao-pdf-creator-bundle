<?php

/*
 * Copyright (c) 2021 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace Heimrichhannot\PdfCreatorBundle\Generator;

use Symfony\Component\Translation\TranslatorInterface;

class DcaGenerator
{
    /**
     * @var TranslatorInterface
     */
    protected $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * Returns a dca field for select a pdf config.
     *
     * Options:
     * - label: (array) override the default label
     * - exclude: (bool) override exclude option. Default: true
     * - includeBlankOption: (bool) override includeBlankOption option. Default: false
     * - chosen: (bool) override chosen option. Default: true
     * - tl_class: (string) override tl_class option. Default: w50
     */
    public function addPdfCreatorConfigSelectField(array $options = []): array
    {
        if (!isset($options['label']) || !\is_array($options['label'])) {
            $label = [
                $this->translator->trans('huh.pdf_creator.fields.pdf_creator_config.name'),
                $this->translator->trans('huh.pdf_creator.fields.pdf_creator_config.description'),
            ];
        } else {
            $label = $options['label'];
        }

        $includeBlankOption = false;

        if (isset($options['includeBlankOption']) || \is_bool($options['includeBlankOption'])) {
            $includeBlankOption = $options['includeBlankOption'];
        }

        $choosen = true;

        if (isset($options['chosen']) || \is_bool($options['chosen'])) {
            $choosen = $options['chosen'];
        }

        $exclude = true;

        if (isset($options['exclude']) || \is_bool($options['exclude'])) {
            $exclude = $options['exclude'];
        }

        $tlClass = 'w50';

        if (isset($options['tl_class']) || \is_string($options['tl_class'])) {
            $tlClass = $options['tl_class'];
        }

        return [
            'label' => $label,
            'inputType' => 'select',
            'options_callback' => [PdfConfigOptionsGenerator::class, 'getPdfCreatorConfigOptions'],
            'exclude' => $exclude,
            'eval' => ['includeBlankOption' => $includeBlankOption, 'chosen' => $choosen, 'tl_class' => $tlClass],
            'sql' => 'int(10) unsigned NOT NULL default 0',
        ];
    }
}

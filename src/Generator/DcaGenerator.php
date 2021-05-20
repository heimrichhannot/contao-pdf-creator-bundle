<?php

/*
 * Copyright (c) 2021 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace Heimrichhannot\PdfCreatorBundle\Generator;

use Heimrichhannot\PdfCreatorBundle\DataContainer\PdfCreatorConfigContainer;
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
     * - tl_class: (string) override tl_class option. Default: 'w50 wizard'
     */
    public function getPdfCreatorConfigSelectFieldConfig(array $options = []): array
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

        $tlClass = 'w50 wizard';

        if (isset($options['tl_class']) || \is_string($options['tl_class'])) {
            $tlClass = $options['tl_class'];
        }

        return [
            'label' => $label,
            'inputType' => 'select',
            'options_callback' => [PdfCreatorConfigContainer::class, 'getPdfCreatorConfigOptions'],
            'exclude' => $exclude,
            'eval' => ['includeBlankOption' => $includeBlankOption, 'chosen' => $choosen, 'tl_class' => $tlClass, 'submitOnChange' => true],
            'wizard' => [[PdfCreatorConfigContainer::class, 'onPdfCreatorConfigWizardCallback']],
            'sql' => "varchar(32) NOT NULL default ''",
        ];
    }
}

<?php

/*
 * Copyright (c) 2022 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace Heimrichhannot\PdfCreatorBundle\Generator;

use Heimrichhannot\PdfCreatorBundle\DataContainer\PdfCreatorConfigContainer;
use Symfony\Contracts\Translation\TranslatorInterface;

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
        $options = array_merge([
            'includeBlankOption' => false,
            'chosen' => true,
            'exclude' => true,
            'tl_class' => 'w50 wizard',
            'label' => null,
        ], $options);

        if (!$options['label']) {
            $label = [
                $this->translator->trans('huh.pdf_creator.fields.pdf_creator_config.name'),
                $this->translator->trans('huh.pdf_creator.fields.pdf_creator_config.description'),
            ];
        }

        return [
            'label' => $label,
            'inputType' => 'select',
            'options_callback' => [PdfCreatorConfigContainer::class, 'getPdfCreatorConfigOptions'],
            'exclude' => $options['exclude'],
            'eval' => [
                'includeBlankOption' => (bool) $options['includeBlankOption'],
                'chosen' => (bool) $options['chosen'],
                'tl_class' => $options['tl_class'],
                'submitOnChange' => true,
            ],
            'wizard' => [[PdfCreatorConfigContainer::class, 'onPdfCreatorConfigWizardCallback']],
            'sql' => "varchar(32) NOT NULL default ''",
        ];
    }
}

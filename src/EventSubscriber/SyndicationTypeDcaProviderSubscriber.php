<?php

/*
 * Copyright (c) 2021 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace Heimrichhannot\PdfCreatorBundle\EventSubscriber;

use Contao\Controller;
use Heimrichhannot\PdfCreatorBundle\Generator\DcaGenerator;
use HeimrichHannot\SyndicationTypeBundle\Event\AddSyndicationTypeFieldsEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class SyndicationTypeDcaProviderSubscriber implements EventSubscriberInterface
{
    /**
     * @var DcaGenerator
     */
    protected $dcaGenerator;
    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * SyndicationTypeDcaProviderSubscriber constructor.
     */
    public function __construct(DcaGenerator $dcaGenerator, TranslatorInterface $translator)
    {
        $this->dcaGenerator = $dcaGenerator;
        $this->translator = $translator;
    }

    public static function getSubscribedEvents()
    {
        return [
            'HeimrichHannot\SyndicationTypeBundle\Event\AddSyndicationTypeFieldsEvent' => 'addFields',
//            'HeimrichHannot\SyndicationTypeBundle\Event\AddSyndicationTypePaletteSelectorsEvent' => '',
//            'HeimrichHannot\SyndicationTypeBundle\Event\AddSyndicationTypeSubpalettesEvent' => ''
        ];
    }

    public function addFields(AddSyndicationTypeFieldsEvent $event): void
    {
        $event->addField('syndicationPdfCreatorTemplate', [
            'label' => [
                $this->translator->trans('huh.pdf_creator.fields.syndicationPdfCreatorTemplate.name'),
                $this->translator->trans('huh.pdf_creator.fields.syndicationPdfCreatorTemplate.description'),
            ],
            'inputType' => 'select',
            'options_callback' => function ($dc) {
                return Controller::getTemplateGroup('syndication_type_pdf_');
            },
            'exclude' => true,
            'eval' => ['includeBlankOption' => true, 'chosen' => true, 'tl_class' => 'w50'],
            'sql' => "varchar(64) NOT NULL default ''",
        ]);

        $event->addField('syndicationPdfCreatorConfig', $this->dcaGenerator->getPdfCreatorConfigSelectFieldConfig());
    }
}

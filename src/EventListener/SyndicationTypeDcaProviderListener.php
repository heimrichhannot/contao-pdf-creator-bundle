<?php

/*
 * Copyright (c) 2021 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace Heimrichhannot\PdfCreatorBundle\EventListener;

use Heimrichhannot\PdfCreatorBundle\Generator\DcaGenerator;
use Heimrichhannot\PdfCreatorBundle\SyndicationType\PdfCreatorSyndicationType;
use HeimrichHannot\SyndicationTypeBundle\Event\AddSyndicationTypeFieldsEvent;
use HeimrichHannot\SyndicationTypeBundle\Event\AddSyndicationTypePaletteSelectorsEvent;
use HeimrichHannot\SyndicationTypeBundle\Event\AddSyndicationTypeSubpalettesEvent;
use Psr\Container\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Contracts\Service\ServiceSubscriberInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class SyndicationTypeDcaProviderListener implements EventSubscriberInterface, ServiceSubscriberInterface
{
    protected ContainerInterface $container;
    protected DcaGenerator $dcaGenerator;
    protected TranslatorInterface $translator;

    /**
     * SyndicationTypeDcaProviderSubscriber constructor.
     */
    public function __construct(ContainerInterface $container, DcaGenerator $dcaGenerator, TranslatorInterface $translator)
    {
        $this->dcaGenerator = $dcaGenerator;
        $this->translator = $translator;
        $this->container = $container;
    }

    public static function getSubscribedEvents()
    {
        return [
            'HeimrichHannot\SyndicationTypeBundle\Event\AddSyndicationTypeFieldsEvent' => 'onAddFields',
            'HeimrichHannot\SyndicationTypeBundle\Event\AddSyndicationTypeSubpalettesEvent' => 'onAddSubpalettes',
            'HeimrichHannot\SyndicationTypeBundle\Event\AddSyndicationTypePaletteSelectorsEvent' => 'onAddSelector',
        ];
    }

    public static function getSubscribedServices()
    {
        return [
            '?HeimrichHannot\EncoreBundle\Dca\DcaGenerator',
        ];
    }

    public function onAddFields(AddSyndicationTypeFieldsEvent $event): void
    {
        $event->addTemplateSelectField('synPdfCreatorTemplate', 'syndication_type_pdf_');
        $event->addField('synPdfCreatorConfig', $this->dcaGenerator->getPdfCreatorConfigSelectFieldConfig());

        if ($this->container->has('HeimrichHannot\EncoreBundle\Dca\DcaGenerator')) {
            $event->addCheckboxField('synPdfCreatorUseCustomEncoreEntries', true);
            $event->addField('synPdfCreatorCustomEncoreEntries', $this->container->get(\HeimrichHannot\EncoreBundle\Dca\DcaGenerator::class)->getEncoreEntriesSelect(false));
        }
    }

    public function onAddSubpalettes(AddSyndicationTypeSubpalettesEvent $event)
    {
        if ($this->container->has('HeimrichHannot\EncoreBundle\Dca\DcaGenerator')) {
            $event->addSubpalettes('synPdfCreatorUseCustomEncoreEntries', 'synPdfCreatorCustomEncoreEntries');
            $event->addSubpalettes(
                PdfCreatorSyndicationType::getActivationField(),
                str_replace(
                    'synPdfCreatorTemplate',
                    'synPdfCreatorTemplate,synPdfCreatorUseCustomEncoreEntries',
                    $event->getSubpalettes()[PdfCreatorSyndicationType::getActivationField()]
                )
            );
//            $event->addSubpalettes('synUsePrintTemplate', $event->getSubpalettes()['synUsePrintTemplate'].',synPrintUseCustomEncoreEntries');
        }
    }

    public function onAddSelector(AddSyndicationTypePaletteSelectorsEvent $event): void
    {
        if ($this->container->has('HeimrichHannot\EncoreBundle\Dca\DcaGenerator')) {
            $event->addSelector('synPdfCreatorUseCustomEncoreEntries');
        }
    }
}

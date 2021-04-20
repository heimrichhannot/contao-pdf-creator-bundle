<?php

/*
 * Copyright (c) 2021 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace Heimrichhannot\PdfCreatorBundle\EventSubscriber;

use Dompdf\Options;
use HeimrichHannot\PdfCreator\Concrete\DompdfCreator;
use Heimrichhannot\PdfCreatorBundle\Event\BeforeCreateLibraryInstanceEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class PdfCreatorSubscriber implements EventSubscriberInterface
{
    /**
     * @var KernelInterface
     */
    protected $kernel;

    /**
     * PdfCreatorSubscriber constructor.
     */
    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    public function addDompdfLogging(BeforeCreateLibraryInstanceEvent $event)
    {
        if (DompdfCreator::getType() === $event->getBeforeCreateLibraryInstanceCallback()->getType()) {
            /** @var Options $options */
            $options = $event->getBeforeCreateLibraryInstanceCallback()->getConstructorParameters()['options'];
            $options->setLogOutputFile($this->kernel->getLogDir().'/huh_pdf_creator_dompdf.log');
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            BeforeCreateLibraryInstanceEvent::class => [
                ['addDompdfLogging', 10],
            ],
        ];
    }
}

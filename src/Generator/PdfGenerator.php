<?php

/*
 * Copyright (c) 2021 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace Heimrichhannot\PdfCreatorBundle\Generator;

use Ausi\SlugGenerator\SlugGenerator;
use HeimrichHannot\PdfCreator\BeforeCreateLibraryInstanceCallback;
use HeimrichHannot\PdfCreator\BeforeOutputPdfCallback;
use HeimrichHannot\PdfCreator\PdfCreatorFactory;
use Heimrichhannot\PdfCreatorBundle\Event\BeforeCreateLibraryInstanceEvent;
use Heimrichhannot\PdfCreatorBundle\Event\BeforeOutputPdfCallbackEvent;
use Heimrichhannot\PdfCreatorBundle\Exception\InvalidPdfGeneratorConfigurationException;
use Heimrichhannot\PdfCreatorBundle\Exception\PdfCreatorConfigurationNotFoundException;
use Heimrichhannot\PdfCreatorBundle\Exception\PdfCreatorNotFoundException;
use Heimrichhannot\PdfCreatorBundle\Model\PdfCreatorConfigModel;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class PdfGenerator
{
    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * PdfGenerator constructor.
     */
    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function generate(string $htmlContent, int $configuration, PdfGeneratorContext $context): void
    {
        $configuration = PdfCreatorConfigModel::findByPk($configuration);

        if (!$configuration) {
            throw new PdfCreatorConfigurationNotFoundException((int) $configuration);
        }

        $type = PdfCreatorFactory::createInstance($configuration->type);

        if (!$type) {
            throw new PdfCreatorNotFoundException($configuration->type);
        }

        $eventDispatch = $this->eventDispatcher;
        $slugGenerator = new SlugGenerator();

        $type->setBeforeCreateInstanceCallback(function (BeforeCreateLibraryInstanceCallback $callback) use ($eventDispatch) {
            /* @noinspection PhpParamsInspection */
            /* @noinspection PhpMethodParametersCountMismatchInspection */
            $eventDispatch->dispatch(BeforeCreateLibraryInstanceEvent::class, new BeforeCreateLibraryInstanceEvent($callback));
        });

        $type->setBeforeOutputPdfCallback(function (BeforeOutputPdfCallback $callback) use ($eventDispatch) {
            /* @noinspection PhpParamsInspection */
            /* @noinspection PhpMethodParametersCountMismatchInspection */
            $eventDispatch->dispatch(BeforeOutputPdfCallbackEvent::class, new BeforeOutputPdfCallbackEvent($callback));
        });

        $filename = str_replace(['%title'], [$slugGenerator->generate($context->getTitle())], $configuration->filename);
        $type->setFilename($filename);

        $type->setOrientation($configuration->orientation);

        $type->setOutputMode($configuration->outputMode);

        $formatSize = explode(',', $configuration->format);

        if (2 == \count($formatSize)) {
            $type->setFormat($formatSize);
        } elseif (\count($formatSize) > 2) {
            throw new InvalidPdfGeneratorConfigurationException('Invalid pdf format given.');
        } else {
            $type->setFormat($configuration->format);
        }

        $type->setHtmlContent($htmlContent);

        $type->render();
    }
}

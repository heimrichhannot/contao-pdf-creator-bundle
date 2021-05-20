<?php

/*
 * Copyright (c) 2021 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace Heimrichhannot\PdfCreatorBundle\Generator;

use Ausi\SlugGenerator\SlugGenerator;
use Contao\FilesModel;
use Contao\StringUtil;
use HeimrichHannot\PdfCreator\AbstractPdfCreator;
use HeimrichHannot\PdfCreator\BeforeCreateLibraryInstanceCallback;
use HeimrichHannot\PdfCreator\BeforeOutputPdfCallback;
use HeimrichHannot\PdfCreator\PdfCreatorFactory;
use Heimrichhannot\PdfCreatorBundle\Event\BeforeCreateLibraryInstanceEvent;
use Heimrichhannot\PdfCreatorBundle\Event\BeforeOutputPdfCallbackEvent;
use Heimrichhannot\PdfCreatorBundle\Exception\InvalidPdfGeneratorConfigurationException;
use Heimrichhannot\PdfCreatorBundle\Exception\PdfCreatorConfigurationNotFoundException;
use Heimrichhannot\PdfCreatorBundle\Exception\PdfCreatorNotFoundException;
use Heimrichhannot\PdfCreatorBundle\Model\PdfCreatorConfigModel;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class PdfGenerator
{
    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;
    /**
     * @var string
     */
    protected $projectFolder;
    /**
     * @var LoggerInterface
     */
    protected $pdfInstanceLog;
    /**
     * @var KernelInterface
     */
    protected $kernel;
    /**
     * @var array
     */
    protected $bundleConfig;

    /**
     * PdfGenerator constructor.
     */
    public function __construct(EventDispatcherInterface $eventDispatcher, string $projectFolder, LoggerInterface $pdfInstanceLog, KernelInterface $kernel, array $bundleConfig)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->projectFolder = $projectFolder;
        $this->pdfInstanceLog = $pdfInstanceLog;
        $this->kernel = $kernel;
        $this->bundleConfig = $bundleConfig;
    }

    public function generate(string $htmlContent, string $configuration, PdfGeneratorContext $context): void
    {
        if (is_numeric($configuration)) {
            $configuration = PdfCreatorConfigModel::findByPk($configuration);
        } else {
            if (isset($this->bundleConfig['configurations'][$configuration])) {
                $configuration = PdfCreatorConfigModel::createModelFromBundleConfig(
                    $configuration,
                    $this->bundleConfig['configurations'][$configuration]
                );
            }
        }

        if (!$configuration) {
            throw new PdfCreatorConfigurationNotFoundException((int) $configuration);
        }

        $type = PdfCreatorFactory::createInstance($configuration->type);

        if (!$type) {
            throw new PdfCreatorNotFoundException($configuration->type);
        }

        if ($this->kernel->isDebug() && $type->isSupported(AbstractPdfCreator::SUPPORT_PSR_LOGGING)) {
            $type->setLogger($this->pdfInstanceLog);
        }

        $eventDispatcher = $this->eventDispatcher;
        $slugGenerator = new SlugGenerator();

        $type->setBeforeCreateInstanceCallback(function (BeforeCreateLibraryInstanceCallback $callback) use ($eventDispatcher, $configuration) {
            /* @noinspection PhpParamsInspection */
            /* @noinspection PhpMethodParametersCountMismatchInspection */
            $eventDispatcher->dispatch(BeforeCreateLibraryInstanceEvent::class, new BeforeCreateLibraryInstanceEvent($callback, $configuration));
        });

        $type->setBeforeOutputPdfCallback(function (BeforeOutputPdfCallback $callback) use ($eventDispatcher, $configuration) {
            /* @noinspection PhpParamsInspection */
            /* @noinspection PhpMethodParametersCountMismatchInspection */
            $eventDispatcher->dispatch(BeforeOutputPdfCallbackEvent::class, new BeforeOutputPdfCallbackEvent($callback, $configuration));
        });

        $filename = str_replace(
            ['%title%'],
            [$slugGenerator->generate($context->getTitle())],
            $configuration->filename
        );
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

        $fonts = StringUtil::deserialize($configuration->fonts, true);

        if (!empty(array_filter($fonts))) {
            foreach ($fonts as $font) {
                if (file_exists($this->projectFolder.\DIRECTORY_SEPARATOR.$font['filepath'])) {
                    $type->addFont($this->projectFolder.\DIRECTORY_SEPARATOR.$font['filepath'], $font['family'], $font['style'], $font['weight']);
                }
            }
        }

        $margins = StringUtil::deserialize($configuration->pageMargins, true);

        if (!empty(array_filter($margins))) {
            $type->setMargins($margins['top'], $margins['right'], $margins['bottom'], $margins['left']);
        }

        if ($configuration->masterTemplate && $file = FilesModel::findByUuid($configuration->masterTemplate)) {
            if (file_exists($this->projectFolder.\DIRECTORY_SEPARATOR.'web/'.$file->path)) {
                $type->setTemplateFilePath($this->projectFolder.\DIRECTORY_SEPARATOR.$file->path);
            }
        }

        $type->setHtmlContent($htmlContent);

        $type->render();
    }
}

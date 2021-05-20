<?php

/*
 * Copyright (c) 2021 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace Heimrichhannot\PdfCreatorBundle\Event;

use HeimrichHannot\PdfCreator\BeforeOutputPdfCallback;
use Heimrichhannot\PdfCreatorBundle\Model\PdfCreatorConfigModel;
use Symfony\Component\EventDispatcher\Event;

class BeforeOutputPdfCallbackEvent extends Event
{
    /**
     * @var BeforeOutputPdfCallback
     */
    protected $beforeOutputPdfCallback;
    /**
     * @var PdfCreatorConfigModel
     */
    protected $configuration;

    /**
     * BeforeOutputPdfCallbackEvent constructor.
     */
    public function __construct(BeforeOutputPdfCallback $beforeOutputPdfCallback, PdfCreatorConfigModel $configuration)
    {
        $this->beforeOutputPdfCallback = $beforeOutputPdfCallback;
        $this->configuration = $configuration;
    }

    public function getBeforeOutputPdfCallback(): BeforeOutputPdfCallback
    {
        return $this->beforeOutputPdfCallback;
    }

    public function setBeforeOutputPdfCallback(BeforeOutputPdfCallback $beforeOutputPdfCallback): void
    {
        $this->beforeOutputPdfCallback = $beforeOutputPdfCallback;
    }

    public function getConfiguration(): PdfCreatorConfigModel
    {
        return $this->configuration;
    }
}

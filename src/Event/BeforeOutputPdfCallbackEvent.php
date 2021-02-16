<?php

/*
 * Copyright (c) 2021 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace Heimrichhannot\PdfCreatorBundle\Event;

use HeimrichHannot\PdfCreator\BeforeOutputPdfCallback;
use Symfony\Component\EventDispatcher\Event;

class BeforeOutputPdfCallbackEvent extends Event
{
    /**
     * @var BeforeOutputPdfCallback
     */
    protected $beforeOutputPdfCallback;

    /**
     * BeforeOutputPdfCallbackEvent constructor.
     */
    public function __construct(BeforeOutputPdfCallback $beforeOutputPdfCallback)
    {
        $this->beforeOutputPdfCallback = $beforeOutputPdfCallback;
    }

    public function getBeforeOutputPdfCallback(): BeforeOutputPdfCallback
    {
        return $this->beforeOutputPdfCallback;
    }

    public function setBeforeOutputPdfCallback(BeforeOutputPdfCallback $beforeOutputPdfCallback): void
    {
        $this->beforeOutputPdfCallback = $beforeOutputPdfCallback;
    }
}

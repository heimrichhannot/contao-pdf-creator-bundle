<?php

/*
 * Copyright (c) 2021 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace Heimrichhannot\PdfCreatorBundle\Event;

use HeimrichHannot\PdfCreator\BeforeCreateLibraryInstanceCallback;
use Heimrichhannot\PdfCreatorBundle\Model\PdfCreatorConfigModel;
use Symfony\Component\EventDispatcher\Event;

class BeforeCreateLibraryInstanceEvent extends Event
{
    /**
     * @var BeforeCreateLibraryInstanceCallback
     */
    protected $beforeCreateLibraryInstanceCallback;
    /**
     * @var PdfCreatorConfigModel
     */
    protected $configuration;

    /**
     * BeforeCreateLibraryInstanceEvent constructor.
     */
    public function __construct(BeforeCreateLibraryInstanceCallback $beforeCreateLibraryInstanceCallback, PdfCreatorConfigModel $configuration)
    {
        $this->beforeCreateLibraryInstanceCallback = $beforeCreateLibraryInstanceCallback;
        $this->configuration = $configuration;
    }

    public function getBeforeCreateLibraryInstanceCallback(): BeforeCreateLibraryInstanceCallback
    {
        return $this->beforeCreateLibraryInstanceCallback;
    }

    public function setBeforeCreateLibraryInstanceCallback(BeforeCreateLibraryInstanceCallback $beforeCreateLibraryInstanceCallback): void
    {
        $this->beforeCreateLibraryInstanceCallback = $beforeCreateLibraryInstanceCallback;
    }

    public function getConfiguration(): PdfCreatorConfigModel
    {
        return $this->configuration;
    }
}

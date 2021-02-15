<?php

/*
 * Copyright (c) 2021 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace Heimrichhannot\PdfCreatorBundle\Event;

use HeimrichHannot\PdfCreator\BeforeCreateLibraryInstanceCallback;
use Symfony\Component\EventDispatcher\Event;

class BeforeCreateLibraryInstanceEvent extends Event
{
    /**
     * @var BeforeCreateLibraryInstanceCallback
     */
    protected $beforeCreateLibraryInstanceCallback;

    /**
     * BeforeCreateLibraryInstanceEvent constructor.
     */
    public function __construct(BeforeCreateLibraryInstanceCallback $beforeCreateLibraryInstanceCallback)
    {
        $this->beforeCreateLibraryInstanceCallback = $beforeCreateLibraryInstanceCallback;
    }

    public function getBeforeCreateLibraryInstanceCallback(): BeforeCreateLibraryInstanceCallback
    {
        return $this->beforeCreateLibraryInstanceCallback;
    }

    public function setBeforeCreateLibraryInstanceCallback(BeforeCreateLibraryInstanceCallback $beforeCreateLibraryInstanceCallback): void
    {
        $this->beforeCreateLibraryInstanceCallback = $beforeCreateLibraryInstanceCallback;
    }
}

<?php

/*
 * Copyright (c) 2023 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace Heimrichhannot\PdfCreatorBundle\Generator;

class PdfGeneratorContext
{
    protected string $title;

    private array $overrideConfiguration = [];
    /**
     * @var callable|null
     */
    private $beforeOutputPdfCallbackListener = null;
    /**
     * @var callable|null
     */
    private $beforeCreateLibraryInstanceCallbackListener = null;

    /**
     * PdfGeneratorContext constructor.
     */
    public function __construct(string $title)
    {
        $this->title = $title;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getOverrideConfiguration(): array
    {
        return $this->overrideConfiguration;
    }

    /**
     * Override pdf configuration options. Keys must be same as model properties.
     */
    public function setOverrideConfiguration(array $overrideConfiguration): void
    {
        $this->overrideConfiguration = $overrideConfiguration;
    }

    public function setBeforeCreateLibraryInstanceCallbackListener(callable $listener): void
    {
        $this->beforeCreateLibraryInstanceCallbackListener = $listener;
    }

    public function setBeforeOutputPdfCallbackListener(callable $listener): void
    {
        $this->beforeOutputPdfCallbackListener = $listener;
    }

    public function getBeforeOutputPdfCallbackListener(): ?callable
    {
        return $this->beforeOutputPdfCallbackListener;
    }

    public function getBeforeCreateLibraryInstanceCallbackListener(): ?callable
    {
        return $this->beforeCreateLibraryInstanceCallbackListener;
    }
}

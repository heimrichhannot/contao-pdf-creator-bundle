<?php

/*
 * Copyright (c) 2021 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace Heimrichhannot\PdfCreatorBundle\Exception;

class PdfCreatorConfigurationNotFoundException extends \Exception
{
    public function __construct(int $configurationId, $overrideMesssage = '', $code = 0, Throwable $previous = null)
    {
        if (empty($overrideMesssage)) {
            $overrideMesssage = 'A pdf creator configuration with id %configuration% could not be found.';
        }
        $overrideMesssage = str_replace('%configuration%', $configurationId, $overrideMesssage);

        parent::__construct($overrideMesssage, $code, $previous);
    }
}

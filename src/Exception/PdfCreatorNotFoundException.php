<?php

/*
 * Copyright (c) 2021 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace Heimrichhannot\ContaoPdfCreatorBundle\Exception;

use Throwable;

class PdfCreatorNotFoundException extends \Exception
{
    public function __construct(string $type, $overrideMesssage = '', $code = 0, Throwable $previous = null)
    {
        if (empty($overrideMesssage)) {
            $overrideMesssage = 'A pdf creator of type %type% could not be found!';
        }
        $overrideMesssage = str_replace('%type%', $type, $overrideMesssage);

        parent::__construct($overrideMesssage, $code, $previous);
    }
}

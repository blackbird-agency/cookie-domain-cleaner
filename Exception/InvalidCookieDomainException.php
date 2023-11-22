<?php

declare(strict_types=1);

namespace Blackbird\CookieDomainCleaner\Exception;

use Magento\Framework\Phrase;
use Magento\Framework\Exception\LocalizedException;

class InvalidCookieDomainException extends LocalizedException
{
    public function __construct(Phrase $phrase = null, \Exception $cause = null, $code = 0)
    {
        if ($phrase === null) {
            $phrase = __('The cookie domain format is not valid');
        }
        parent::__construct($phrase, $cause, $code);
    }
}

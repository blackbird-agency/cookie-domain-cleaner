<?php

declare(strict_types=1);

namespace Blackbird\CookieDomainCleaner\Exception;

use Magento\Framework\Phrase;
use Magento\Framework\Exception\LocalizedException;

class CookieDomainIsAlreadyParentDomainException extends LocalizedException
{
    public function __construct(Phrase $phrase = null, \Exception $cause = null, $code = 0)
    {
        if ($phrase === null) {
            $phrase = __('The cookie domain is already a parent domain');
        }
        parent::__construct($phrase, $cause, $code);
    }
}

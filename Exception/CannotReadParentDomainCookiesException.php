<?php

declare(strict_types=1);

namespace Blackbird\CookieDomainCleaner\Exception;

use Magento\Framework\Phrase;
use Magento\Framework\Exception\LocalizedException;

class CannotReadParentDomainCookiesException extends LocalizedException
{
    public function __construct(Phrase $phrase = null, \Exception $cause = null, $code = 0)
    {
        if ($phrase === null) {
            $phrase = __('The $_SERVER[\'HTTP_COOKIE\'] key does not exist');
        }
        parent::__construct($phrase, $cause, $code);
    }
}

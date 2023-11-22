<?php

declare(strict_types=1);

namespace Blackbird\CookieDomainCleaner\Api\Service;

interface CookieDomainFormatterInterface
{
    public function getParentDomainFromCookieDomain(string $cookieDomain): string;
}

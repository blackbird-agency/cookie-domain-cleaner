<?php

declare(strict_types=1);

namespace Blackbird\CookieDomainCleaner\Api\Service;

interface CookieCheckerInterface
{
    public function doesCookieExistInParentDomain(string $cookieName): bool;
}

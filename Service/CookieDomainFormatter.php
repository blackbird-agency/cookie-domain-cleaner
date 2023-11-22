<?php

declare(strict_types=1);

namespace Blackbird\CookieDomainCleaner\Service;

use Blackbird\CookieDomainCleaner\Exception\InvalidCookieDomainException;
use Blackbird\CookieDomainCleaner\Api\Service\CookieDomainFormatterInterface;
use Blackbird\CookieDomainCleaner\Exception\CookieDomainIsAlreadyParentDomainException;

class CookieDomainFormatter implements CookieDomainFormatterInterface
{

    /**
     * @throws CookieDomainIsAlreadyParentDomainException
     * @throws InvalidCookieDomainException
     */
    public function getParentDomainFromCookieDomain(string $cookieDomain): string
    {
        $cookieDomainParts = explode('.', \trim($cookieDomain, '.'));
        if (empty($cookieDomainParts) || count($cookieDomainParts) < 2) {
            throw new InvalidCookieDomainException(__('The cookie domain format of "%1" is invalid', $cookieDomain));
        }

        if (count($cookieDomainParts) === 2) {
            throw new CookieDomainIsAlreadyParentDomainException(
                __('The cookie domain "%1" is already a parent domain', $cookieDomain)
            );
        }

        $domainName = $cookieDomainParts[count($cookieDomainParts) - 2];
        $extension  = $cookieDomainParts[count($cookieDomainParts) - 1];

        return "{$domainName}.{$extension}";
    }
}

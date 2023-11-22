<?php

declare(strict_types=1);

namespace Blackbird\CookieDomainCleaner\Service;

use Magento\Framework\Exception\LocalizedException;
use Blackbird\CookieDomainCleaner\Api\Service\CookieCheckerInterface;
use Blackbird\CookieDomainCleaner\Exception\CannotReadParentDomainCookiesException;

class CookieChecker implements CookieCheckerInterface
{

    /**
     * @throws CannotReadParentDomainCookiesException
     * @throws LocalizedException
     */
    public function doesCookieExistInParentDomain(string $cookieName): bool
    {
        if (empty($cookieName)) {
            throw new LocalizedException(__('The cookie name is empty'));
        }

        if (!isset($_SERVER['HTTP_COOKIE']) || empty($_SERVER['HTTP_COOKIE'])) {
            // $_SERVER['HTTP_COOKIE'] is not always set depending on server configuration
            throw new CannotReadParentDomainCookiesException();
        }

        $allCookiesString = $_SERVER['HTTP_COOKIE'];
        preg_match_all("/{$cookieName}=/", $allCookiesString, $matches);

        if (empty($matches[0])) {
            return false;
        }

        return count($matches[0]) >= 2;
    }
}

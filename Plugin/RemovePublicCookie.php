<?php

declare(strict_types=1);

namespace Blackbird\CookieDomainCleaner\Plugin;

use Blackbird\CookieDomainCleaner\Plugin\LocalizedException;
use Magento\Framework\Stdlib\CookieManagerInterface;
use Blackbird\CookieDomainCleaner\Api\Service\CookieRemoverInterface;
use Blackbird\CookieDomainCleaner\Api\Service\CookieCheckerInterface;
use Magento\Framework\Stdlib\Cookie\PublicCookieMetadata;
use Blackbird\CookieDomainCleaner\Exception\CannotReadParentDomainCookiesException;
use Blackbird\CookieDomainCleaner\Exception\CookieDomainIsAlreadyParentDomainException;

class RemovePublicCookie
{
    public function __construct(
        protected CookieRemoverInterface $cookieRemover,
        protected CookieCheckerInterface $cookieChecker,
    ) {
    }

    /**
     * Remove the cookie name from the parent domain if the current domain is a subdomain
     */
    public function beforeSetPublicCookie(
        CookieManagerInterface $subject,
        string                 $name,
        ?string                $value,
        PublicCookieMetadata   $metadata = null,
    ): array {
        try {
            // Optimise this plugin by checking if there are duplicates in request cookies
            if (!$this->cookieChecker->doesCookieExistInParentDomain($name)) {
                return [$name, $value, $metadata];
            }
        } catch (CannotReadParentDomainCookiesException) {
            // This exception is raised for not being able to optimise this code
        }

        try {
            $this->cookieRemover->deletePublicCookieFromParentDomain($name, $value, $metadata);
        } catch (CookieDomainIsAlreadyParentDomainException) {
            // Do nothing to exit this plugin at the next return
        } catch (LocalizedException) {
            // Do nothing to exit this plugin at the next return
        }

        return [$name, $value, $metadata];
    }
}

<?php

declare(strict_types=1);

namespace Blackbird\CookieDomainCleaner\Plugin;

use Magento\Framework\Session\SessionManager;
use Magento\Framework\Exception\LocalizedException;
use Blackbird\CookieDomainCleaner\Api\Service\CookieCheckerInterface;
use Blackbird\CookieDomainCleaner\Api\Service\CookieRemoverInterface;
use Magento\Framework\Stdlib\Cookie\CookieMetadataFactory;
use Blackbird\CookieDomainCleaner\Exception\CannotReadParentDomainCookiesException;

class PreventPhpsessidHeaderRemoval
{
    public function __construct(
        protected CookieCheckerInterface $cookieChecker,
        protected CookieRemoverInterface $cookieRemover,
        protected CookieMetadataFactory  $cookieMetadataFactory,
    ) {
    }

    /**
     * Make sure that the PHPSESSID is being remove if there are 2 of them. One in the current cookie domain and one in
     * the parent cookie domain. Because the session_regenerate_id native php function regenerate the Set-Cookie directive
     * which create the cookie in the headers and remove the Set-Cookie directive which remove the parent domain cookie.
     */
    public function afterRegenerateId(SessionManager $subject, $result)
    {
        $cookieName = \ini_get('session.name') ?: 'PHPSESSID';

        try {
            // Optimise this plugin by checking if there are duplicates in request cookies
            if (!$this->cookieChecker->doesCookieExistInParentDomain($cookieName)) {
                return $result;
            }
        } catch (CannotReadParentDomainCookiesException) {
            // This exception is raised for not being able to optimise this code
        }

        try {
            $defaultMetadata = $this->cookieMetadataFactory
                ->createPublicCookieMetadata()
                ->setPath('/');

            $this->cookieRemover->deletePublicCookieFromParentDomain($cookieName, null, $defaultMetadata);
        } catch (LocalizedException) {
            return $result;
        }

        return $result;
    }
}

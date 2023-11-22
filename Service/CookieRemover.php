<?php

declare(strict_types=1);

namespace Blackbird\CookieDomainCleaner\Service;

use Magento\Framework\Stdlib\CookieManagerInterface;
use Magento\Framework\Session\Config as SessionConfig;
use Blackbird\CookieDomainCleaner\Api\Service\CookieRemoverInterface;
use Blackbird\CookieDomainCleaner\Api\Service\CookieCheckerInterface;
use Magento\Framework\Stdlib\Cookie\PublicCookieMetadata;
use Magento\Framework\Stdlib\Cookie\CookieMetadataFactory;
use Blackbird\CookieDomainCleaner\Exception\InvalidCookieDomainException;
use Magento\Framework\Stdlib\Cookie\SensitiveCookieMetadata;
use Blackbird\CookieDomainCleaner\Api\Service\CookieDomainFormatterInterface;
use Blackbird\CookieDomainCleaner\Exception\CookieDomainIsAlreadyParentDomainException;

class CookieRemover implements CookieRemoverInterface
{
    public function __construct(
        protected CookieManagerInterface         $cookieManager,
        protected CookieMetadataFactory          $cookieMetadataFactory,
        protected SessionConfig                  $sessionConfig,
        protected CookieDomainFormatterInterface $cookieDomainFormatter,
        protected CookieCheckerInterface         $cookieChecker,
    ) {
    }

    /**
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Stdlib\Cookie\FailureToSendException
     * @throws CookieDomainIsAlreadyParentDomainException
     * @throws InvalidCookieDomainException
     */
    public function deletePublicCookieFromParentDomain(
        string               $name,
        ?string              $value,
        PublicCookieMetadata $metadata = null,
    ): void {
        $currentCookieDomain  = $this->sessionConfig->getCookieDomain();
        $parentCookieDomain   = $this->cookieDomainFormatter->getParentDomainFromCookieDomain($currentCookieDomain);
        $parentDomainMetadata = $this->cookieMetadataFactory
            ->createPublicCookieMetadata($metadata?->__toArray() ?? [])
            ->setDomain($parentCookieDomain);

        $currentCookieValue = $_COOKIE[$name] ?? $value;
        $this->cookieManager->deleteCookie($name, $parentDomainMetadata);
        // Even though, the cookie is deleted from an other domain, the deleteCookie method still remove the cookie
        // from the $_COOKIE variable. This variable represent the current domain cookies so we want to set it back
        $_COOKIE[$name] = $currentCookieValue;
    }

    /**
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Stdlib\Cookie\FailureToSendException
     * @throws CookieDomainIsAlreadyParentDomainException
     * @throws InvalidCookieDomainException
     */
    public function deleteSensitiveCookieFromParentDomain(
        string                  $name,
        ?string                 $value,
        SensitiveCookieMetadata $metadata = null,
    ): void {
        $currentCookieDomain  = $this->sessionConfig->getCookieDomain();
        $parentCookieDomain   = $this->cookieDomainFormatter->getParentDomainFromCookieDomain($currentCookieDomain);
        $parentDomainMetadata = $this->cookieMetadataFactory
            ->createSensitiveCookieMetadata($metadata?->__toArray() ?? [])
            ->setDomain($parentCookieDomain);

        $currentCookieValue = $_COOKIE[$name] ?? $value;
        $this->cookieManager->deleteCookie($name, $parentDomainMetadata);
        // Even though, the cookie is deleted from an other domain, the deleteCookie method still remove the cookie
        // from the $_COOKIE variable. This variable represent the current domain cookies so we want to set it back
        $_COOKIE[$name] = $currentCookieValue;
    }
}

<?php

declare(strict_types=1);

namespace Blackbird\CookieDomainCleaner\Test\Unit;

use Blackbird\CookieDomainCleaner\Api\Service\CookieCheckerInterface;
use Blackbird\CookieDomainCleaner\Api\Service\CookieRemoverInterface;
use Blackbird\CookieDomainCleaner\Exception\InvalidCookieDomainException;
use Blackbird\CookieDomainCleaner\Plugin\RemovePublicCookie;
use Blackbird\CookieDomainCleaner\Plugin\RemoveSensitiveCookie;
use Magento\Framework\Stdlib\CookieManagerInterface;
use Magento\Framework\Stdlib\Cookie\PublicCookieMetadata;
use Magento\Framework\Stdlib\Cookie\SensitiveCookieMetadata;
use PHPUnit\Framework\TestCase;

class RemovePublicCookieTest extends TestCase
{
    public function testInvalidCookieDomainDoesNotEscapeThePublicCookiePlugin(): void
    {
        $cookieChecker = $this->createMock(CookieCheckerInterface::class);
        $cookieChecker->method('doesCookieExistInParentDomain')->willReturn(true);
        $cookieRemover = $this->createMock(CookieRemoverInterface::class);
        $cookieRemover->method('deletePublicCookieFromParentDomain')
            ->willThrowException(new InvalidCookieDomainException(__('invalid')));
        $metadata = $this->createMock(PublicCookieMetadata::class);

        $result = (new RemovePublicCookie($cookieRemover, $cookieChecker))->beforeSetPublicCookie(
            $this->createMock(CookieManagerInterface::class),
            'name',
            'value',
            $metadata
        );

        self::assertSame(['name', 'value', $metadata], $result);
    }

    public function testInvalidCookieDomainDoesNotEscapeTheSensitiveCookiePlugin(): void
    {
        $cookieChecker = $this->createMock(CookieCheckerInterface::class);
        $cookieChecker->method('doesCookieExistInParentDomain')->willReturn(true);
        $cookieRemover = $this->createMock(CookieRemoverInterface::class);
        $cookieRemover->method('deleteSensitiveCookieFromParentDomain')
            ->willThrowException(new InvalidCookieDomainException(__('invalid')));
        $metadata = $this->createMock(SensitiveCookieMetadata::class);

        $result = (new RemoveSensitiveCookie($cookieRemover, $cookieChecker))->beforeSetSensitiveCookie(
            $this->createMock(CookieManagerInterface::class),
            'name',
            'value',
            $metadata
        );

        self::assertSame(['name', 'value', $metadata], $result);
    }
}

<?php

declare(strict_types=1);

namespace Blackbird\CookieDomainCleaner\Test\Unit;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use Blackbird\CookieDomainCleaner\Service\CookieChecker;
use Magento\Framework\Exception\LocalizedException;
use Blackbird\CookieDomainCleaner\Exception\CannotReadParentDomainCookiesException;

class CookieCheckerTest extends TestCase
{
    protected ?CookieChecker $subject;

    protected function setUp(): void
    {
        $this->subject = new CookieChecker();
    }

    protected function tearDown(): void
    {
        $this->subject = null;
    }

    public static function getValidCookiesStringCases(): array
    {
        return [
            ['b', 'a=1; b=2; b=3;', true],
            ['b', 'a=1; a=2; b=3;', false],
            ['a', 'a=1; b=2; c=3;', false],
            ['z', 'a=1; b=2; b=3;', false],
        ];
    }

    /**
     * @dataProvider getValidCookiesStringCases
     */
    #[DataProvider('getValidCookiesStringCases')]
    public function testWithValidCookiesStringWillReturnValue(
        string $cookieName,
        string $serverCookieString,
        bool   $expectedResult,
    ): void {
        $_SERVER['HTTP_COOKIE'] = $serverCookieString;

        $result = $this->subject->doesCookieExistInParentDomain($cookieName);

        self::assertEquals($expectedResult, $result);
    }

    public static function getInvalidCookiesStringCases(): array
    {
        return [
            ['a', '', CannotReadParentDomainCookiesException::class],
            ['', 'a=1; b=2; b=3;', LocalizedException::class],
            ['', '', LocalizedException::class],
        ];
    }

    /**
     * @dataProvider getInvalidCookiesStringCases
     */
    #[DataProvider('getInvalidCookiesStringCases')]
    public function testWithInvalidCookiesStringWillThrowException(
        string $cookieName,
        string $serverCookieString,
        string $exptectedException,
    ): void {
        $_SERVER['HTTP_COOKIE'] = $serverCookieString;

        $this->expectException($exptectedException);

        $result = $this->subject->doesCookieExistInParentDomain($cookieName);
    }
}

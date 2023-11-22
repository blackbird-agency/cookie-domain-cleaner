<?php

declare(strict_types=1);

namespace Blackbird\CookieDomainCleaner\Test\Unit;

use PHPUnit\Framework\TestCase;
use Blackbird\CookieDomainCleaner\Service\CookieDomainFormatter;
use Blackbird\CookieDomainCleaner\Exception\InvalidCookieDomainException;
use Blackbird\CookieDomainCleaner\Exception\CookieDomainIsAlreadyParentDomainException;

class CookieDomainFormatterTest extends TestCase
{
    protected ?CookieDomainFormatter $subject;

    protected function setUp(): void
    {
        $this->subject = new CookieDomainFormatter();
    }

    protected function tearDown(): void
    {
        $this->subject = null;
    }

    public function getValidCookieDomainCases(): array
    {
        return [
            ['bultex.cofel.test', 'cofel.test'],
            ['preprod.bultex.fr', 'bultex.fr'],
            ['subsubdomain.preprod.bultex.fr', 'bultex.fr'],
        ];
    }

    /**
     * @dataProvider getValidCookieDomainCases
     */
    public function testWithValidCookieDomainWillReturnParentCookieDomain(
        string $cookieDomain,
        string $expectedParentDomain,
    ): void {
        $resultingParentDomain = $this->subject->getParentDomainFromCookieDomain($cookieDomain);

        self::assertEquals($expectedParentDomain, $resultingParentDomain);
    }

    public function getInvalidCookieDomainCases(): array
    {
        return [
            ['.bultex'],
            ['.bultex.'],
            ['bultex.'],
            ['bultex'],
            ['.'],
            [''],
        ];
    }

    /**
     * @dataProvider getInvalidCookieDomainCases
     */
    public function testWithInvalidCookieDomainWillThrowAnException(string $cookieDomain): void
    {
        $this->expectException(InvalidCookieDomainException::class);

        $this->subject->getParentDomainFromCookieDomain($cookieDomain);
    }

    public function testWithAlreadyParentDomainWillThrowAnException(): void
    {
        $this->expectException(CookieDomainIsAlreadyParentDomainException::class);

        $this->subject->getParentDomainFromCookieDomain('bultex.fr');
    }
}

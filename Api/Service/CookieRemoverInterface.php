<?php

declare(strict_types=1);

namespace Blackbird\CookieDomainCleaner\Api\Service;

use Magento\Framework\Stdlib\Cookie\PublicCookieMetadata;
use Magento\Framework\Stdlib\Cookie\SensitiveCookieMetadata;

interface CookieRemoverInterface
{
    public function deletePublicCookieFromParentDomain(
        string               $name,
        ?string              $value,
        PublicCookieMetadata $metadata = null,
    ): void;

    public function deleteSensitiveCookieFromParentDomain(
        string                  $name,
        ?string                 $value,
        SensitiveCookieMetadata $metadata = null,
    ): void;
}

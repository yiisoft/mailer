<?php

declare(strict_types=1);

namespace Yiisoft\Mailer;

/**
 * @internal
 */
final class HeadersNormalizer
{
    /**
     * @psalm-param array<string,string|list<string>>|null $headers
     * @psalm-return array<string,list<string>>|null
     */
    public static function normalize(array|null $headers): array|null
    {
        if ($headers === null) {
            return null;
        }

        return array_map(
            static fn(string|array $value): array => (array) $value,
            $headers,
        );
    }
}

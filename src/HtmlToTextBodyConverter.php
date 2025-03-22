<?php

declare(strict_types=1);

namespace Yiisoft\Mailer;

use function html_entity_decode;
use function preg_match;
use function preg_replace;
use function strip_tags;

/**
 * Utility for converting HTML body to text body.
 *
 * @api
 */
final class HtmlToTextBodyConverter
{
    /**
     * Generates a text body from an HTML body.
     *
     * @param string $html The HTML body.
     *
     * @return string The text body.
     */
    public static function convert(string $html): string
    {
        if (preg_match('~<body[^>]*>(.*?)</body>~is', $html, $match)) {
            $html = $match[1];
        }

        /**
         * Remove style and script
         *
         * @var string $html We use valid regular expression, so `preg_replace()` will return string.
         */
        $html = preg_replace('~<((style|script))[^>]*>(.*?)</\1>~is', '', $html);

        // strip all HTML tags and decode HTML entities
        $text = html_entity_decode(strip_tags($html), ENT_QUOTES | ENT_HTML5);

        /**
         * Improve whitespace
         *
         * @var string $text We use valid regular expression, so `preg_replace()` will return string.
         */
        $text = preg_replace("~^[ \t]+~m", '', trim($text));

        /**
         * @var string We use valid regular expression, so `preg_replace()` will return string.
         */
        return preg_replace('~\R\R+~mu', "\n\n", $text);
    }

    public function __invoke(string $html): string
    {
        return self::convert($html);
    }
}

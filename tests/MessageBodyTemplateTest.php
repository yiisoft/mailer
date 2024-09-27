<?php

declare(strict_types=1);

namespace Yiisoft\Mailer\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use Yiisoft\Mailer\MessageBodyTemplate;

final class MessageBodyTemplateTest extends TestCase
{
    public static function setupProvider(): array
    {
        $tempDir = self::getTestFilePath() . DIRECTORY_SEPARATOR;

        return [
            ["{$tempDir}foo", 'foo', '', ''],
            ["{$tempDir}bar", 'baz', 'layouts/html', 'layouts/text'],
            ["{$tempDir}baz", 'baz', 'layouts/html.php', 'layouts/text.php'],
        ];
    }

    #[DataProvider('setupProvider')]
    public function testSetup(string $viewPath, string $htmlLayout, string $textLayout): void
    {
        $template = new MessageBodyTemplate($viewPath, $htmlLayout, $textLayout);

        $this->assertSame($viewPath, $template->getViewPath());
        $this->assertSame($htmlLayout, $template->getHtmlLayout());
        $this->assertSame($textLayout, $template->getTextLayout());
    }
}

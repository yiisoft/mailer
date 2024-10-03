<?php

declare(strict_types=1);

namespace Yiisoft\Mailer\Tests;

use LogicException;
use PHPUnit\Framework\Attributes\DataProvider;
use Yiisoft\Mailer\MessageBodyTemplate;

final class MessageBodyTemplateTest extends TestCase
{
    public static function setupProvider(): array
    {
        $tempDir = self::getTestFilePath() . DIRECTORY_SEPARATOR;

        return [
            ["{$tempDir}foo", 'foo', null, null],
            ["{$tempDir}bar", 'baz', 'layouts/html', 'layouts/text'],
            ["{$tempDir}baz", 'baz', 'layouts/html.php', 'layouts/text.php'],
        ];
    }

    #[DataProvider('setupProvider')]
    public function testSetup(string $viewPath, ?string $htmlLayout, ?string $textLayout): void
    {
        $template = new MessageBodyTemplate($viewPath, $htmlLayout, $textLayout);

        $this->assertSame($viewPath, $template->viewPath);
        $this->assertSame($htmlLayout, $template->htmlLayout);
        $this->assertSame($textLayout, $template->textLayout);
    }

    public function testViewPathAsEmptyString(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('View path must be non-empty string.');
        new MessageBodyTemplate('');
    }

    public function testHtmlLayoutAsEmptyString(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('The HTML layout view name must be non-empty string or null.');
        new MessageBodyTemplate('/', htmlLayout: '');
    }

    public function testTextLayoutAsEmptyString(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('The text layout view name must be non-empty string or null.');
        new MessageBodyTemplate('/', textLayout: '');
    }
}

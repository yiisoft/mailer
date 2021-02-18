<?php

declare(strict_types=1);

namespace Yiisoft\Mailer\Tests;

use Yiisoft\Mailer\MessageBodyTemplate;

final class MessageBodyTemplateTest extends TestCase
{
    public function setupProvider(): array
    {
        $tempDir = $this->getTestFilePath() . DIRECTORY_SEPARATOR;

        return [
            ["{$tempDir}foo", 'foo', '', ''],
            ["{$tempDir}bar", 'baz', 'layouts/html', 'layouts/text'],
            ["{$tempDir}baz", 'baz', 'layouts/html.php', 'layouts/text.php'],
        ];
    }

    /**
     * @dataProvider setupProvider
     *
     * @param string $viewPath
     * @param string $htmlLayout
     * @param string $textLayout
     */
    public function testSetup(string $viewPath, string $htmlLayout, string $textLayout): void
    {
        $template = new MessageBodyTemplate($viewPath, $htmlLayout, $textLayout);

        $this->assertSame($viewPath, $template->getViewPath());
        $this->assertSame($htmlLayout, $template->getHtmlLayout());
        $this->assertSame($textLayout, $template->getTextLayout());
    }
}

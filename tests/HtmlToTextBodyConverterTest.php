<?php

declare(strict_types=1);

namespace Yiisoft\Mailer\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Yiisoft\Mailer\HtmlToTextBodyConverter;

final class HtmlToTextBodyConverterTest extends TestCase
{
    public static function dataBase(): iterable
    {
        yield [
            <<<TEXT
            HTML view file content http://yiifresh.com/index.php?r=site%2Freset-password&token=abcdef
            TEXT,
            'HTML <b>view file</b> content <a href="http://yiifresh.com/index.php?r=site%2Freset-password&amp;token=abcdef">http://yiifresh.com/index.php?r=site%2Freset-password&amp;token=abcdef</a>',
        ];
        yield [
            <<<TEXT
            First paragraph
            second line: 'hello', "world"

            http://yiifresh.com/index.php?r=site%2Freset-password&token=abcdef

            Test Lorem ipsum...
            TEXT,
            <<<HTML
            <html><head><style type="text/css">.content{color: #112345;}</style><title>TEST</title></head>
            <body>
            <style type="text/css">.content{color: #112345;}</style>
            <p> First paragraph
                second line: &#039;hello&#039;, &quot;world&quot;

                <a href="http://yiifresh.com/index.php?r=site%2Freset-password&amp;token=abcdef">http://yiifresh.com/index.php?r=site%2Freset-password&amp;token=abcdef</a>

            </p><script type="text/javascript">alert("hi")</script>

            <p>Test Lorem ipsum...</p>
            </body>
            </html>
            HTML,
        ];
        yield [
            'Hello',
            <<<HTML
            <html>
            <head>
                <title>TEST</title>
            </head>
            <BODY>
            <p>Hello</p>
            </BODY>
            </html>
            HTML,
        ];
    }

    #[DataProvider('dataBase')]
    public function testBase(string $expectedText, string $html): void
    {
        $text = HtmlToTextBodyConverter::convert($html);

        $this->assertSame($expectedText, $text);
    }
}

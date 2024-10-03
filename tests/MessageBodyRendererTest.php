<?php

declare(strict_types=1);

namespace Yiisoft\Mailer\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use RuntimeException;
use stdClass;
use Yiisoft\Mailer\MessageBodyRenderer;
use Yiisoft\Mailer\MessageBodyTemplate;
use Yiisoft\View\View;

final class MessageBodyRendererTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $filePath = self::getTestFilePath();

        if (!file_exists($filePath)) {
            mkdir($filePath, 0777, true);
        }
    }

    public function testWithView(): void
    {
        $renderer = $this->createRenderer(self::getTestFilePath(), 'test-html', 'test-text');
        $view = clone $this->get(View::class);
        $newRenderer = $renderer->withView($view);

        $this->assertNotSame($renderer, $newRenderer);
        $this->assertSame($view, $this->getInaccessibleProperty($newRenderer, 'view'));
    }

    public function testWithTemplate(): void
    {
        $renderer = $this->createRenderer(self::getTestFilePath(), 'test-html', 'test-text');
        $template = new MessageBodyTemplate(self::getTestFilePath());
        $newRenderer = $renderer->withTemplate($template);

        $this->assertNotSame($renderer, $newRenderer);
        $this->assertSame($template, $this->getInaccessibleProperty($newRenderer, 'template'));
    }

    public function testRenderHtmlAndRenderTextWithLocale(): void
    {
        $viewPath = self::getTestFilePath();
        $renderer = $this
            ->createRenderer($viewPath)
            ->withLocale('de_DE');

        $viewName = 'test-view-locale';
        $viewFileName = $viewPath . DIRECTORY_SEPARATOR . $viewName . '.php';
        $this->saveFile($viewFileName, 'no localized');

        $viewFileName = $viewPath . DIRECTORY_SEPARATOR . 'de_DE' . DIRECTORY_SEPARATOR . $viewName . '.php';
        $this->saveFile($viewFileName, 'de_DE locale');

        $this->assertSame('de_DE locale', $renderer->renderHtml('test-view-locale'));
        $this->assertSame('de_DE locale', $renderer->renderText('test-view-locale'));
    }

    public function testRenderHtmlAndRenderTextWithoutLayouts(): void
    {
        $viewPath = self::getTestFilePath();
        $renderer = $this->createRenderer($viewPath);

        $viewName = 'test-view';
        $viewFileName = $viewPath . DIRECTORY_SEPARATOR . $viewName . '.php';
        $viewFileContent = '<?php echo $testParam; ?>';
        $this->saveFile($viewFileName, $viewFileContent);

        $this->assertSame(
            '<p>Test HTML output.</p>',
            $renderer->renderHtml($viewName, [
                'testParam' => '<p>Test HTML output.</p>',
            ])
        );

        $this->assertSame(
            'Test TEXT output.',
            $renderer->renderText($viewName, [
                'testParam' => 'Test TEXT output.',
            ])
        );
    }

    public function testRenderHtmlAndRenderTextWithLayouts(): void
    {
        $viewPath = self::getTestFilePath();
        $layoutName = 'test-layout';
        $renderer = $this->createRenderer($viewPath, $layoutName, $layoutName);

        $viewName = 'test-view';
        $viewFileName = $viewPath . DIRECTORY_SEPARATOR . $viewName . '.php';
        $viewFileContent = '<?php echo $viewParam; ?>';
        $this->saveFile($viewFileName, $viewFileContent);

        $layoutFileName = $viewPath . DIRECTORY_SEPARATOR . $layoutName . '.php';
        $layoutFileContent = 'Begin Layout <?php echo $content; ?> End <?php echo $layoutParam; ?>';
        $this->saveFile($layoutFileName, $layoutFileContent);

        $this->assertSame(
            'Begin Layout <p>Test HTML.</p> End Layout',
            $renderer->renderHtml(
                $viewName,
                ['viewParam' => '<p>Test HTML.</p>'],
                ['layoutParam' => 'Layout', 'content' => 'Replaced'],
            )
        );

        $this->assertSame(
            'Begin Layout Test TEXT. End Layout',
            $renderer->renderText(
                $viewName,
                ['viewParam' => 'Test TEXT.'],
                ['layoutParam' => 'Layout', 'content' => 'Replaced'],
            )
        );
    }

    public function testAddToMessage(): void
    {
        $viewPath = self::getTestFilePath();
        $renderer = $this->createRenderer($viewPath);
        $htmlViewName = 'test-html-view';
        $textViewName = 'test-text-view';

        $htmlViewFileName = $viewPath . DIRECTORY_SEPARATOR . $htmlViewName . '.php';
        $htmlViewFileContent = 'HTML <b>view file</b> content.';
        $this->saveFile($htmlViewFileName, $htmlViewFileContent);

        $textViewFileName = $viewPath . DIRECTORY_SEPARATOR . $textViewName . '.php';
        $textViewFileContent = 'Plain text view file content.';
        $this->saveFile($textViewFileName, $textViewFileContent);

        $message = $renderer->addToMessage(self::createMessage(), ['html' => $htmlViewName, 'text' => $textViewName]);
        $this->assertSame($htmlViewFileContent, $message->getHtmlBody(), 'Unable to render html!');
        $this->assertSame($textViewFileContent, $message->getTextBody(), 'Unable to render text!');

        $message = $renderer->addToMessage(self::createMessage(), $htmlViewName);
        $this->assertEquals($htmlViewFileContent, $message->getHtmlBody(), 'Unable to render html by direct view!');
        $this->assertEquals(
            strip_tags($htmlViewFileContent),
            $message->getTextBody(),
            'Unable to render text by direct view!'
        );
    }

    public static function htmlAndPlainProvider(): array
    {
        return [
            [
                'HTML <b>view file</b> content <a href="http://yiifresh.com/index.php?r=site%2Freset-password&amp;token=abcdef">http://yiifresh.com/index.php?r=site%2Freset-password&amp;token=abcdef</a>',
                'HTML view file content http://yiifresh.com/index.php?r=site%2Freset-password&token=abcdef',
            ],
            [
                <<<HTML
<html><head><style type="text/css">.content{color: #112345;}</style><title>TEST</title></head>
<body>
    <style type="text/css">.content{color: #112345;}</style>
    <p> First paragraph
    second line

     <a href="http://yiifresh.com/index.php?r=site%2Freset-password&amp;token=abcdef">http://yiifresh.com/index.php?r=site%2Freset-password&amp;token=abcdef</a>

     </p><script type="text/javascript">alert("hi")</script>

<p>Test Lorem ipsum...</p>
</body>
</html>
HTML,
                <<<TEXT
First paragraph
second line

http://yiifresh.com/index.php?r=site%2Freset-password&token=abcdef

Test Lorem ipsum...
TEXT
            ],
        ];
    }

    #[DataProvider('htmlAndPlainProvider')]
    public function testAddToMessagePlainTextFallback(string $htmlViewFileContent, string $expectedTextRendering): void
    {
        $viewPath = self::getTestFilePath();
        $renderer = $this->createRenderer($viewPath);
        $htmlViewName = 'test-html-view';

        $htmlViewFileName = $viewPath . DIRECTORY_SEPARATOR . $htmlViewName . '.php';
        $this->saveFile($htmlViewFileName, $htmlViewFileContent);

        $message = $renderer->addToMessage(self::createMessage(), $htmlViewName);
        $this->assertEqualsWithoutLE($htmlViewFileContent, $message->getHtmlBody(), 'Unable to render html!');
        $this->assertEqualsWithoutLE($expectedTextRendering, $message->getTextBody(), 'Unable to render text!');

        $message = $renderer->addToMessage(self::createMessage(), ['html' => $htmlViewName]);
        $this->assertEqualsWithoutLE($htmlViewFileContent, $message->getHtmlBody(), 'Unable to render html!');
        $this->assertEqualsWithoutLE($expectedTextRendering, $message->getTextBody(), 'Unable to render text!');
    }

    public static function invalidViewProvider(): array
    {
        return [
            'int' => [1],
            'float' => [1.1],
            'bool' => [true],
            'object' => [new stdClass()],
            'callable' => [static fn () => 'view'],
            'array-without-required-keys' => [['json' => 'json-view', 'xml' => 'xml-view']],
            'empty-array' => [[]],
        ];
    }

    #[DataProvider('invalidViewProvider')]
    public function testAddToMessageThrowExceptionForInvalidView(mixed $view): void
    {
        $renderer = $this->createRenderer(self::getTestFilePath());
        $this->expectException(RuntimeException::class);
        $renderer->addToMessage(self::createMessage(), $view);
    }

    public function createRenderer(
        string $viewPath,
        ?string $htmlLayout = null,
        ?string $textLayout = null,
    ): MessageBodyRenderer {
        return new MessageBodyRenderer(
            $this->get(View::class),
            new MessageBodyTemplate($viewPath, $htmlLayout, $textLayout)
        );
    }
}

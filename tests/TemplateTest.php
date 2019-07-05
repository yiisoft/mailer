<?php
namespace Yiisoft\Mailer\Tests;

use Yiisoft\Mailer\Template;
use Yiisoft\View\View;

class TemplateTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $filePath = $this->getTestFilePath();
        if (!file_exists($filePath)) {
            mkdir($filePath, 0777, true);
        }
    }

    /**
     * @dataProvider setUpData
     */
    public function testSetup($viewPath, $viewName, $htmlLayout, $textLayout)
    {
        $template = new Template($this->get(View::class), $viewPath, $viewName);
        $template->setHtmlLayout($htmlLayout);
        $template->setTextLayout($textLayout);
        $this->assertSame($viewPath, $this->getObjectPropertyValue($template, 'viewPath'));
        $this->assertSame($viewName, $this->getObjectPropertyValue($template, 'viewName'));
        $this->assertSame($textLayout, $this->getObjectPropertyValue($template, 'textLayout'));
        $this->assertSame($textLayout, $this->getObjectPropertyValue($template, 'textLayout'));
    }

    public function setUpData()
    {
        return [
            ['/tmp/foo', 'bar', '', ''],
            ['/tmp/bar', 'baz', 'layouts/html', 'layouts/text'],
            ['/tmp/bar', ['html' => 'html', 'text' => 'text'], 'layouts/html', 'layouts/text'],
        ];
    }

    /**
     * @return Template
     */
    public function createTemplate($viewPath, $viewName)
    {
        $template = new Template($this->get(View::class), $viewPath, $viewName);

        return $template;
    }

    public function testRender()
    {
        $viewPath = $this->getTestFilePath(); 
        $viewName = 'test_view';
        $template = $this->createTemplate($viewPath, $viewName);
        $viewFileName = $viewPath . DIRECTORY_SEPARATOR . $viewName . '.php';
        $viewFileContent = '<?php echo $testParam; ?>';
        $this->saveFile($viewFileName, $viewFileContent);

        $parameters = [
            'testParam' => 'test output'
        ];
        $renderResult = $template->render($viewName, $parameters);
        $this->assertEquals($parameters['testParam'], $renderResult);
    }

    /**
     * @depends testRender
     */
    public function testRenderLayout()
    {

        $viewPath = $this->getTestFilePath();

        $viewName = 'test_view2';
        $template = $this->createTemplate($viewPath, $viewName);
        $viewFileName = $viewPath . DIRECTORY_SEPARATOR . $viewName . '.php';
        $viewFileContent = 'view file content';
        $this->saveFile($viewFileName, $viewFileContent);

        $layoutName = 'test_layout';
        $layoutFileName = $viewPath . DIRECTORY_SEPARATOR . $layoutName . '.php';
        $layoutFileContent = 'Begin Layout <?php echo $content; ?> End Layout';
        $this->saveFile($layoutFileName, $layoutFileContent);

        $renderResult = $template->render($viewName, [], $layoutName);
        $this->assertEquals('Begin Layout ' . $viewFileContent . ' End Layout', $renderResult);
    }

    /**
     * @depends testRenderLayout
     */
    public function testCompose()
    {
        $viewPath = $this->getTestFilePath();
        $htmlViewName = 'test_html_view';
        $textViewName = 'test_text_view';
        $viewName = [
            'html' => $htmlViewName,
            'text' => $textViewName,
        ];
        $template = $this->createTemplate($viewPath, $viewName);

        $template->setHtmlLayout('');
        $template->setTextLayout('');

        $htmlViewFileName = $viewPath . DIRECTORY_SEPARATOR . $htmlViewName . '.php';
        $htmlViewFileContent = 'HTML <b>view file</b> content';
        $this->saveFile($htmlViewFileName, $htmlViewFileContent);

        $textViewFileName = $viewPath . DIRECTORY_SEPARATOR . $textViewName . '.php';
        $textViewFileContent = 'Plain text view file content';
        $this->saveFile($textViewFileName, $textViewFileContent);

        $message = $this->createMessage();
        $template->compose($message);
        $this->assertEquals($htmlViewFileContent, $message->getHtmlBody(), 'Unable to render html!');
        $this->assertEquals($textViewFileContent, $message->getTextBody(), 'Unable to render text!');

        $message = $this->createMessage();
        $template2 = $this->createTemplate($viewPath, $htmlViewName);
        $template2->compose($message);
        $this->assertEquals($htmlViewFileContent, $message->getHtmlBody(), 'Unable to render html by direct view!');
        $this->assertEquals(strip_tags($htmlViewFileContent), $message->getTextBody(), 'Unable to render text by direct view!');
    }

    public function htmlAndPlainProvider()
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
HTML
                ,<<<TEXT
First paragraph
second line

http://yiifresh.com/index.php?r=site%2Freset-password&token=abcdef

Test Lorem ipsum...
TEXT
            ],
        ];
    }

    /**
     * @dataProvider htmlAndPlainProvider
     * @depends testCompose
     *
     * @param string $htmlViewFileContent
     * @param string $expectedTextRendering
     */
    public function testComposePlainTextFallback($htmlViewFileContent, $expectedTextRendering)
    {
        $viewPath = $this->getTestFilePath();
        $htmlViewName = 'test_html_view';
        $template = $this->createTemplate($viewPath, $htmlViewName);

        $htmlViewFileName = $viewPath . DIRECTORY_SEPARATOR . $htmlViewName . '.php';
        $this->saveFile($htmlViewFileName, $htmlViewFileContent);

        $message = $this->createMessage();
        $template->compose($message);
        $this->assertEqualsWithoutLE($htmlViewFileContent, $message->getHtmlBody(), 'Unable to render html!');
        $this->assertEqualsWithoutLE($expectedTextRendering, $message->getTextBody(), 'Unable to render text!');
    }
}

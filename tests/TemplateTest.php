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
     * @var Template
     */
    public function createTemplate()
    {
        $template = new Template();
        $template->viewPath = $this->getTestFilePath();
        $template->view = $this->get(View::class);

        return $template;
    }

    public function testRender()
    {
        $template = $this->createTemplate();

        $viewName = 'test_view';
        $viewFileName = $this->getTestFilePath() . DIRECTORY_SEPARATOR . $viewName . '.php';
        $viewFileContent = '<?php echo $testParam; ?>';
        $this->saveFile($viewFileName, $viewFileContent);

        $params = [
            'testParam' => 'test output'
        ];
        $renderResult = $template->render($viewName, $params);
        $this->assertEquals($params['testParam'], $renderResult);
    }

    /**
     * @depends testRender
     */
    public function testRenderLayout()
    {
        $template = $this->createTemplate();

        $filePath = $this->getTestFilePath();

        $viewName = 'test_view2';
        $viewFileName = $filePath . DIRECTORY_SEPARATOR . $viewName . '.php';
        $viewFileContent = 'view file content';
        $this->saveFile($viewFileName, $viewFileContent);

        $layoutName = 'test_layout';
        $layoutFileName = $filePath . DIRECTORY_SEPARATOR . $layoutName . '.php';
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
        $template = $this->createTemplate();

        $template->htmlLayout = false;
        $template->textLayout = false;

        $htmlViewName = 'test_html_view';
        $htmlViewFileName = $this->getTestFilePath() . DIRECTORY_SEPARATOR . $htmlViewName . '.php';
        $htmlViewFileContent = 'HTML <b>view file</b> content';
        $this->saveFile($htmlViewFileName, $htmlViewFileContent);

        $textViewName = 'test_text_view';
        $textViewFileName = $this->getTestFilePath() . DIRECTORY_SEPARATOR . $textViewName . '.php';
        $textViewFileContent = 'Plain text view file content';
        $this->saveFile($textViewFileName, $textViewFileContent);

        $template->viewName = [
            'html' => $htmlViewName,
            'text' => $textViewName,
        ];
        $message = new TestMessage();
        $template->compose($message);
        $this->assertEquals($htmlViewFileContent, $message->htmlBody, 'Unable to render html!');
        $this->assertEquals($textViewFileContent, $message->textBody, 'Unable to render text!');

        $message = new TestMessage();
        $template->viewName = $htmlViewName;
        $template->compose($message);
        $this->assertEquals($htmlViewFileContent, $message->htmlBody, 'Unable to render html by direct view!');
        $this->assertEquals(strip_tags($htmlViewFileContent), $message->textBody, 'Unable to render text by direct view!');
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
        $template = $this->createTemplate();

        $htmlViewName = 'test_html_view';
        $htmlViewFileName = $this->getTestFilePath() . DIRECTORY_SEPARATOR . $htmlViewName . '.php';
        $this->saveFile($htmlViewFileName, $htmlViewFileContent);

        $template->viewName = $htmlViewName;

        $message = new TestMessage();
        $template->compose($message);
        $this->assertEqualsWithoutLE($htmlViewFileContent, $message->htmlBody, 'Unable to render html!');
        $this->assertEqualsWithoutLE($expectedTextRendering, $message->textBody, 'Unable to render text!');
    }
}

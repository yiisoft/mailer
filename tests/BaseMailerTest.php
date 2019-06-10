<?php
namespace Yiisoft\Mailer\Tests;

use Psr\EventDispatcher\ListenerProviderInterface;
use Yiisoft\EventDispatcher\Provider\Provider;
use Yiisoft\Mailer\{Composer, MessageInterface, Event\BeforeSend};
use Yiisoft\View\View;

class BaseMailerTest extends TestCase
{
    public function testCompose()
    {
        $mailer = $this->getMailer();
        $message = $mailer->compose();
        $this->assertInstanceOf(MessageInterface::class, $message);
    }

    public function testComposeWithView()
    {
        $mailer = $this->getMailer();
        $viewPath = $this->getTestFilePath();

        $composer = $mailer->getComposer();
        $composer->setViewPath($viewPath);
        $composer->htmlLayout = false;
        $composer->textLayout = false;

        $htmlViewName = 'test_html_view';
        $htmlViewFileName = $viewPath . DIRECTORY_SEPARATOR . $htmlViewName . '.php';
        $htmlViewFileContent = 'HTML <b>view file</b> content';
        $this->saveFile($htmlViewFileName, $htmlViewFileContent);

        $textViewName = 'test_text_view';
        $textViewFileName = $viewPath . DIRECTORY_SEPARATOR . $textViewName . '.php';
        $textViewFileContent = 'Plain text view file content';
        $this->saveFile($textViewFileName, $textViewFileContent);

        $message = $mailer->compose([
            'html' => $htmlViewName,
            'text' => $textViewName,
        ]);
        $this->assertEquals($htmlViewFileContent, $message->htmlBody, 'Unable to render html!');
        $this->assertEquals($textViewFileContent, $message->textBody, 'Unable to render text!');

        $message = $mailer->compose($htmlViewName);
        $this->assertEquals($htmlViewFileContent, $message->htmlBody, 'Unable to render html by direct view!');
        $this->assertEquals(strip_tags($htmlViewFileContent), $message->textBody, 'Unable to render text by direct view!');
    }

    public function testSetComposer()
    {
        $composer = new Composer($this->get(View::class), '/tmp/views');
        $mailer = $this->getMailer();
        $mailer->setComposer($composer);
        $this->assertEquals($composer, $mailer->getComposer());
    }

    public function testUseFileTransport()
    {
        $mailer = $this->getMailer();
        $this->assertFalse($mailer->useFileTransport);
        $this->assertEquals('/tmp/mail', $mailer->fileTransportPath);

        $mailer->fileTransportPath = '/tmp/mail';
        $mailer->useFileTransport = true;
        $mailer->fileTransportCallback = function () {
            return 'message.txt';
        };
        $message = $mailer->compose()
            ->setTo('to@example.com')
            ->setFrom('from@example.com')
            ->setSubject('test subject')
            ->setTextBody('text body' . microtime(true));
        $this->assertTrue($mailer->send($message));
        $file = $mailer->fileTransportPath . '/message.txt';
        $this->assertTrue(is_file($file));
        $this->assertEquals($message->toString(), file_get_contents($file));
    }

    /**
     * @dataProvider messagesProvider
     */
    public function testSendMultiple($messages)
    {
        $mailer = $this->getMailer();
        $this->assertSame(count($messages), $mailer->sendMultiple($messages));
    }

    public function messagesProvider()
    {
        return [
            [[]],
            [[new TestMessage()]],
            [[new TestMessage(), new TestMessage()]],
        ];
    }

    public function testBeforeSend()
    {
        $mailer = $this->getMailer();
        $this->assertTrue($mailer->beforeSend(new TestMessage()));

        /** @var Provider $provider */
        $provider = $this->get(ListenerProviderInterface::class);
        $provider->attach(function(BeforeSend $event) {
            $event->stopPropagation();
        });
        $this->assertFalse($mailer->beforeSend(new TestMessage()));
    }
}

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
        $composer->setHtmlLayout('');
        $composer->setTextayout('');

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
        $this->assertEquals($htmlViewFileContent, $message->getHtmlBody(), 'Unable to render html!');
        $this->assertEquals($textViewFileContent, $message->getTextBody(), 'Unable to render text!');

        $message = $mailer->compose($htmlViewName);
        $this->assertEquals($htmlViewFileContent, $message->getHtmlBody(), 'Unable to render html by direct view!');
        $this->assertEquals(strip_tags($htmlViewFileContent), $message->getTextBody(), 'Unable to render text by direct view!');
    }

    public function testUseFileTransport()
    {
        $mailer = $this->getMailer();
        $this->assertFalse($mailer->getUseFileTransport());

        $fileTransportPath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'mail';
        $mailer->setFileTransportPath($fileTransportPath);
        $this->assertSame($fileTransportPath, $mailer->getFileTransportPath());

        $mailer->setUseFileTransport(true);
        $this->assertTrue($mailer->getUseFileTransport());
        $message = $mailer->compose()
            ->setTo('to@example.com')
            ->setFrom('from@example.com')
            ->setSubject('test subject')
            ->setTextBody('text body' . microtime(true));
        $this->assertTrue($mailer->send($message));
        $file = $fileTransportPath . DIRECTORY_SEPARATOR . $mailer->lastTransportFilename;
        $this->assertTrue(is_file($file));
        $this->assertEquals($message->toString(), file_get_contents($file));
    }

    public function testUseFileTransportWithCallback()
    {
        $mailer = $this->getMailer();
        $this->assertFalse($mailer->getUseFileTransport());

        $fileTransportPath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'mail';
        $mailer->setFileTransportPath($fileTransportPath);
        $this->assertSame($fileTransportPath, $mailer->getFileTransportPath());

        $mailer->setUseFileTransport(true);
        $this->assertTrue($mailer->getUseFileTransport());
        $filename = md5(uniqid()) . '.txt';
        $mailer->setFileTransportCallback(function () use ($filename) {
            return $filename;
        });
        $message = $mailer->compose()
            ->setTo([
                'foo@example.com',
                'bar@example.com',
            ])
            ->setFrom('from@example.com')
            ->setSubject('test subject')
            ->setTextBody('text body' . microtime(true));
        $this->assertTrue($mailer->send($message));
        $file = $fileTransportPath . DIRECTORY_SEPARATOR . $filename;
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
;    }

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
        $this->assertFalse($mailer->send(new TestMessage()));
    }
}

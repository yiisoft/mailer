<?php

declare(strict_types=1);

namespace Yiisoft\Mailer\Tests;

use InvalidArgumentException;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Yiisoft\Mailer\Composer;
use Yiisoft\Mailer\Event\BeforeSend;
use Yiisoft\Mailer\MessageFactoryInterface;
use Yiisoft\Mailer\MessageInterface;

class BaseMailerTest extends TestCase
{
    public function testCompose(): void
    {
        $mailer = $this->getMailer();
        $message = $mailer->compose();
        $this->assertInstanceOf(MessageInterface::class, $message);
    }

    public function testComposeWithView(): void
    {
        $mailer = $this->getMailer();
        $viewPath = $this->getTestFilePath();

        $composer = $mailer->getComposer();
        $composer->setViewPath($viewPath);
        $composer->setHtmlLayout('');
        $composer->setTextLayout('');

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

    /**
     * @dataProvider messagesProvider
     */
    public function testSendMultiple(array $messages): void
    {
        $mailer = $this->getMailer();
        $this->assertCount(0, $mailer->sendMultiple($messages));
    }

    public function messagesProvider(): array
    {
        return [
            [[]],
            [[$this->createMessage('foo')]],
            [[$this->createMessage('bar'), $this->createMessage('baz')]],
        ];
    }

    public function testSendMultipleExceptions(): void
    {
        $mailer = $this->getMailer();
        $messages = [$this->createMessage(''), $this->createMessage()];
        /** @var MessageInterface[] $failed */
        $failed = $mailer->sendMultiple($messages);
        $this->assertCount(1, $failed);
        $this->assertEquals($messages[0], $failed[0]);
        $this->assertEquals(new InvalidArgumentException("Message's subject is required"), $failed[0]->getError());
    }

    public function testBeforeSend(): void
    {
        $message = $this->createMock(MessageInterface::class);
        $event = new BeforeSend($message);
        $messageFactory = $this->createMock(MessageFactoryInterface::class);
        $composer = $this->createMock(Composer::class);
        $logger = $this->createMock(LoggerInterface::class);
        $eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $eventDispatcher->method('dispatch')->willReturn($event);
        $mailer = new TestMailer($messageFactory, $composer, $eventDispatcher, $logger, '');

        $this->assertTrue($mailer->beforeSend($message));
        $event->stopPropagation();
        $this->assertFalse($mailer->beforeSend($message));
        $mailer->send($message);
    }
}

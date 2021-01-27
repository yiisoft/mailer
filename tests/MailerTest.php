<?php

declare(strict_types=1);

namespace Yiisoft\Mailer\Tests;

use InvalidArgumentException;
use Psr\EventDispatcher\EventDispatcherInterface;
use Yiisoft\Mailer\Event\BeforeSend;
use Yiisoft\Mailer\MailerInterface;
use Yiisoft\Mailer\MessageBodyRenderer;
use Yiisoft\Mailer\MessageFactoryInterface;
use Yiisoft\Mailer\MessageInterface;
use Yiisoft\Mailer\Tests\TestAsset\DummyMailer;

use function strip_tags;

final class MailerTest extends TestCase
{
    public function testCompose(): void
    {
        $mailer = $this->get(MailerInterface::class);
        $message = $mailer->compose();
        $this->assertInstanceOf(MessageInterface::class, $message);
    }

    public function testComposeWithView(): void
    {
        $mailer = $this->get(MailerInterface::class);
        $viewPath = $this->getTestFilePath();

        $htmlViewName = 'test-html-view';
        $htmlViewFileName = $viewPath . DIRECTORY_SEPARATOR . $htmlViewName . '.php';
        $htmlViewFileContent = 'HTML <b>view file</b> content.';
        $this->saveFile($htmlViewFileName, $htmlViewFileContent);

        $textViewName = 'test-text-view';
        $textViewFileName = $viewPath . DIRECTORY_SEPARATOR . $textViewName . '.php';
        $textViewFileContent = 'Plain text view file content.';
        $this->saveFile($textViewFileName, $textViewFileContent);

        $message = $mailer->compose([
            'html' => $htmlViewName,
            'text' => $textViewName,
        ]);
        $this->assertSame($htmlViewFileContent, $message->getHtmlBody(), 'Unable to render html!');
        $this->assertSame($textViewFileContent, $message->getTextBody(), 'Unable to render text!');

        $message = $mailer->compose($htmlViewName);
        $this->assertSame($htmlViewFileContent, $message->getHtmlBody(), 'Unable to render html by direct view!');
        $this->assertSame(strip_tags($htmlViewFileContent), $message->getTextBody(), 'Unable to render text by direct view!');
    }

    /**
     * @dataProvider messagesProvider
     *
     * @param array $messages
     */
    public function testSendMultiple(array $messages): void
    {
        $mailer = $this->get(MailerInterface::class);
        $this->assertCount(0, $mailer->sendMultiple($messages));
        $this->assertSame($messages, $mailer->sentMessages);
    }

    public function messagesProvider(): array
    {
        return [
            [[]],
            [[$this->createMessage()]],
            [[$this->createMessage('bar'), $this->createMessage('baz')]],
        ];
    }

    public function testSendMultipleExceptions(): void
    {
        $mailer = $this->get(MailerInterface::class);
        $messages = [$this->createMessage(''), $this->createMessage()];
        $failed = $mailer->sendMultiple($messages);

        $this->assertCount(1, $failed);
        $this->assertInstanceOf(InvalidArgumentException::class, $failed[0]->getError());
        $this->assertSame("Message's subject is required.", $failed[0]->getError()->getMessage());
    }

    public function testBeforeSend(): void
    {
        $message = $this->createMock(MessageInterface::class);
        $event = new BeforeSend($message);
        $messageFactory = $this->createMock(MessageFactoryInterface::class);
        $messageBodyRenderer = $this->get(MessageBodyRenderer::class);
        $eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $eventDispatcher->method('dispatch')->willReturn($event);
        $mailer = new DummyMailer($messageFactory, $messageBodyRenderer, $eventDispatcher);

        $this->assertTrue($mailer->beforeSend($message));
        $event->stopPropagation();
        $this->assertFalse($mailer->beforeSend($message));

        $this->assertSame([], $mailer->sentMessages);
        $mailer->send($message);
        $this->assertSame([], $mailer->sentMessages);
    }
}

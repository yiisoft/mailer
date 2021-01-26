<?php

declare(strict_types=1);

namespace Yiisoft\Mailer\Tests;

use Psr\EventDispatcher\EventDispatcherInterface;
use RuntimeException;
use stdClass;
use Yiisoft\Mailer\FileMailer;
use Yiisoft\Mailer\MessageBodyRenderer;
use Yiisoft\Mailer\MessageFactoryInterface;
use Yiisoft\Mailer\MessageInterface;

use function file_get_contents;
use function glob;
use function is_file;
use function microtime;

final class FileMailerTest extends TestCase
{
    public function testSend(): void
    {
        $mailer = $this->createFileMailer();

        $message = $mailer->compose()
            ->withTo('to@example.com')
            ->withFrom('from@example.com')
            ->withSubject('test subject')
            ->withTextBody('text body' . microtime(true));

        $mailer->send($message);
        $pattern = $this->getTestFilePath() . DIRECTORY_SEPARATOR . '*.eml';

        foreach (glob($pattern) as $file) {
            $this->assertTrue(is_file($file));
            $this->assertSame((string) $message, file_get_contents($file));
        }
    }

    public function filenameCallbackProvider(): array
    {
        $time = microtime(true);

        return [
            'without-message' => [static fn () => "test-file-{$time}.txt", "test-file-{$time}.txt"],
            'with-message' => [
                static fn (MessageInterface $message) => "{$message->getFrom()}-{$time}.txt",
                "from@example.com-{$time}.txt",
            ],
        ];
    }

    /**
     * @dataProvider filenameCallbackProvider
     *
     * @param callable $filenameCallback
     * @param string $filenameExpected
     */
    public function testSendWithFilenameCallback(callable $filenameCallback, string $filenameExpected): void
    {
        $mailer = $this->createFileMailer($filenameCallback);

        $message = $mailer->compose()
            ->withTo('to@example.com')
            ->withFrom('from@example.com')
            ->withSubject('test subject')
            ->withTextBody('text body' . microtime(true));

        $mailer->send($message);
        $pattern = $this->getTestFilePath() . DIRECTORY_SEPARATOR . $filenameExpected;

        foreach (glob($pattern) as $file) {
            $this->assertTrue(is_file($file));
            $this->assertEquals((string) $message, file_get_contents($file));
        }
    }

    public function invalidFilenameCallbackProvider(): array
    {
        return [
            'int' => [static fn () => 1],
            'float' => [static fn () => 1,1],
            'bool' => [static fn () => true],
            'array' => [static fn () => []],
            'object' => [static fn () => new stdClass()],
            'callable' => [static fn () => static fn () => 'string'],
        ];
    }

    /**
     * @dataProvider invalidFilenameCallbackProvider
     *
     * @param callable $filenameCallback
     */
    public function testSendThrowExceptionForFilenameCallbackReturnNotString(callable $filenameCallback): void
    {
        $mailer = $this->createFileMailer($filenameCallback);
        $this->expectException(RuntimeException::class);
        $mailer->send($this->createMessage());
    }

    private function createFileMailer(callable $filenameCallback = null): FileMailer
    {
        return new FileMailer(
            $this->get(MessageFactoryInterface::class),
            $this->get(MessageBodyRenderer::class),
            $this->get(EventDispatcherInterface::class),
            $this->getTestFilePath(),
            $filenameCallback,
        );
    }
}

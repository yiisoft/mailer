<?php

declare(strict_types=1);

namespace Yiisoft\Mailer\Tests;

use Closure;
use PHPUnit\Framework\Attributes\DataProvider;
use Psr\EventDispatcher\EventDispatcherInterface;
use RuntimeException;
use stdClass;
use Yiisoft\Mailer\Event\AfterSend;
use Yiisoft\Mailer\Event\BeforeSend;
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

        $message = $mailer
            ->compose()
            ->withTo('to@example.com')
            ->withFrom('from@example.com')
            ->withSubject('test subject')
            ->withTextBody('text body' . microtime(true));

        $mailer->send($message);
        $files = glob(self::getTestFilePath() . DIRECTORY_SEPARATOR . '*.eml');

        $this->assertNotEmpty($files);
        $this->assertSame(
            [BeforeSend::class, AfterSend::class],
            $this
                ->get(EventDispatcherInterface::class)
                ->getEventClasses(),
        );

        foreach ($files as $file) {
            $this->assertTrue(is_file($file));
            $this->assertSame((string) $message, file_get_contents($file));
        }
    }

    public static function filenameCallbackProvider(): array
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

    #[DataProvider('filenameCallbackProvider')]
    public function testSendWithFilenameCallback(callable $filenameCallback, string $filenameExpected): void
    {
        $mailer = $this->createFileMailer($filenameCallback);

        $message = $mailer
            ->compose()
            ->withTo('to@example.com')
            ->withFrom('from@example.com')
            ->withSubject('test subject')
            ->withTextBody('text body' . microtime(true));

        $mailer->send($message);
        $files = glob(self::getTestFilePath() . DIRECTORY_SEPARATOR . $filenameExpected);

        $this->assertNotEmpty($files);
        $this->assertSame(
            [BeforeSend::class, AfterSend::class],
            $this
                ->get(EventDispatcherInterface::class)
                ->getEventClasses(),
        );

        foreach ($files as $file) {
            $this->assertTrue(is_file($file));
            $this->assertEquals((string) $message, file_get_contents($file));
        }
    }

    public static function invalidFilenameCallbackProvider(): array
    {
        return [
            'int' => [static fn (): int => 1],
            'float' => [static fn (): float => 1,1],
            'bool' => [static fn (): bool => true],
            'array' => [static fn (): array => []],
            'object' => [static fn (): stdClass => new stdClass()],
            'callable' => [static fn (): Closure => static fn () => 'string'],
        ];
    }

    #[DataProvider('invalidFilenameCallbackProvider')]
    public function testSendThrowExceptionForFilenameCallbackReturnNotString(callable $filenameCallback): void
    {
        $mailer = $this->createFileMailer($filenameCallback);
        $this->expectException(RuntimeException::class);
        $mailer->send(self::createMessage());
    }

    private function createFileMailer(callable $filenameCallback = null): FileMailer
    {
        return new FileMailer(
            $this->get(MessageFactoryInterface::class),
            $this->get(MessageBodyRenderer::class),
            self::getTestFilePath(),
            $filenameCallback,
            $this->get(EventDispatcherInterface::class),
        );
    }
}

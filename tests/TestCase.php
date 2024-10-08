<?php

declare(strict_types=1);

namespace Yiisoft\Mailer\Tests;

use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use ReflectionClass;
use Yiisoft\Files\FileHelper;
use Yiisoft\Mailer\MailerInterface;
use Yiisoft\Mailer\Message;
use Yiisoft\Mailer\MessageInterface;
use Yiisoft\Mailer\Tests\Support\DummyMailer;
use Yiisoft\Test\Support\Container\SimpleContainer;
use Yiisoft\Test\Support\EventDispatcher\SimpleEventDispatcher;

use function basename;
use function dirname;
use function file_put_contents;
use function is_dir;
use function mkdir;
use function str_replace;
use function sys_get_temp_dir;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    private ?ContainerInterface $container = null;

    protected function setUp(): void
    {
        FileHelper::ensureDirectory(self::getTestFilePath());
        $this->getContainer();
    }

    protected function tearDown(): void
    {
        $this->container = null;
        FileHelper::removeDirectory(self::getTestFilePath());
    }

    /**
     * @template T
     * @param class-string<T> $id
     * @return T
     */
    protected function get(string $id)
    {
        return $this->getContainer()->get($id);
    }

    protected static function createMessage(
        string $subject = 'foo',
        string $from = 'from@example.com',
        string $to = 'to@example.com'
    ): MessageInterface {
        return new Message(from: $from, to: $to, subject: $subject);
    }

    /**
     * Asserting two strings equality ignoring line endings.
     */
    protected function assertEqualsWithoutLE(string $expected, string $actual, string $message = ''): void
    {
        $expected = str_replace("\r\n", "\n", $expected);
        $actual = str_replace("\r\n", "\n", $actual);
        $this->assertEquals($expected, $actual, $message);
    }

    protected static function getTestFilePath(): string
    {
        return sys_get_temp_dir()
            . DIRECTORY_SEPARATOR
            . basename(str_replace('\\', '_', static::class))
        ;
    }

    protected function saveFile(string $filename, string $data): void
    {
        $path = dirname($filename);

        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }

        file_put_contents($filename, $data);
    }

    /**
     * Gets an inaccessible object property.
     *
     * @return mixed
     */
    protected function getInaccessibleProperty(object $object, string $propertyName)
    {
        $class = new ReflectionClass($object);

        while (!$class->hasProperty($propertyName)) {
            $class = $class->getParentClass();
        }

        $property = $class->getProperty($propertyName);
        $property->setAccessible(true);
        $result = $property->getValue($object);
        $property->setAccessible(false);

        return $result;
    }

    private function getContainer(): ContainerInterface
    {
        if ($this->container === null) {
            $eventDispatcher = new SimpleEventDispatcher();
            $this->container = new SimpleContainer([
                EventDispatcherInterface::class => $eventDispatcher,
                MailerInterface::class => new DummyMailer(eventDispatcher: $eventDispatcher),
            ]);
        }

        return $this->container;
    }
}

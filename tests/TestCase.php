<?php
namespace Yiisoft\Mailer\Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;
use Psr\Container\ContainerInterface;
use Yiisoft\Mailer\MailerInterface;
use Yiisoft\Mailer\MessageInterface;
use Yiisoft\Di\Container;

abstract class TestCase extends BaseTestCase
{
    /**
     * @var ContainerInterface $container
     */
    private $container;

    protected function setUp()
    {
        parent::setUp();

        $config = require __DIR__ . '/config.php';
        $this->container = new Container($config);
    }

    protected function tearDown()
    {
        $this->container = null;

        parent::tearDown();
    }

    protected function get($id)
    {
        return $this->container->get($id);
    }

    /**
     * @return TestMailer mailer instance.
     */
    protected function getMailer(): TestMailer
    {
        return $this->get(MailerInterface::class);
    }

    /**
     * Creates a new message instance.
     * @return MessageInterface
     */
    protected function createMessage(string $subject = 'foo', string $from = 'from@example.com', string $to = 'to@example.com'): MessageInterface
    {
        return (new TestMessage())
            ->setSubject($subject)
            ->setFrom($from)
            ->setTo($to);
    }

    /**
     * Asserting two strings equality ignoring line endings.
     * @param string $expected
     * @param string $actual
     * @param string $message
     */
    protected function assertEqualsWithoutLE(string $expected, string $actual, string $message = ''): void
    {
        $expected = str_replace("\r\n", "\n", $expected);
        $actual = str_replace("\r\n", "\n", $actual);
        $this->assertEquals($expected, $actual, $message);
    }

    /**
     * @return string test file path.
     */
    protected function getTestFilePath(): string
    {
        return sys_get_temp_dir() . DIRECTORY_SEPARATOR . basename(str_replace('\\', '_', get_class($this))) . '_' . getmypid();
    }

    protected function saveFile(string $filename, string $data): void
    {
        $path = dirname($filename);
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }

        file_put_contents($filename, $data);
    }

    protected function getObjectPropertyValue($obj, string $name)
    {
        $property = new \ReflectionProperty(get_class($obj), $name);
        $property->setAccessible(true);
        return $property->getValue($obj);
    }
}

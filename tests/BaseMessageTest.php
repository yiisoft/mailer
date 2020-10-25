<?php

namespace Yiisoft\Mailer\Tests;

class BaseMessageTest extends TestCase
{
    public function testSend(): void
    {
        $mailer = $this->getMailer();
        $message = $mailer->compose()->setSubject('foo');
        $message->send();
        $this->assertEquals($message, $mailer->sentMessages[0], 'Unable to send message!');
    }

    public function testToString(): void
    {
        $mailer = $this->getMailer();
        $message = $mailer->compose();
        $this->assertEquals($message->toString(), '' . $message);
    }

    public function testMagicToString(): void
    {
        set_error_handler([$this, 'errorHandler'], E_USER_ERROR);

        $errstr = 'Unsupported __toString';
        $message = new class($errstr) extends TestMessage {
            private $errstr;

            public function __construct($errstr)
            {
                $this->errstr = $errstr;
            }

            public function toString(): string
            {
                throw new \Exception($this->errstr);
                return '';
            }
        };
        $this->assertEquals('', strval($message));
        $this->assertCount(1, $this->errors);
        $this->assertEquals(E_USER_ERROR, $this->errors[0]['errno']);
        $this->assertStringContainsString($errstr, $this->errors[0]['errstr']);
    }

    private $errors;

    public function errorHandler(int $errno, string $errstr, string $errfile, int $errline, array $errcontext = []): bool
    {
        $this->errors[] = [
            'errno' => $errno,
            'errstr' => $errstr,
        ];

        return true;
    }
}

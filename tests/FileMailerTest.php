<?php

namespace Yiisoft\Mailer\Tests;

class FileMailerTest extends TestCase
{
    public function testSend(): void
    {
        $mailer = $this->getMailer();

        $path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'mails';
        $mailer->setPath($path);
        $this->assertSame($path, $mailer->getPath());

        $message = $mailer->compose()
            ->setTo('to@example.com')
            ->setFrom('from@example.com')
            ->setSubject('test subject')
            ->setTextBody('text body' . microtime(true));
        $mailer->send($message);
        $file = $path . DIRECTORY_SEPARATOR . $mailer->lastFilename;
        $this->assertTrue(is_file($file));
        $this->assertEquals($message->toString(), file_get_contents($file));
    }

    public function testFilenameCallback(): void
    {
        $mailer = $this->getMailer();

        $path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'mails';
        $mailer->setPath($path);
        $this->assertSame($path, $mailer->getPath());

        $filename = date('Ymd') . DIRECTORY_SEPARATOR . md5(uniqid()) . '.txt';
        $mailer->setFilenameCallback(function () use ($filename) {
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
        $mailer->send($message);
        $file = $path . DIRECTORY_SEPARATOR . $filename;
        $this->assertTrue(is_file($file));
        $this->assertEquals($message->toString(), file_get_contents($file));
    }
}

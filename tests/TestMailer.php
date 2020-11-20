<?php

declare(strict_types=1);

namespace Yiisoft\Mailer\Tests;

use InvalidArgumentException;
use Yiisoft\Mailer\FileMailer;
use Yiisoft\Mailer\MessageInterface;

class TestMailer extends FileMailer
{
    public $sentMessages = [];
    public $lastFilename;

    protected function sendMessage(MessageInterface $message): void
    {
        if (empty($message->getSubject())) {
            throw new InvalidArgumentException("Message's subject is required");
        }

        parent::sendMessage($message);
        $this->sentMessages[] = $message;
    }

    public function beforeSend(MessageInterface $message): bool
    {
        return parent::beforeSend($message);
    }

    protected function generateMessageFilename(): string
    {
        $filename = parent::generateMessageFilename();
        $this->lastFilename = $filename;
        return $filename;
    }
}

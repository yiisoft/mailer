<?php
namespace Yiisoft\Mailer\Tests;

use InvalidArgumentException;
use Yiisoft\Mailer\{FileMailer, MessageInterface};

class TestMailer extends FileMailer
{
    public $sentMessages = [];

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

    public $lastFilename;

    protected function generateMessageFilename(): string
    {
        $filename = parent::generateMessageFilename();
        $this->lastFilename = $filename;
        return $filename;
    }
}

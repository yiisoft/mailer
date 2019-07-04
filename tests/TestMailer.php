<?php
namespace Yiisoft\Mailer\Tests;

use InvalidArgumentException;
use Yiisoft\Mailer\{FileMailer, MessageInterface};

class TestMailer extends FileMailer
{
    public $sentMessages = [];

    /**
     * {@inheritdoc}
     */    
    protected function sendMessage(MessageInterface $message): void
    {
        if (empty($message->getSubject())) {
            throw new InvalidArgumentException("Message's subject is required");
        }

        parent::sendMessage($message);
        $this->sentMessages[] = $message;
    }

    /**
     * {@inheritdoc}
     */    
    public function beforeSend(MessageInterface $message): bool
    {
        return parent::beforeSend($message);
    }

    public $lastFilename;

    protected function generateMessageFileName(): string
    {
        $filename = parent::generateMessageFileName();
        $this->lastFilename = $filename;
        return $filename;
    }
}

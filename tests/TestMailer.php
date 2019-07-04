<?php
namespace Yiisoft\Mailer\Tests;

use Yiisoft\Mailer\{FileMailer, MessageInterface};

class TestMailer extends FileMailer
{
    public $sentMessages = [];

    /**
     * {@inheritdoc}
     */    
    protected function sendMessage(MessageInterface $message): bool
    {
        $isSuccessful = parent::sendMessage($message);
        $this->sentMessages[] = $message;
        return $isSuccessful;
    }

    /**
     * {@inheritdoc}
     */    
    public function beforeSend(MessageInterface $message): bool
    {
        return parent::beforeSend($message);
    }

    public $lastTransportFilename;

    protected function generateMessageFileName(): string
    {
        $filename = parent::generateMessageFileName();
        $this->lastTransportFilename = $filename;
        return $filename;
    }
}

<?php
namespace Yiisoft\Mailer\Tests;

use Yiisoft\Mailer\{BaseMailer, MessageInterface};

class TestMailer extends BaseMailer
{
    public $sentMessages = [];

    /**
     * {@inheritdoc}
     */    
    protected function sendMessage(MessageInterface $message): bool
    {
        $this->sentMessages[] = $message;
        return true;
    }

    /**
     * {@inheritdoc}
     */    
    public function beforeSend(MessageInterface $message): bool
    {
        return parent::beforeSend($message);
    }
}
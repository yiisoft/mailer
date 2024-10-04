<?php

declare(strict_types=1);

namespace Yiisoft\Mailer\Tests\TestAsset;

use InvalidArgumentException;
use Yiisoft\Mailer\Mailer;
use Yiisoft\Mailer\MessageInterface;

final class DummyMailer extends Mailer
{
    public array $sentMessages = [];

    protected function sendMessage(MessageInterface $message): void
    {
        if (empty($message->getSubject())) {
            throw new InvalidArgumentException("Message's subject is required.");
        }

        $this->sentMessages[] = $message;
    }

    public function beforeSend(MessageInterface $message): bool
    {
        return parent::beforeSend($message);
    }

    public function afterSend(MessageInterface $message): void
    {
        parent::afterSend($message);
    }
}

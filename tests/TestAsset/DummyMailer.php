<?php

declare(strict_types=1);

namespace Yiisoft\Mailer\Tests\TestAsset;

use InvalidArgumentException;
use Yiisoft\Mailer\BaseMailer;
use Yiisoft\Mailer\MessageInterface;

final class DummyMailer extends BaseMailer
{
    public array $sentMessages = [];

    protected function sendMessage(MessageInterface $message): void
    {
        if (empty($message->getSubject())) {
            throw new InvalidArgumentException("Message's subject is required.");
        }

        $this->sentMessages[] = $message;
    }

    public function afterSend(MessageInterface $message): void
    {
        parent::afterSend($message);
    }
}

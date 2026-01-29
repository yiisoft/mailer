<?php

declare(strict_types=1);

namespace Yiisoft\Mailer\Debug;

use Yiisoft\Mailer\MailerInterface;
use Yiisoft\Mailer\MessageBodyTemplate;
use Yiisoft\Mailer\MessageInterface;

final class MailerInterfaceProxy implements MailerInterface
{
    public function __construct(
        private MailerInterface $decorated,
        private MailerCollector $collector,
    ) {}

    public function compose($view = null, array $viewParameters = [], array $layoutParameters = []): MessageInterface
    {
        return $this->decorated->compose($view, $viewParameters, $layoutParameters);
    }

    public function send(MessageInterface $message): void
    {
        $this->collector->collectMessage($message);

        $this->decorated->send($message);
    }

    public function sendMultiple(array $messages): array
    {
        $this->collector->collectMessages($messages);
        return $this->decorated->sendMultiple($messages);
    }

    public function withTemplate(MessageBodyTemplate $template): MailerInterface
    {
        return new self(
            $this->decorated->withTemplate($template),
            $this->collector,
        );
    }

    public function withLocale(string $locale): MailerInterface
    {
        return new self(
            $this->decorated->withLocale($locale),
            $this->collector,
        );
    }
}

<?php

declare(strict_types=1);

namespace Yiisoft\Mailer\Tests\TestAsset;

use Throwable;
use Yiisoft\Mailer\MailerInterface;
use Yiisoft\Mailer\MessageInterface;

use function json_encode;

final class DummyMessage implements MessageInterface
{
    private ?MailerInterface $mailer = null;
    private string $charset = '';
    private string $from = '';
    private string $to = '';
    private string $replyTo = '';
    private string $cc = '';
    private string $bcc = '';
    private string $subject = '';
    private string $htmlBody = '';
    private string $textBody = '';
    private ?Throwable $error = null;

    public function withMailer(MailerInterface $mailer): self
    {
        $new = clone $this;
        $new->mailer = $mailer;
        return $new;
    }

    public function getCharset(): string
    {
        return $this->charset;
    }

    public function withCharset(string $charset): self
    {
        $new = clone $this;
        $new->charset = $charset;
        return $new;
    }

    public function getFrom()
    {
        return $this->from;
    }

    public function withFrom($from): self
    {
        $new = clone $this;
        $new->from = $from;
        return $new;
    }

    public function getTo()
    {
        return $this->to;
    }

    public function withTo($to): self
    {
        $new = clone $this;
        $new->to = $to;
        return $new;
    }

    public function getReplyTo()
    {
        return $this->replyTo;
    }

    public function withReplyTo($replyTo): self
    {
        $new = clone $this;
        $new->replyTo = $replyTo;
        return $new;
    }

    public function getCc()
    {
        return $this->cc;
    }

    public function withCc($cc): self
    {
        $new = clone $this;
        $new->cc = $cc;
        return $new;
    }

    public function getBcc()
    {
        return $this->bcc;
    }

    public function withBcc($bcc): self
    {
        $new = clone $this;
        $new->bcc = $bcc;
        return $new;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function withSubject(string $subject): self
    {
        $new = clone $this;
        $new->subject = $subject;
        return $new;
    }

    public function getTextBody(): string
    {
        return $this->textBody;
    }

    public function withTextBody(string $text): self
    {
        $new = clone $this;
        $new->textBody = $text;
        return $new;
    }

    public function getHtmlBody(): string
    {
        return $this->htmlBody;
    }

    public function withHtmlBody(string $html): self
    {
        $new = clone $this;
        $new->htmlBody = $html;
        return $new;
    }

    public function withAttached(string $fileName, array $options = []): self
    {
        return $this;
    }

    public function withAttachedContent(string $content, array $options = []): self
    {
        return $this;
    }

    public function embed(string $fileName, array $options = []): string
    {
        return '';
    }

    public function embedContent(string $content, array $options = []): string
    {
        return '';
    }

    public function getHeader(string $name): array
    {
        return [];
    }

    public function withAddedHeader(string $name, string $value): self
    {
        return $this;
    }

    public function withHeader(string $name, $value): self
    {
        return $this;
    }

    public function withHeaders(array $headers): self
    {
        return $this;
    }

    public function getError(): ?Throwable
    {
        return $this->error;
    }

    public function withError(Throwable $e): self
    {
        $new = clone $this;
        $new->error = $e;
        return $new;
    }

    public function send(): void
    {
        $this->mailer->send($this);
    }

    public function __toString(): string
    {
        return json_encode([
            'from' => $this->from,
            'to' => $this->to,
            'replyTo' => $this->replyTo,
            'cc' => $this->cc,
            'bcc' => $this->bcc,
            'subject' => $this->subject,
            'htmlBody' => $this->htmlBody,
            'textBody' => $this->textBody,
            'error' => (string) $this->error,
        ]);
    }
}

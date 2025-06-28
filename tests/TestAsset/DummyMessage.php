<?php

declare(strict_types=1);

namespace Yiisoft\Mailer\Tests\TestAsset;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use Throwable;
use Yiisoft\Mailer\File;
use Yiisoft\Mailer\MessageInterface;
use Yiisoft\Mailer\Priority;

use function json_encode;

final class DummyMessage implements MessageInterface
{
    private string $charset = '';
    private string|array $from = '';
    private string|array $to = '';
    private string $replyTo = '';
    private string $cc = '';
    private string $bcc = '';
    private string $subject = '';
    private ?DateTimeImmutable $date = null;
    private Priority $priority = Priority::NORMAL;
    private string $returnPath = '';
    private string $sender = '';
    private string $htmlBody = '';
    private string $textBody = '';
    private ?Throwable $error = null;

    public function getCharset(): string
    {
        return $this->charset;
    }

    public function withCharset(string|null $charset): static
    {
        $new = clone $this;
        $new->charset = $charset;
        return $new;
    }

    public function getFrom(): string|array
    {
        return $this->from;
    }

    public function withFrom($from): static
    {
        $new = clone $this;
        $new->from = $from;
        return $new;
    }

    public function getTo(): string|array
    {
        return $this->to;
    }

    public function withTo($to): static
    {
        $new = clone $this;
        $new->to = $to;
        return $new;
    }

    public function getReplyTo(): string
    {
        return $this->replyTo;
    }

    public function withReplyTo($replyTo): static
    {
        $new = clone $this;
        $new->replyTo = $replyTo;
        return $new;
    }

    public function getCc(): string
    {
        return $this->cc;
    }

    public function withCc($cc): static
    {
        $new = clone $this;
        $new->cc = $cc;
        return $new;
    }

    public function getBcc(): string
    {
        return $this->bcc;
    }

    public function withBcc($bcc): static
    {
        $new = clone $this;
        $new->bcc = $bcc;
        return $new;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function withSubject(string|null $subject): static
    {
        $new = clone $this;
        $new->subject = $subject;
        return $new;
    }

    public function getDate(): ?DateTimeImmutable
    {
        return $this->date;
    }

    public function withDate(DateTimeInterface|null $date): static
    {
        if ($date instanceof DateTime) {
            $immutable = new DateTimeImmutable('@' . $date->getTimestamp());
            $date = $immutable->setTimezone($date->getTimezone());
        }

        $new = clone $this;
        $new->date = $date;
        return $new;
    }

    public function getPriority(): Priority
    {
        return $this->priority;
    }

    public function withPriority(Priority|null $priority): static
    {
        $new = clone $this;
        $new->priority = $priority;
        return $new;
    }

    public function getReturnPath(): string
    {
        return $this->returnPath;
    }

    public function withReturnPath(string|null $address): static
    {
        $new = clone $this;
        $new->returnPath = $address;
        return $new;
    }

    public function getSender(): string
    {
        return $this->sender;
    }

    public function withSender(string|null $address): static
    {
        $new = clone $this;
        $new->sender = $address;
        return $new;
    }

    public function getTextBody(): string
    {
        return $this->textBody;
    }

    public function withTextBody(string|null $text): static
    {
        $new = clone $this;
        $new->textBody = $text;
        return $new;
    }

    public function getHtmlBody(): string
    {
        return $this->htmlBody;
    }

    public function withHtmlBody(string|null $html): static
    {
        $new = clone $this;
        $new->htmlBody = $html;
        return $new;
    }

    public function withAttached(File $file): self
    {
        return clone $this;
    }

    public function withEmbedded(File $file): self
    {
        return clone $this;
    }

    public function getHeader(string $name): array
    {
        return [];
    }

    public function withAddedHeader(string $name, string $value): static
    {
        return $this;
    }

    public function withHeader(string $name, $value): static
    {
        return $this;
    }

    public function withHeaders(array|null $headers): static
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

    public function withAddedFrom(array|string $from): static
    {
        return $this;
    }

    public function withAddedTo(array|string $to): static
    {
        return $this;
    }

    public function withAddedReplyTo(array|string $replyTo): static
    {
        return $this;
    }

    public function withAddedCc(array|string $cc): static
    {
        return $this;
    }

    public function withAddedBcc(array|string $bcc): static
    {
        return $this;
    }

    public function getAttachments(): array|null
    {
        return [];
    }

    public function withAttachments(File ...$files): static
    {
        return $this;
    }

    public function withAddedAttachments(File ...$files): static
    {
        return $this;
    }

    public function withoutAttachments(): static
    {
        return $this;
    }

    public function getEmbeddings(): array|null
    {
        return [];
    }

    public function withEmbeddings(File ...$files): static
    {
        return $this;
    }

    public function withAddedEmbeddings(File ...$files): static
    {
        return $this;
    }

    public function withoutEmbeddings(): static
    {
        return $this;
    }

    public function getHeaders(): array|null
    {
        return [];
    }

    public function __toString(): string
    {
        return json_encode([
            'charset' => $this->charset,
            'from' => $this->from,
            'to' => $this->to,
            'replyTo' => $this->replyTo,
            'cc' => $this->cc,
            'bcc' => $this->bcc,
            'subject' => $this->subject,
            'date' => (array) $this->date,
            'priority' => $this->priority,
            'returnPath' => $this->returnPath,
            'sender' => $this->sender,
            'htmlBody' => $this->htmlBody,
            'textBody' => $this->textBody,
            'error' => (string) $this->error,
        ], JSON_THROW_ON_ERROR);
    }
}

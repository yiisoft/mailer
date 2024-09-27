<?php

declare(strict_types=1);

namespace Yiisoft\Mailer;

use DateTimeImmutable;
use DateTimeInterface;
use Throwable;

final class Message implements MessageInterface
{
    /**
     * @param string|string[] $from
     * @param string|string[] $to
     * @param string|string[] $replyTo
     * @param string|string[] $cc
     * @param string|string[] $bcc
     * @param File[] $attachments
     * @param File[] $embeddings
     * @param array[] $headers
     * @psalm-param array<string, string>|string $from
     * @psalm-param array<string, string>|string $to
     * @psalm-param array<string, string>|string $replyTo
     * @psalm-param array<string, string>|string $cc
     * @psalm-param array<string, string>|string $bcc
     * @psalm-param list<File> $attachments
     * @psalm-param list<File> $embeddings
     * @psalm-param array<string,list<string>> $headers
     */
    public function __construct(
        private string $charset = 'utf-8',
        private array|string $from = [],
        private array|string $to = [],
        private array|string $replyTo = [],
        private array|string $cc = [],
        private array|string $bcc = [],
        private string $subject = '',
        private ?DateTimeImmutable $date = null,
        private int $priority = 3,
        private string $returnPath = '',
        private string $sender = '',
        private string $textBody = '',
        private string $htmlBody = '',
        private array $attachments = [],
        private array $embeddings = [],
        private array $headers = [],
        private ?Throwable $error = null,
    ) {
    }

    public function getCharset(): string
    {
        return $this->charset;
    }

    public function withCharset(string $charset): MessageInterface
    {
        $new = clone $this;
        $new->charset = $charset;
        return $new;
    }

    public function getFrom(): array|string
    {
        return $this->from;
    }

    public function withFrom(array|string $from): MessageInterface
    {
        $new = clone $this;
        $new->from = $from;
        return $new;
    }

    public function getTo(): array|string
    {
        return $this->to;
    }

    public function withTo(array|string $to): MessageInterface
    {
        $new = clone $this;
        $new->to = $to;
        return $new;
    }

    public function getReplyTo(): array|string
    {
        return $this->replyTo;
    }

    public function withReplyTo(array|string $replyTo): MessageInterface
    {
        $new = clone $this;
        $new->replyTo = $replyTo;
        return $new;
    }

    public function getCc(): array|string
    {
        return $this->cc;
    }

    public function withCc(array|string $cc): MessageInterface
    {
        $new = clone $this;
        $new->cc = $cc;
        return $new;
    }

    public function getBcc(): array|string
    {
        return $this->bcc;
    }

    public function withBcc(array|string $bcc): MessageInterface
    {
        $new = clone $this;
        $new->bcc = $bcc;
        return $new;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function withSubject(string $subject): MessageInterface
    {
        $new = clone $this;
        $new->subject = $subject;
        return $new;
    }

    public function getDate(): ?DateTimeImmutable
    {
        return $this->date;
    }

    public function withDate(DateTimeInterface $date): MessageInterface
    {
        $new = clone $this;
        $new->date = $date instanceof DateTimeImmutable ? $date : DateTimeImmutable::createFromInterface($date);
        return $new;
    }

    public function getPriority(): int
    {
        return $this->priority;
    }

    public function withPriority(int $priority): MessageInterface
    {
        $new = clone $this;
        $new->priority = $priority;
        return $new;
    }

    public function getReturnPath(): string
    {
        return $this->returnPath;
    }

    public function withReturnPath(string $address): MessageInterface
    {
        $new = clone $this;
        $new->returnPath = $address;
        return $new;
    }

    public function getSender(): string
    {
        return $this->sender;
    }

    public function withSender(string $address): MessageInterface
    {
        $new = clone $this;
        $new->sender = $address;
        return $new;
    }

    public function getHtmlBody(): string
    {
        return $this->htmlBody;
    }

    public function withHtmlBody(string $html): MessageInterface
    {
        $new = clone $this;
        $new->htmlBody = $html;
        return $new;
    }

    public function getTextBody(): string
    {
        return $this->textBody;
    }

    public function withTextBody(string $text): MessageInterface
    {
        $new = clone $this;
        $new->textBody = $text;
        return $new;
    }

    public function getAttachments(): array
    {
        return $this->attachments;
    }

    /**
     * @no-named-arguments
     */
    public function withAttachment(File ...$files): MessageInterface
    {
        $new = clone $this;
        $new->attachments = $files;
        return $new;
    }

    /**
     * @no-named-arguments
     */
    public function withAddedAttachment(File ...$files): MessageInterface
    {
        $new = clone $this;
        $new->attachments = array_merge($this->attachments, $files);
        return $new;
    }

    public function getEmbeddings(): array
    {
        return $this->embeddings;
    }

    /**
     * @no-named-arguments
     */
    public function withEmbedding(File ...$files): MessageInterface
    {
        $new = clone $this;
        $new->embeddings = $files;
        return $new;
    }

    /**
     * @no-named-arguments
     */
    public function withAddedEmbedding(File ...$files): MessageInterface
    {
        $new = clone $this;
        $new->embeddings = array_merge($this->embeddings, $files);
        return $new;
    }

    public function getHeader(string $name): array
    {
        return $this->headers[$name] ?? [];
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function withAddedHeader(string $name, string $value): MessageInterface
    {
        $new = clone $this;
        $new->headers[$name][] = $value;
        return $new;
    }

    public function withHeader(string $name, array|string $value): MessageInterface
    {
        $new = clone $this;
        $new->headers[$name] = (array) $value;
        return $new;
    }

    public function withHeaders(array $headers): MessageInterface
    {
        $new = clone $this;
        $new->headers = array_map(
            static fn(string|array $value): array => (array) $value,
            $headers,
        );
        return $new;
    }

    public function getError(): ?Throwable
    {
        return $this->error;
    }

    public function withError(Throwable $e): MessageInterface
    {
        $new = clone $this;
        $new->error = $e;
        return $new;
    }

    public function __toString(): string
    {
        $result = [];
        foreach ($this->headers as $name => $values) {
            foreach ($values as $value) {
                $result[] = $name . ': ' . $value;
            }
        }
        $result[] = $this->textBody;
        return implode("\n", $result);
    }
}

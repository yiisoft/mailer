<?php

declare(strict_types=1);

namespace Yiisoft\Mailer;

use DateTimeImmutable;
use DateTimeInterface;

/**
 * Mail message class that represents the settings and content of an email, such as the sender, recipient, subject,
 * body, etc. Messages are sent by a {@see MailerInterface}.
 *
 * @api
 */
final class Message implements MessageInterface
{
    /**
     * @var array[]|null
     * @psalm-var array<string,list<string>>|null
     */
    private ?array $headers;

    /**
     * @param string|string[]|null $from The sender email address(es). You may also specify sender name in addition
     * to email address using format: `[email => name]`.
     * @param string|string[]|null $to The receiver email address(es). You may also specify sender name in addition
     * to email address using format: `[email => name]`.
     * @param string|string[]|null $replyTo The reply-to address(es) of this message. You may also specify sender name
     * in addition to email address using format: `[email => name]`.
     * @param string|string[]|null $cc The additional copy receiver address(es) of this message. You may also specify
     * sender name in addition to email address using format: `[email => name]`.
     * @param string|string[]|null $bcc The hidden copy receiver address(es) of this message. You may also specify
     * sender name in addition to email address using format: `[email => name]`.
     * @param File[]|null $attachments The attached files.
     * @param File[]|null $embeddings The embedded files.
     * @param array[]|null $headers The custom headers in format: `[name => value|value[]]`.
     *
     * @psalm-param list<File>|null $attachments
     * @psalm-param list<File>|null $embeddings
     * @psalm-param array<string,string|list<string>>|null $headers
     */
    public function __construct(
        private ?string $charset = null,
        private array|string|null $from = null,
        private array|string|null $to = null,
        private array|string|null $replyTo = null,
        private array|string|null $cc = null,
        private array|string|null $bcc = null,
        private ?string $subject = null,
        private ?DateTimeImmutable $date = null,
        private ?Priority $priority = null,
        private ?string $returnPath = null,
        private ?string $sender = null,
        private ?string $textBody = null,
        private ?string $htmlBody = null,
        private ?array $attachments = null,
        private ?array $embeddings = null,
        ?array $headers = null,
    ) {
        $this->setHeaders($headers);
    }

    public function __toString(): string
    {
        $result = [];
        if ($this->headers !== null) {
            foreach ($this->headers as $name => $values) {
                foreach ($values as $value) {
                    $result[] = $name . ': ' . $value;
                }
            }
        }
        $result[] = $this->textBody;
        return implode("\n", $result);
    }

    public function getCharset(): ?string
    {
        return $this->charset;
    }

    public function withCharset(?string $charset): static
    {
        $new = clone $this;
        $new->charset = $charset;
        return $new;
    }

    public function getFrom(): array|string|null
    {
        return $this->from;
    }

    public function withFrom(array|string|null $from): static
    {
        $new = clone $this;
        $new->from = $from;
        return $new;
    }

    public function withAddedFrom(array|string $from): static
    {
        return $this->withFrom(
            $this->mergeAddresses($this->from, $from),
        );
    }

    public function getTo(): array|string|null
    {
        return $this->to;
    }

    public function withTo(array|string|null $to): static
    {
        $new = clone $this;
        $new->to = $to;
        return $new;
    }

    public function withAddedTo(array|string $to): static
    {
        return $this->withTo(
            $this->mergeAddresses($this->to, $to),
        );
    }

    public function getReplyTo(): array|string|null
    {
        return $this->replyTo;
    }

    public function withReplyTo(array|string|null $replyTo): static
    {
        $new = clone $this;
        $new->replyTo = $replyTo;
        return $new;
    }

    public function withAddedReplyTo(array|string $replyTo): static
    {
        return $this->withReplyTo(
            $this->mergeAddresses($this->replyTo, $replyTo),
        );
    }

    public function getCc(): array|string|null
    {
        return $this->cc;
    }

    public function withCc(array|string|null $cc): static
    {
        $new = clone $this;
        $new->cc = $cc;
        return $new;
    }

    public function withAddedCc(array|string $cc): static
    {
        return $this->withCc(
            $this->mergeAddresses($this->cc, $cc),
        );
    }

    public function getBcc(): array|string|null
    {
        return $this->bcc;
    }

    public function withBcc(array|string|null $bcc): static
    {
        $new = clone $this;
        $new->bcc = $bcc;
        return $new;
    }

    public function withAddedBcc(array|string $bcc): static
    {
        return $this->withBcc(
            $this->mergeAddresses($this->bcc, $bcc),
        );
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function withSubject(?string $subject): static
    {
        $new = clone $this;
        $new->subject = $subject;
        return $new;
    }

    public function getDate(): ?DateTimeImmutable
    {
        return $this->date;
    }

    public function withDate(?DateTimeInterface $date): static
    {
        $new = clone $this;
        if ($date === null) {
            $new->date = $date;
        } else {
            $new->date = $date instanceof DateTimeImmutable ? $date : DateTimeImmutable::createFromInterface($date);
        }
        return $new;
    }

    public function getPriority(): ?Priority
    {
        return $this->priority;
    }

    public function withPriority(?Priority $priority): static
    {
        $new = clone $this;
        $new->priority = $priority;
        return $new;
    }

    public function getReturnPath(): ?string
    {
        return $this->returnPath;
    }

    public function withReturnPath(?string $address): static
    {
        $new = clone $this;
        $new->returnPath = $address;
        return $new;
    }

    public function getSender(): ?string
    {
        return $this->sender;
    }

    public function withSender(?string $address): static
    {
        $new = clone $this;
        $new->sender = $address;
        return $new;
    }

    public function getHtmlBody(): ?string
    {
        return $this->htmlBody;
    }

    public function withHtmlBody(?string $html): static
    {
        $new = clone $this;
        $new->htmlBody = $html;
        return $new;
    }

    public function getTextBody(): ?string
    {
        return $this->textBody;
    }

    public function withTextBody(?string $text): static
    {
        $new = clone $this;
        $new->textBody = $text;
        return $new;
    }

    public function getAttachments(): ?array
    {
        return $this->attachments;
    }

    /**
     * @no-named-arguments
     */
    public function withAttachments(File ...$files): static
    {
        $new = clone $this;
        $new->attachments = $files;
        return $new;
    }

    /**
     * @no-named-arguments
     */
    public function withAddedAttachments(File ...$files): static
    {
        $new = clone $this;
        $new->attachments = array_merge($this->attachments ?? [], $files);
        return $new;
    }

    public function withoutAttachments(): static
    {
        $new = clone $this;
        $new->attachments = null;
        return $new;
    }

    public function getEmbeddings(): ?array
    {
        return $this->embeddings;
    }

    /**
     * @no-named-arguments
     */
    public function withEmbeddings(File ...$files): static
    {
        $new = clone $this;
        $new->embeddings = $files;
        return $new;
    }

    /**
     * @no-named-arguments
     */
    public function withAddedEmbeddings(File ...$files): static
    {
        $new = clone $this;
        $new->embeddings = array_merge($this->embeddings ?? [], $files);
        return $new;
    }

    public function withoutEmbeddings(): static
    {
        $new = clone $this;
        $new->embeddings = null;
        return $new;
    }

    public function getHeader(string $name): array
    {
        return $this->headers[$name] ?? [];
    }

    public function getHeaders(): ?array
    {
        return $this->headers;
    }

    public function withAddedHeader(string $name, string $value): static
    {
        $new = clone $this;
        $new->headers ??= [];
        $new->headers[$name][] = $value;
        return $new;
    }

    public function withHeader(string $name, array|string $value): static
    {
        $new = clone $this;
        $new->headers[$name] = (array) $value;
        return $new;
    }

    public function withHeaders(?array $headers): static
    {
        $new = clone $this;
        $new->setHeaders($headers);
        return $new;
    }

    /**
     * @psalm-param array<string,string|list<string>>|null $headers
     */
    private function setHeaders(?array $headers): void
    {
        $this->headers = HeadersNormalizer::normalize($headers);
    }

    /**
     * @param string|string[]|null $base
     * @param string|string[] $added
     * @return string|string[]
     */
    private function mergeAddresses(array|string|null $base, array|string $added): array|string
    {
        return $base === null
            ? $added
            : array_merge((array) $base, (array) $added);
    }
}

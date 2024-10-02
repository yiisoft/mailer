<?php

declare(strict_types=1);

namespace Yiisoft\Mailer;

use DateTimeImmutable;
use DateTimeInterface;

/**
 * Mail message class that represents the settings and content of an email, such as the sender, recipient, subject,
 * body, etc. Messages are sent by a {@see MailerInterface}.
 */
final class Message implements MessageInterface
{
    /**
     * @var array[]
     * @psalm-var array<string,list<string>>
     */
    private array $headers;

    /**
     * @param string|string[] $from The sender email address(es). You may also specify sender name in addition to email
     * address using format: `[email => name]`.
     * @param string|string[] $to The receiver email address(es). You may also specify sender name in addition to email
     * address using format: `[email => name]`.
     * @param string|string[] $replyTo The reply-to address(es) of this message. You may also specify sender name in
     * addition to email address using format: `[email => name]`.
     * @param string|string[] $cc The additional copy receiver address(es) of this message. You may also specify sender
     * name in addition to email address using format: `[email => name]`.
     * @param string|string[] $bcc The hidden copy receiver address(es) of this message. You may also specify sender
     * name in addition to email address using format: `[email => name]`.
     * @param File[] $attachments The attached files.
     * @param File[] $embeddings The embedded files.
     * @param array[] $headers The custom headers in format: `[name => value|value[]]`.
     *
     * @psalm-param list<File> $attachments
     * @psalm-param list<File> $embeddings
     * @psalm-param array<string,string|list<string>> $headers
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
        private Priority $priority = Priority::NORMAL,
        private string $returnPath = '',
        private string $sender = '',
        private string $textBody = '',
        private string $htmlBody = '',
        private array $attachments = [],
        private array $embeddings = [],
        array $headers = [],
    ) {
        $this->setHeaders($headers);
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

    public function getPriority(): Priority
    {
        return $this->priority;
    }

    public function withPriority(Priority $priority): MessageInterface
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
    public function withAttachments(File ...$files): MessageInterface
    {
        $new = clone $this;
        $new->attachments = $files;
        return $new;
    }

    /**
     * @no-named-arguments
     */
    public function withAddedAttachments(File ...$files): MessageInterface
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
    public function withEmbeddings(File ...$files): MessageInterface
    {
        $new = clone $this;
        $new->embeddings = $files;
        return $new;
    }

    /**
     * @no-named-arguments
     */
    public function withAddedEmbeddings(File ...$files): MessageInterface
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
        $new->setHeaders($headers);
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

    /**
     * @psalm-param array<string,string|list<string>> $headers
     */
    private function setHeaders(array $headers): void
    {
        $this->headers = array_map(
            static fn(string|array $value): array => (array) $value,
            $headers,
        );
    }
}

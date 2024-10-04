<?php

declare(strict_types=1);

namespace Yiisoft\Mailer;

use DateTimeImmutable;

final class MessageSettings
{
    /**
     * @var array[]|null
     * @psalm-var array<string,list<string>>|null
     */
    public readonly array|null $headers;

    /**
     * @var array[]|null
     * @psalm-var array<string,list<string>>|null
     */
    public readonly array|null $overwriteHeaders;

    /**
     * @param string|string[]|null $from The sender email address(es). You may also specify sender name in addition
     * to email address using format: `[email => name]`.
     * @param string|string[]|null $addFrom The sender email address(es) that always will be added to message. You may
     * also specify sender name in addition to email address using format: `[email => name]`.
     * @param string|string[]|null $to The receiver email address(es). You may also specify sender name in addition
     * to email address using format: `[email => name]`.
     * @param string|string[]|null $addTo The receiver email address(es) that always will be added to message. You may
     * also specify sender name in addition to email address using format: `[email => name]`.
     * @param string|string[]|null $replyTo The reply-to address(es) of this message. You may also specify sender name
     * in addition to email address using format: `[email => name]`.
     * @param string|string[]|null $addReplyTo The reply-to address(es) that always will be added to message. You may
     * also specify sender name in addition to email address using format: `[email => name]`.
     * @param string|string[]|null $cc The additional copy receiver address(es) of this message. You may also specify
     * sender name in addition to email address using format: `[email => name]`.
     * @param string|string[]|null $addCc The additional copy receiver address(es) that always will be added to message.
     * You may also specify sender name in addition to email address using format: `[email => name]`.
     * @param string|string[]|null $bcc The hidden copy receiver address(es) of this message. You may also specify
     * sender name in addition to email address using format: `[email => name]`.
     * @param string|string[]|null $addBcc The hidden copy receiver address(es) that always will be added to message.
     * You may also specify sender name in addition to email address using format: `[email => name]`.
     * @param File[]|null $attachments The attached files.
     * @param File[]|null $addAttachments The attached files that always will be added to message.
     * @param File[]|null $embeddings The embedded files.
     * @param File[]|null $addEmbeddings The embedded files that always will be added to message.
     * @param array[]|null $headers The custom headers in format `[name => value|value[]]`.
     * @param array[]|null $overwriteHeaders The custom headers in format `[name => value|value[]]` that always will be
     * added to message.
     *
     * @psalm-param list<File>|null $attachments
     * @psalm-param list<File>|null $addAttachments
     * @psalm-param list<File>|null $embeddings
     * @psalm-param list<File>|null $addEmbeddings
     * @psalm-param array<string,string|list<string>>|null $headers
     * @psalm-param array<string,string|list<string>>|null $overwriteHeaders
     */
    public function __construct(
        public readonly string|null $charset = null,
        public readonly array|string|null $from = null,
        public readonly array|string|null $addFrom = null,
        public readonly array|string|null $to = null,
        public readonly array|string|null $addTo = null,
        public readonly array|string|null $replyTo = null,
        public readonly array|string|null $addReplyTo = null,
        public readonly array|string|null $cc = null,
        public readonly array|string|null $addCc = null,
        public readonly array|string|null $bcc = null,
        public readonly array|string|null $addBcc = null,
        public readonly string|null $subject = null,
        public readonly DateTimeImmutable|null $date = null,
        public readonly Priority|null $priority = null,
        public readonly string|null $returnPath = null,
        public readonly string|null $sender = null,
        public readonly string|null $textBody = null,
        public readonly string|null $htmlBody = null,
        public readonly array|null $attachments = null,
        public readonly array|null $addAttachments = null,
        public readonly array|null $embeddings = null,
        public readonly array|null $addEmbeddings = null,
        array|null $headers = null,
        array|null $overwriteHeaders = null,
    ) {
        $this->headers = HeadersNormalizer::normalize($headers);
        $this->overwriteHeaders = HeadersNormalizer::normalize($overwriteHeaders);
    }

    public function applyTo(MessageInterface $message): MessageInterface
    {
        if ($this->charset !== null && $message->getCharset() === null) {
            $message = $message->withCharset($this->charset);
        }

        if ($this->from !== null && $message->getFrom() === null) {
            $message = $message->withFrom($this->from);
        }
        if ($this->addFrom !== null) {
            $message = $message->withAddedFrom($this->addFrom);
        }

        if ($this->to !== null && $message->getTo() === null) {
            $message = $message->withTo($this->to);
        }
        if ($this->addTo !== null) {
            $message = $message->withAddedTo($this->addTo);
        }

        if ($this->replyTo !== null && $message->getReplyTo() === null) {
            $message = $message->withReplyTo($this->replyTo);
        }
        if ($this->addReplyTo !== null) {
            $message = $message->withAddedReplyTo($this->addReplyTo);
        }

        if ($this->cc !== null && $message->getCc() === null) {
            $message = $message->withCc($this->cc);
        }
        if ($this->addCc !== null) {
            $message = $message->withAddedCc($this->addCc);
        }

        if ($this->bcc !== null && $message->getBcc() === null) {
            $message = $message->withBcc($this->bcc);
        }
        if ($this->addBcc !== null) {
            $message = $message->withAddedBcc($this->addBcc);
        }

        if ($this->subject !== null && $message->getSubject() === null) {
            $message = $message->withSubject($this->subject);
        }

        if ($this->date !== null && $message->getDate() === null) {
            $message = $message->withDate($this->date);
        }

        if ($this->priority !== null && $message->getPriority() === null) {
            $message = $message->withPriority($this->priority);
        }

        if ($this->returnPath !== null && $message->getReturnPath() === null) {
            $message = $message->withReturnPath($this->returnPath);
        }

        if ($this->sender !== null && $message->getSender() === null) {
            $message = $message->withSender($this->sender);
        }

        if ($this->textBody !== null && $message->getTextBody() === null) {
            $message = $message->withTextBody($this->textBody);
        }

        if ($this->htmlBody !== null && $message->getHtmlBody() === null) {
            $message = $message->withHtmlBody($this->htmlBody);
        }

        if ($this->attachments !== null && $message->getAttachments() === null) {
            $message = $message->withAttachments(...$this->attachments);
        }
        if ($this->addAttachments !== null) {
            $message = $message->withAddedAttachments(...$this->addAttachments);
        }

        if ($this->embeddings !== null && $message->getEmbeddings() === null) {
            $message = $message->withEmbeddings(...$this->embeddings);
        }
        if ($this->addEmbeddings !== null) {
            $message = $message->withAddedEmbeddings(...$this->addEmbeddings);
        }

        if ($this->headers !== null && $message->getHeaders() === null) {
            $message = $message->withHeaders($this->headers);
        }
        if ($this->overwriteHeaders !== null) {
            foreach ($this->overwriteHeaders as $headerName => $headerValue) {
                $message = $message->withHeader($headerName, $headerValue);
            }
        }

        return $message;
    }
}

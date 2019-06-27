<?php
namespace Yiisoft\Mailer\Tests;

use Yiisoft\Mailer\{BaseMessage, MessageInterface};

/**
 * Test Message class
 */
class TestMessage extends BaseMessage
{
    public $id;

    public $encoding;

    public $charset = '';

    public $from;

    public $replyTo;

    public $to;

    public $cc;

    public $bcc;

    public $subject = '';

    public $textBody = '';

    public $htmlBody = '';

    public function getCharset(): string
    {
        return $this->charset;
    }
    
    public function setCharset(string $charset): MessageInterface
    {
        return $this;
    }

    public function getFrom()
    {
        return $this->from;
    }

    public function setFrom($from): MessageInterface
    {
        $this->from = $from;
        return $this;
    }

    public function getTo()
    {
        return $this->to;
    }

    public function setTo($to): MessageInterface
    {
        $this->to = $to;
        return $this;
    }

    public function getCc()
    {
        return $this->cc;
    }

    public function setCc($cc): MessageInterface
    {
        $this->cc = $cc;
        return $this;
    }

    public function getBcc()
    {
        return $this->bcc;
    }
    
    public function setBcc($bcc): MessageInterface
    {
        $this->bcc = $bcc;
        return $this;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): MessageInterface
    {
        $this->subject = $subject;
        return $this;
    }

    public function getReplyTo()
    {
        return $this->replyTo;
    }

    public function setReplyTo($replyTo): MessageInterface
    {
        $this->replyTo = $replyTo;
        return $this;
    }

    public function getTextBody(): string
    {
        return $this->textBody;
    }

    public function setTextBody(string $text): MessageInterface
    {
        $this->textBody = $text;
        return $this;
    }

    public function getHtmlBody(): string
    {
        return $this->htmlBody;
    }

    public function setHtmlBody(string $html): MessageInterface
    {
        $this->htmlBody = $html;
        return $this;
    }

    public function attachContent(string $content, array $options = []): MessageInterface
    {
        return $this;
    }

    public function attach(string $fileName, array $options = []): MessageInterface
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

    public function toString(): string
    {
        $s = var_export($this, true);
        return $s;
    }

    public function addHeader(string $name, string $value): MessageInterface
    {
        return $this;
    }

    public function setHeader(string $name, $value): MessageInterface
    {
        return $this;
    }

    public function getHeader(string $name): array
    {
        return [];
    }

    public function setHeaders(array $headers): MessageInterface
    {
        return $this;
    }
}

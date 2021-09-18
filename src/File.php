<?php

declare(strict_types=1);

namespace Yiisoft\Mailer;

use Exception;
use RuntimeException;

use function bin2hex;
use function is_file;
use function random_bytes;

/**
 * File is a data object that stores data for attaching a file to a mail message.
 */
final class File
{
    /**
     * @var string|null The file ID.
     */
    private ?string $id = null;

    /**
     * @var string|null The name that should be used to attach the file.
     */
    private ?string $name;

    /**
     * @var string|null The full path to the file.
     */
    private ?string $path;

    /**
     * @var string|null The content that should be used to attach the file.
     */
    private ?string $content;

    /**
     * @var string|null MIME type that should be used to attach the file.
     */
    private ?string $contentType;

    /**
     * @param string|null $name The name that should be used to attach the file.
     * @param string|null $path The full path to the file.
     * @param string|null $content The content that should be used to attach the file.
     * @param string|null $contentType MIME type that should be used to attach the file.
     */
    private function __construct(?string $name, ?string $path, ?string $content, ?string $contentType)
    {
        $this->name = $name;
        $this->path = $path;
        $this->content = $content;
        $this->contentType = $contentType;
    }

    /**
     * Creates a new file instance from the specified content.
     *
     * @param string $content The content that should be used to attach the file.
     * @param string|null $name The name that should be used to attach the file.
     * @param string|null $contentType MIME type that should be used to attach the file.
     *
     * @return self
     */
    public static function fromContent(string $content, string $name = null, string $contentType = null): self
    {
        return new self($name, null, $content, $contentType);
    }

    /**
     * Creates a new file instance from the specified full path to the file.
     *
     * @param string $path The full path to the file.
     * @param string|null $name The name that should be used to attach the file.
     * @param string|null $contentType MIME type that should be used to attach the file.
     *
     * @throws RuntimeException If the specified file does not exist.
     *
     * @return self
     */
    public static function fromPath(string $path, string $name = null, string $contentType = null): self
    {
        if (!is_file($path)) {
            throw new RuntimeException("The file {$path} does not exist.");
        }

        return new self($name, $path, null, $contentType);
    }

    /**
     * Returns the file ID.
     *
     * @throws Exception {@see https://www.php.net/manual/en/function.random-bytes.php}
     *
     * @return string The file ID.
     */
    public function id(): string
    {
        if ($this->id === null) {
            $this->id = bin2hex(random_bytes(16)) . '@app';
        }

        return $this->id;
    }

    /**
     * Returns the file CID source.
     *
     * @throws Exception {@see https://www.php.net/manual/en/function.random-bytes.php}
     *
     * @return string The file CID source.
     */
    public function cid(): string
    {
        return "cid:{$this->id()}";
    }

    /**
     * Returns the name that should be used to attach the file.
     *
     * @return string|null The name that should be used to attach the file.
     */
    public function name(): ?string
    {
        return $this->name;
    }

    /**
     * Returns the full path to the file.
     *
     * @return string|null The full path to the file.
     */
    public function path(): ?string
    {
        return $this->path;
    }

    /**
     * Returns the content that should be used to attach the file.
     *
     * @return string|null The content that should be used to attach the file.
     */
    public function content(): ?string
    {
        return $this->content;
    }

    /**
     * Returns the MIME type that should be used to attach the file.
     *
     * @return string|null MIME type that should be used to attach the file.
     */
    public function contentType(): ?string
    {
        return $this->contentType;
    }
}

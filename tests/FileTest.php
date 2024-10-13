<?php

declare(strict_types=1);

namespace Yiisoft\Mailer\Tests;

use PHPUnit\Framework\TestCase;
use RuntimeException;
use Yiisoft\Mailer\File;

use function strlen;
use function strpos;

final class FileTest extends TestCase
{
    public function testFromContent(): void
    {
        $file1 = File::fromContent('');

        $this->assertSame(36, strlen($file1->id()));
        $this->assertSame(32, strpos($file1->id(), '@app'));
        $this->assertSame($file1->cid(), "cid:{$file1->id()}");
        $this->assertSame('', $file1->content());
        $this->assertNull($file1->contentType());
        $this->assertNull($file1->name());
        $this->assertNull($file1->path());

        $file2 = File::fromContent('Content', 'text.txt', 'plain/text');

        $this->assertNotSame($file1, $file2);

        $this->assertSame(36, strlen($file2->id()));
        $this->assertSame(32, strpos($file2->id(), '@app'));
        $this->assertSame($file2->cid(), "cid:{$file2->id()}");
        $this->assertSame('Content', $file2->content());
        $this->assertSame('plain/text', $file2->contentType());
        $this->assertSame('text.txt', $file2->name());
        $this->assertNull($file2->path());
    }

    public function testFromPath(): void
    {
        $file1 = File::fromPath(__FILE__);

        $this->assertSame(36, strlen($file1->id()));
        $this->assertSame(32, strpos($file1->id(), '@app'));
        $this->assertSame($file1->cid(), "cid:{$file1->id()}");
        $this->assertSame(__FILE__, $file1->path());
        $this->assertNull($file1->contentType());
        $this->assertNull($file1->name());
        $this->assertNull($file1->content());

        $file2 = File::fromPath(__FILE__, 'text.txt', 'plain/text');

        $this->assertSame($file1->path(), $file2->path());
        $this->assertNotSame($file1, $file2);

        $this->assertSame(36, strlen($file2->id()));
        $this->assertSame(32, strpos($file2->id(), '@app'));
        $this->assertSame($file2->cid(), "cid:{$file2->id()}");
        $this->assertSame(__FILE__, $file2->path());
        $this->assertSame('plain/text', $file2->contentType());
        $this->assertSame('text.txt', $file2->name());
        $this->assertNull($file2->content());
    }

    public function testFromPathThrowExceptionIfFileNotExist(): void
    {
        $this->expectException(RuntimeException::class);
        File::fromPath('file-not-exist');
    }

    public function testFromPathThrowExceptionIfFileIsDirectory(): void
    {
        $this->expectException(RuntimeException::class);
        File::fromPath(__DIR__);
    }
}

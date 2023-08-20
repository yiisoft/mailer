<?php

declare(strict_types=1);

namespace Yiisoft\Mailer\Tests\Debug;

use Yiisoft\Mailer\Debug\MailerCollector;
use Yiisoft\Mailer\Tests\TestAsset\DummyMessage;
use Yiisoft\Yii\Debug\Collector\CollectorInterface;
use Yiisoft\Yii\Debug\Tests\Shared\AbstractCollectorTestCase;

final class MailerCollectorTest extends AbstractCollectorTestCase
{
    /**
     * @param CollectorInterface|MailerCollector $collector
     */
    protected function collectTestData(CollectorInterface|MailerCollector $collector): void
    {
        $messages = $this->createMessages();
        $collector->collectMessages($messages);
    }

    protected function getCollector(): CollectorInterface
    {
        return new MailerCollector();
    }

    protected function checkSummaryData(array $data): void
    {
        parent::checkSummaryData($data);
        $this->assertSame(['total' => 1], $data['mailer']);
    }

    protected function checkCollectedData(array $data): void
    {
        parent::checkCollectedData($data);
        $this->assertCount(1, $data['messages']);
        $message = $data['messages'][0];
        $this->assertSame(['me@mail.com' => 'Its me'], $message['from']);
        $this->assertSame(['you@yiisoft.com' => 'Its you'], $message['to']);
        $this->assertSame('Test subject', $message['subject']);
        $this->assertSame('Test text body', $message['textBody']);
        $this->assertSame('<b>Test html body</b>', $message['htmlBody']);
    }

    private function createMessages(): array
    {
        return [
            (new DummyMessage())
                ->withFrom(['me@mail.com' => 'Its me'])
                ->withTo(['you@yiisoft.com' => 'Its you'])
                ->withSubject('Test subject')
                ->withTextBody('Test text body')
                ->withHtmlBody('<b>Test html body</b>'),
        ];
    }
}
